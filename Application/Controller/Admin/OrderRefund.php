<?php

namespace Mollie\Payment\Application\Controller\Admin;

use Mollie\Payment\Application\Helper\Payment as PaymentHelper;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;
use Mollie\Payment\Application\Model\RequestLog;
use OxidEsales\Eshop\Core\Field;

class OrderRefund extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Template to be used
     *
     * @var string
     */
    protected $_sTemplate = "mollie_order_refund.tpl";

    /**
     * Order object
     *
     * @var Order|null
     */
    protected $_oOrder = null;

    /**
     * Error message property
     *
     * @var string|bool
     */
    protected $_sErrorMessage = false;

    /**
     * Mollie ApiOrder
     *
     * @var \Mollie\Api\Resources\Payment|\Mollie\Api\Resources\Order
     */
    protected $_oMollieApiOrder = null;

    /**
     * Flag if a successful refund was executed
     *
     * @var bool|null
     */
    protected $_blSuccessfulRefund = null;

    /**
     * Array of refund items
     *
     * @var array|null
     */
    protected $_aRefundItems = null;

    /**
     * All voucher types
     *
     * @var array
     */
    protected $_aVoucherTypes = ['voucher', 'discount'];

    /**
     * Loads current order
     *
     * @return null|object|Order
     */
    public function getOrder()
    {
        if ($this->_oOrder === null) {
            $oOrder = oxNew(Order::class);

            $soxId = $this->getEditObjectId();
            if (isset($soxId) && $soxId != "-1") {
                $oOrder->load($soxId);

                $this->_oOrder = $oOrder;
            }
        }
        return $this->_oOrder;
    }

    /**
     * Returns if refund was successful
     *
     * @return bool
     */
    public function wasRefundSuccessful()
    {
        return $this->_blSuccessfulRefund;
    }

    /**
     * Returns errormessage
     *
     * @return bool|string
     */
    public function getErrorMessage()
    {
        return $this->_sErrorMessage;
    }

    /**
     * Sets error message
     *
     * @param string $sError
     */
    public function setErrorMessage($sError)
    {
        $this->_sErrorMessage = $sError;
    }

    /**
     * Main render method
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $oOrder = $this->getOrder();
        if ($oOrder) {
            $this->_aViewData["edit"] = $oOrder;
        }

        return $this->_sTemplate;
    }

    /**
     * Format prices to always have 2 decimal places
     *
     * @param double $dPrice
     * @return string
     */
    protected function formatPrice($dPrice)
    {
        return number_format($dPrice, 2, '.', '');
    }

    /**
     * Get refund type - quantity or amount
     *
     * @return string
     */
    protected function getRefundType()
    {
        $sType = Registry::getRequest()->getRequestEscapedParameter('mollie_refund_type');
        if (empty($sType)) {
            $sType = 'amount';
        }
        return $sType;
    }

    protected function getRefundItemsFromRequest()
    {
        $sSelectKey = $sSelectKey = 'refund_'.$this->getRefundType();
        $aRefundItems = Registry::getRequest()->getRequestEscapedParameter('aOrderArticles');
        foreach ($aRefundItems as $sKey => $aRefundItem) {
            foreach ($aRefundItem as $sItemKey => $item) {
                $aRefundItem[$sItemKey] = str_replace(',', '.', $aRefundItem[$sItemKey]);
            }
            if (isset($aRefundItem[$sSelectKey])) {
                $dValue = $aRefundItem[$sSelectKey];
                $aRefundItems[$sKey] = [$sSelectKey => $aRefundItem[$sSelectKey]];
            } else {
                $dValue = $aRefundItem['refund_amount'];
            }
            if ($dValue <= 0) {
                unset($aRefundItems[$sKey]);
            }
            $aBasketItem = $this->getRefundItemById($sKey);

            if ($aBasketItem['type'] == 'product') {
                $sCounterSelectKey = 'refund_amount';
                if ($sSelectKey == 'refund_amount') {
                    $sCounterSelectKey = 'refund_quantity';
                }
                if (isset($aRefundItem[$sCounterSelectKey])) {
                    unset($aRefundItems[$sKey][$sCounterSelectKey]);
                }
            }
        }
        return $aRefundItems;
    }

    /**
     * Returns single refund item by id
     *
     * @param string $sId
     * @return array|null
     */
    protected function getRefundItemById($sId)
    {
        $aRefundItems = $this->getRefundItems();
        foreach ($aRefundItems as $aRefundItem) {
            if ($aRefundItem['id'] == $sId) {
                return $aRefundItem;
            }
        }
        return null;
    }

    /**
     * Mollie needs its own line id for the refund so we have to collect it
     *
     * @param string $sId
     * @return string
     */
    protected function getMollieLineIdFromApi($sId)
    {
        $oMollieApiOrder = $this->getMollieApiOrder();
        if ($oMollieApiOrder instanceof \Mollie\Api\Resources\Order) {
            $aLines = $oMollieApiOrder->lines();
            foreach ($aLines as $oLine) {
                if ($oLine->sku == $sId) {
                    return $oLine->id;
                }
            }
        }
        return $sId;
    }

    /**
     * Returns remaining refundable amount from Mollie Api
     *
     * @return double
     */
    public function getRemainingRefundableAmount()
    {
        $oMollieApiOrder = $this->getMollieApiOrder();

        return ($oMollieApiOrder->amount->value - $oMollieApiOrder->amountRefunded->value);
    }

    /**
     * Generate refund lines for the Mollie API request
     *
     * @return array
     */
    protected function getPartialRefundParameters()
    {
        $aParams = ['lines' => []];
        $dAmount = 0;

        $aRefundItems = $this->getRefundItemsFromRequest();
        foreach ($aRefundItems as $sId => $aRefundItem) {
            $aBasketItem = $this->getRefundItemById($sId);
            if ($aBasketItem['type'] == 'product') {
                $sId = $aBasketItem['artnum']; // Mollie doesnt know the orderarticles id - only the artnum
            }

            $aLine = ['id' => $this->getMollieLineIdFromApi($sId)];
            if (isset($aRefundItem['refund_amount'])) {
                if ($aRefundItem['refund_amount'] > $aBasketItem['totalPrice']) { // check if higher amount than payed
                    $aRefundItem['refund_amount'] = $aBasketItem['totalPrice'];
                }
                if (in_array($aBasketItem['type'], $this->_aVoucherTypes)) {
                    $aRefundItem['refund_amount'] = $aRefundItem['refund_amount'] * -1;
                }
                $aLine['amount'] = [
                    "currency" => $this->getOrder()->oxorder__oxcurrency->value,
                    "value" => $this->formatPrice($aRefundItem['refund_amount'])
                ];
                $dAmount += $aRefundItem['refund_amount'];
            } elseif (isset($aRefundItem['refund_quantity'])) {
                if ($aRefundItem['refund_quantity'] > $aBasketItem['quantity']) { // check if higher quantity than payed
                    $aRefundItem['refund_quantity'] = $aBasketItem['quantity'];
                }
                $aLine['quantity'] = $aRefundItem['refund_quantity'];
                $dAmount = $aRefundItem['refund_quantity'] * $aBasketItem['singlePrice'];
            }
            $aParams['lines'][] = $aLine;
        }
        $aParams['amount'] = [
            "currency" => $this->getOrder()->oxorder__oxcurrency->value,
            "value" => $this->formatPrice($dAmount)
        ];

        return $aParams;
    }

    /**
     * Generate request parameter array
     *
     * @param bool $blFull
     * @return array
     */
    protected function getRefundParameters($blFull = true)
    {
        if ($blFull === false) {
            $aParams = $this->getPartialRefundParameters();
        } else {
            $dAmount = $this->getOrder()->oxorder__oxtotalordersum->value;
            if (!empty(Registry::getRequest()->getRequestEscapedParameter('refundRemaining'))) {
                $dAmount = $this->getRemainingRefundableAmount();
            }

            $aParams = ["amount" => [
                "currency" => $this->getOrder()->oxorder__oxcurrency->value,
                "value" => $this->formatPrice($dAmount)
            ]];
        }

        $sDescription = Registry::getRequest()->getRequestEscapedParameter('refund_description');
        if (!empty($sDescription)) {
            $aParams['description'] = $sDescription;
        }
        return $aParams;
    }

    /**
     * Fills refunded db-fields for partially refunded products and costs
     *
     * @return void
     */
    protected function markOrderPartially()
    {
        $aRefundItems = $this->getRefundItemsFromRequest();
        $aOrderArticles = $this->getOrder()->getOrderArticles();

        $oOrder = $this->getOrder();

        foreach ($aRefundItems as $sId => $aRefundItem) {
            foreach ($aOrderArticles as $oOrderArticle) {
                if ($oOrderArticle->getId() == $sId) {
                    if (isset($aRefundItem['refund_amount'])) {
                        if ($aRefundItem['refund_amount'] > $oOrderArticle->oxorderarticles__oxbrutprice->value) {
                            $aRefundItem['refund_amount'] = $oOrderArticle->oxorderarticles__oxbrutprice->value;
                        }
                        $oOrderArticle->oxorderarticles__mollieamountrefunded = new Field((double)$oOrderArticle->oxorderarticles__mollieamountrefunded->value += $aRefundItem['refund_amount']);
                    } elseif (isset($aRefundItem['refund_quantity'])) {
                        if ($aRefundItem['refund_quantity'] > $oOrderArticle->oxorderarticles__oxamount->value) {
                            $aRefundItem['refund_quantity'] = $oOrderArticle->oxorderarticles__oxamount->value;
                        }
                        $oOrderArticle->oxorderarticles__molliequantityrefunded = new Field((int)$oOrderArticle->oxorderarticles__molliequantityrefunded->value += $aRefundItem['refund_quantity']);
                        $oOrderArticle->oxorderarticles__mollieamountrefunded = new Field((double)$oOrderArticle->oxorderarticles__mollieamountrefunded->value += $aRefundItem['refund_quantity'] * $oOrderArticle->oxorderarticles__oxbprice->value);
                    }
                    $oOrderArticle->save();
                    continue 2;
                }
            }

            if (isset($aRefundItem['refund_amount'])) {
                $aBasketItem = $this->getRefundItemById($sId);
                if ($aRefundItem['refund_amount'] > $aBasketItem['totalPrice']) { // check if higher amount than payed
                    $aRefundItem['refund_amount'] = $aBasketItem['totalPrice'];
                }

                if ($aBasketItem['type'] == 'shipping_fee') {
                    $oOrder->oxorder__molliedelcostrefunded = new Field((double)$oOrder->oxorder__molliedelcostrefunded->value + $aRefundItem['refund_amount']);
                } elseif ($aBasketItem['type'] == 'payment_fee') {
                    $oOrder->oxorder__molliepaycostrefunded = new Field((double)$oOrder->oxorder__molliepaycostrefunded->value + $aRefundItem['refund_amount']);
                } elseif ($aBasketItem['type'] == 'wrapping') {
                    $oOrder->oxorder__molliewrapcostrefunded = new Field((double)$oOrder->oxorder__molliewrapcostrefunded->value + $aRefundItem['refund_amount']);
                } elseif ($aBasketItem['type'] == 'giftcard') {
                    $oOrder->oxorder__molliegiftcardrefunded = new Field((double)$oOrder->oxorder__molliegiftcardrefunded->value + $aRefundItem['refund_amount']);
                } elseif ($aBasketItem['type'] == 'voucher') {
                    $oOrder->oxorder__mollievoucherdiscountrefunded = new Field((double)$oOrder->oxorder__mollievoucherdiscountrefunded->value + $aRefundItem['refund_amount']);
                } elseif ($aBasketItem['type'] == 'discount') {
                    $oOrder->oxorder__molliediscountrefunded = new Field((double)$oOrder->oxorder__molliediscountrefunded->value + $aRefundItem['refund_amount']);
                }
            }

        }
        $oOrder->save();

        $this->_oOrder = $oOrder; // update order for renderering the page
        $this->_aRefundItems = null;
    }

    /**
     * Fills refunded db-fields with full costs
     *
     * @return void
     */
    protected function markOrderAsFullyRefunded()
    {
        $oOrder = $this->getOrder();
        $oOrder->oxorder__molliedelcostrefunded = new Field($oOrder->oxorder__oxdelcost->value);
        $oOrder->oxorder__molliepaycostrefunded = new Field($oOrder->oxorder__oxpaycost->value);
        $oOrder->oxorder__molliewrapcostrefunded = new Field($oOrder->oxorder__oxwrapcost->value);
        $oOrder->oxorder__molliegiftcardrefunded = new Field($oOrder->oxorder__oxgiftcardcost->value);
        $oOrder->oxorder__mollievoucherdiscountrefunded = new Field($oOrder->oxorder__oxvoucherdiscount->value);
        $oOrder->oxorder__molliediscountrefunded = new Field($oOrder->oxorder__oxdiscount->value);
        $oOrder->save();

        foreach ($this->getOrder()->getOrderArticles() as $oOrderArticle) {
            $oOrderArticle->oxorderarticles__mollieamountrefunded = new Field($oOrderArticle->oxorderarticles__oxbrutprice->value);
            $oOrderArticle->save();
        }

        $this->_oOrder = $oOrder; // update order for renderering the page
        $this->_aRefundItems = null;
    }

    /**
     * Execute full refund action
     *
     * @return void
     */
    public function fullRefund()
    {
        $aParams = $this->getRefundParameters();

        $oRequestLog = oxNew(RequestLog::class);
        try {
            $oMollieApiOrder = $this->getMollieApiOrder();
            if ($oMollieApiOrder instanceof \Mollie\Api\Resources\Payment) {
                $oResponse = $oMollieApiOrder->refund($aParams);
            } else {
                unset($aParams['amount']);
                $oResponse = $oMollieApiOrder->refundAll($aParams);
            }
            $oRequestLog->logRequest($aParams, $oResponse, $this->getOrder()->getId(), $this->getConfig()->getShopId());
            $this->markOrderAsFullyRefunded();
            $this->_blSuccessfulRefund = true;
        } catch (\Exception $exc) {
            $this->setErrorMessage($exc->getMessage());
            $oRequestLog->logExceptionResponse($aParams, $exc->getCode(), $exc->getMessage(), 'refund', $this->getOrder()->getId(), $this->getConfig()->getShopId());
            $this->_blSuccessfulRefund = false;
        }
    }

    /**
     * Execute partial refund action
     *
     * @return void
     */
    public function partialRefund()
    {
        $aParams = $this->getRefundParameters(false);
        if (empty($aParams['lines'])) {
            $this->setErrorMessage('Lines array is empty - something went wrong!');
            return;
        }
        $oRequestLog = oxNew(RequestLog::class);
        try {
            $oMollieApiOrder = $this->getMollieApiOrder();
            if ($oMollieApiOrder instanceof \Mollie\Api\Resources\Order) {
                unset($aParams['amount']);
            } elseif ($oMollieApiOrder instanceof \Mollie\Api\Resources\Payment) {
                unset($aParams['lines']);
            }

            $oResponse = $oMollieApiOrder->refund($aParams);
            $oRequestLog->logRequest($aParams, $oResponse, $this->getOrder()->getId(), $this->getConfig()->getShopId());
            $this->markOrderPartially();
            $this->_blSuccessfulRefund = true;
        } catch (\Exception $exc) {
            $this->setErrorMessage($exc->getMessage());
            $oRequestLog->logExceptionResponse($aParams, $exc->getCode(), $exc->getMessage(), 'refund', $this->getOrder()->getId(), $this->getConfig()->getShopId());
            $this->_blSuccessfulRefund = false;
        }
    }

    /**
     * Return Mollie api order
     *
     * @return \Mollie\Api\Resources\Order|\Mollie\Api\Resources\Payment
     */
    protected function getMollieApiOrder()
    {
        if ($this->_oMollieApiOrder === null) {
            $this->_oMollieApiOrder = $this->getMollieApiRequestModel()->get($this->getOrder()->oxorder__oxtransid->value);
        }
        return $this->_oMollieApiOrder;
    }

    /**
     * Check Mollie API if order is refundable
     *
     * @return bool
     */
    public function isOrderRefundable()
    {
        if ($this->wasRefundSuccessful() === true && Registry::getRequest()->getRequestEscapedParameter('fnc') == 'fullRefund') {
            // the mollie order is not updated instantly, so this is used to show that the order was fully refunded already
            return false;
        }

        $oApiOrder = $this->getMollieApiOrder();

        if (empty($oApiOrder->amountRefunded) || $oApiOrder->amountRefunded->value != $oApiOrder->amount->value) {
            return true;
        }
        return false;
    }

    /**
     * Checks if order was payed with Mollie
     *
     * @return bool
     */
    public function isMollieOrder()
    {
        return PaymentHelper::getInstance()->isMolliePaymentMethod($this->getOrder()->oxorder__oxpaymenttype->value);
    }

    /**
     * Checks if there were previous partial refunds and therefore full refund is not available anymore
     *
     * @return bool
     */
    public function isFullRefundAvailable()
    {
        $oOrder = $this->getOrder();
        foreach ($oOrder->getOrderArticles() as $orderArticle) {
            if ((double)$orderArticle->oxorderarticles__mollieamountrefunded->value > 0 || $orderArticle->oxorderarticles__molliequantityrefunded->value > 0) {
                return false;
            }
        }

        if ($oOrder->oxorder__molliedelcostrefunded->value > 0
            || $oOrder->oxorder__molliepaycostrefunded->value > 0
            || $oOrder->oxorder__molliewrapcostrefunded->value > 0
            || $oOrder->oxorder__molliegiftcardrefunded->value > 0
            || $oOrder->oxorder__mollievoucherdiscountrefunded->value > 0
            || $oOrder->oxorder__molliediscountrefunded->value > 0) {
            return false;
        }
        return true;
    }

    /**
     * Returns user payment used for current order. In case current order was executed using
     * credit card and user payment info is not stored in db (if \OxidEsales\Eshop\Core\Config::blStoreCreditCardInfo = false),
     * just for preview user payment is set from oxPayment
     *
     * @param object $oOrder Order object
     *
     * @return oxUserPayment
     */
    protected function _getPaymentType($oOrder)
    {
        if (!($oUserPayment = $oOrder->getPaymentType()) && $oOrder->oxorder__oxpaymenttype->value) {
            $oPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
            if ($oPayment->load($oOrder->oxorder__oxpaymenttype->value)) {
                // in case due to security reasons payment info was not kept in db
                $oUserPayment = oxNew(\OxidEsales\Eshop\Application\Model\UserPayment::class);
                $oUserPayment->oxpayments__oxdesc = new \OxidEsales\Eshop\Core\Field($oPayment->oxpayments__oxdesc->value);
            }
        }

        return $oUserPayment;
    }

    /**
     * Get refunded amount formated
     *
     * @return string
     */
    public function getFormatedPrice($dPrice)
    {
        $oLang = \OxidEsales\Eshop\Core\Registry::getLang();
        $oOrder = $this->getOrder();
        $oCurrency = $this->getConfig()->getCurrencyObject($oOrder->oxorder__oxcurrency->value);

        return $oLang->formatCurrency($dPrice, $oCurrency);
    }

    /**
     * Translate item type from basket item array
     *
     * @param  array $aBasketItem
     * @return string
     */
    protected function getTypeFromBasketItem($aBasketItem)
    {
        if (in_array($aBasketItem['type'], array('shipping_fee', 'discount'))) {
            return $aBasketItem['type'];
        }

        if (in_array($aBasketItem['sku'], array('wrapping', 'giftcard', 'voucher'))) {
            return $aBasketItem['sku'];
        }

        return 'payment_fee';
    }

    /**
     * Returns previously refunded amount by type
     *
     * @param string $sType
     * @return double
     */
    protected function getAmountRefundedByType($sType)
    {
        $oOrder = $this->getOrder();
        switch ($sType) {
            case 'shipping_fee':
                return $oOrder->oxorder__molliedelcostrefunded->value;
            case 'payment_fee':
                return $oOrder->oxorder__molliepaycostrefunded->value;
            case 'wrapping':
                return $oOrder->oxorder__molliewrapcostrefunded->value;
            case 'giftcard':
                return $oOrder->oxorder__molliegiftcardrefunded->value;
            case 'voucher':
                return $oOrder->oxorder__mollievoucherdiscountrefunded->value;
            case 'discount':
                return $oOrder->oxorder__molliediscountrefunded->value;
        }
        return 0;
    }

    /**
     * Returns still refundable amount by type
     *
     * @param string $sType
     * @return int
     */
    protected function getRefundableAmountByType($sType)
    {
        $oOrder = $this->getOrder();
        switch ($sType) {
            case 'shipping_fee':
                return $oOrder->oxorder__oxdelcost->value - $oOrder->oxorder__molliedelcostrefunded->value;
            case 'payment_fee':
                return $oOrder->oxorder__oxpaycost->value - $oOrder->oxorder__molliepaycostrefunded->value;
            case 'wrapping':
                return $oOrder->oxorder__oxwrapcost->value - $oOrder->oxorder__molliewrapcostrefunded->value;
            case 'giftcard':
                return $oOrder->oxorder__oxgiftcardcost->value - $oOrder->oxorder__molliegiftcardrefunded->value;
            case 'voucher':
                return $oOrder->oxorder__oxvoucherdiscount->value - $oOrder->oxorder__mollievoucherdiscountrefunded->value;
            case 'discount':
                return $oOrder->oxorder__oxdiscount->value - $oOrder->oxorder__molliediscountrefunded->value;
        }
        return 0;
    }

    /**
     * Returns Mollie payment or order Api
     *
     * @return \Mollie\Api\Endpoints\EndpointAbstract
     */
    protected function getMollieApiRequestModel()
    {
        return $this->getOrder()->mollieGetPaymentModel()->getApiEndpoint();
    }

    /**
     * Generate item array for shipping, payment, etc
     *
     * @return array
     */
    protected function getOtherItemsFromOrder()
    {
        $aItems = array();

        $oRequestModel = $this->getOrder()->mollieGetPaymentModel()->getApiRequestModel();
        $aBasketItems = $oRequestModel->getBasketItems($this->getOrder());
        foreach ($aBasketItems as $aBasketItem) {
            if (in_array($aBasketItem['type'], array('physical', 'digital'))) {
                continue; // skip order articles
            }
            $sType = $this->getTypeFromBasketItem($aBasketItem);
            if (in_array($sType, array('voucher', 'discount'))) {
                $aBasketItem['totalAmount']['value'] = $this->formatPrice($aBasketItem['totalAmount']['value'] * -1);
                $aBasketItem['unitPrice']['value'] = $aBasketItem['totalAmount']['value'];
            }
            $aItems[] = array(
                'id' => $aBasketItem['sku'],
                'type' => $sType,
                'artnum' => $aBasketItem['sku'],
                'title' => $aBasketItem['name'],
                'singlePrice' => $aBasketItem['unitPrice']['value'],
                'totalPrice' => $aBasketItem['totalAmount']['value'],
                'vat' => $aBasketItem['vatRate'],
                'amountRefunded' => $this->getAmountRefundedByType($sType),
                'refundableAmount' => $this->formatPrice($this->getRefundableAmountByType($sType)),
                'isOrderarticle' => false,
                'isPartialAllowed' => in_array($sType, ['voucher', 'discount']) ? false : true
            );
        }
        return $aItems;
    }

    /**
     * Check if quantity controls can be shown
     * Can only be shown as long as no partial refunds with a free money amount was used
     *
     * @return bool
     */
    public function isQuantityAvailable()
    {
        foreach ($this->getOrder()->getOrderArticles() as $orderArticle) {
            if ((double)$orderArticle->oxorderarticles__mollieamountrefunded->value > 0 && fmod($orderArticle->oxorderarticles__mollieamountrefunded->value, $orderArticle->oxorderarticles__oxbprice->value) != 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * Map order article values to item array
     *
     * @return array
     */
    protected function getItemsFromOrderArticles()
    {
        $aItems = array();

        foreach ($this->getOrder()->getOrderArticles() as $orderArticle) {
            $quantityRefunded = $orderArticle->oxorderarticles__molliequantityrefunded->value;
            if ($orderArticle->oxorderarticles__mollieamountrefunded->value == $orderArticle->oxorderarticles__oxbrutprice->value) {
                $quantityRefunded = $orderArticle->oxorderarticles__oxamount->value;
            }

            $aItems[] = array(
                'id' => $orderArticle->getId(),
                'type' => 'product',
                'refundableQuantity' => $orderArticle->mollieGetRefundableQuantity(),
                'refundableAmount' => $this->formatPrice($orderArticle->mollieGetRefundableAmount()),
                'artnum' => $orderArticle->oxorderarticles__oxartnum->value,
                'title' => $orderArticle->oxorderarticles__oxtitle->value,
                'singlePrice' => $orderArticle->oxorderarticles__oxbprice->value,
                'totalPrice' => $orderArticle->oxorderarticles__oxbrutprice->value,
                'vat' => $orderArticle->oxorderarticles__oxvat->value,
                'quantity' => $orderArticle->oxorderarticles__oxamount->value,
                'quantityRefunded' => $quantityRefunded,
                'amountRefunded' => $orderArticle->oxorderarticles__mollieamountrefunded->value,
                'isOrderarticle' => true,
                'isPartialAllowed' => true
            );
        }
        return $aItems;
    }

    /**
     * Returns if the order includes vouchers or discounts
     *
     * @return bool
     */
    public function hasOrderVoucher()
    {
        $aRefundItems = $this->getRefundItems();
        foreach ($aRefundItems as $aRefundItem) {
            if (in_array($aRefundItem['type'], $this->_aVoucherTypes)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Collect all refund items
     *
     * @return array
     */
    public function getRefundItems()
    {
        if ($this->_aRefundItems === null) {
            $this->_aRefundItems = array_merge($this->getItemsFromOrderArticles(), $this->getOtherItemsFromOrder());
        }
        return $this->_aRefundItems;
    }
}
