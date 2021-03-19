<?php


namespace Mollie\Payment\Tests\Unit\Application\Model\Cronjob;

use Mollie\Payment\Application\Model\Cronjob\OrderShipment;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsObject;
use OxidEsales\TestingLibrary\UnitTestCase;

class OrderShipmentTest extends UnitTestCase
{
    public function tearDown()
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('DELETE FROM oxorder WHERE oxid = "markShipmentTest"');

        parent::tearDown();
    }

    public function testStartCronjob()
    {
        $oOrder = $this->getMockBuilder(Order::class)->disableOriginalConstructor()->getMock();
        $oOrder->method('load')->willReturn(true);
        $oOrder->method('mollieIsMolliePaymentUsed')->willReturn(true);

        UtilsObject::setClassInstance(Order::class, $oOrder);

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("INSERT INTO oxorder (oxid, oxpaymenttype, oxsenddate, mollieshipmenthasbeenmarked) VALUE ('markShipmentTest', 'molliecreditcard', '2021-02-02 00:00:01', 0)");

        $oCronjob = oxNew(OrderShipment::class);
        $result = $oCronjob->startCronjob();

        $this->assertTrue($result);
    }
}