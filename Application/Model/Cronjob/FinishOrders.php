<?php

namespace Mollie\Payment\Application\Model\Cronjob;

use Mollie\Payment\Application\Helper\Payment;
use OxidEsales\Eshop\Application\Model\Order;
use Mollie\Payment\Application\Model\Payment\Base;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;

class FinishOrders extends \Mollie\Payment\Application\Model\Cronjob\Base
{
    /**
     * Id of current cronjob
     *
     * @var string
     */
    protected $sCronjobId = 'mollie_finish_orders';

    /**
     * Default cronjob interval in minutes
     *
     * @var int
     */
    protected $iDefaultMinuteInterval = 10;

    /**
     * Collects all expired order ids
     *
     * @return array
     */
    protected function getPaidUnfinishedOrders()
    {
        $aOrders = [];

        $sProcessingFolder = Registry::getConfig()->getShopConfVar('sMollieStatusProcessing');
        $sTriggerDate = date('Y-m-d H:i:s', time() - (60 * 60 * 24));
        $sMinPaidDate = date('Y-m-d H:i:s', time() - (60 * 2)); // This will prevent finishing legit orders before the customer does
        $sQuery = " SELECT 
                        OXID 
                    FROM 
                        oxorder 
                    WHERE 
                        oxstorno = 0 AND 
                        oxpaymenttype LIKE '%mollie%' AND 
                        oxorderdate > ? AND 
                        oxtransstatus = 'NOT_FINISHED' AND 
                        oxfolder = ? AND 
                        oxpaid != '0000-00-00 00:00:00' AND
                        oxpaid < ?;";
        $aResult = DatabaseProvider::getDb()->getAll($sQuery, array($sTriggerDate, $sProcessingFolder, $sMinPaidDate));
        foreach ($aResult as $aRow) {
            $aOrders[] = $aRow[0];
        }

        return $aOrders;
    }

    /**
     * Collects exired order ids and cancels these orders
     *
     * @return bool
     */
    protected function handleCronjob()
    {
        $aUnfinishedOrders = $this->getPaidUnfinishedOrders();
        foreach ($aUnfinishedOrders as $sUnfinishedOrderId) {
            $oOrder = oxNew(Order::class);
            if ($oOrder->load($sUnfinishedOrderId) && $oOrder->mollieIsOrderInUnfinishedState()) {
                $oOrder->mollieFinishOrder();
                $this->outputInfo("Finished Order with ID ".$oOrder->getId());
            }
        }
        return true;
    }
}
