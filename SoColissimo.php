<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace SoColissimo;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use SoColissimo\Model\SocolissimoDeliveryMode;
use SoColissimo\Model\SocolissimoDeliveryModeQuery;
use SoColissimo\Model\SocolissimoPrice;
use SoColissimo\Model\SocolissimoPriceQuery;
use Symfony\Component\Config\Definition\Exception\Exception;
use Thelia\Model\AreaQuery;
use Thelia\Model\ConfigQuery;
use Thelia\Model\Country;
use Thelia\Model\ModuleImageQuery;
use Thelia\Model\ModuleQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Module\AbstractDeliveryModule;
use Thelia\Module\Exception\DeliveryException;

class SoColissimo extends AbstractDeliveryModule
{
    protected $request;
    protected $dispatcher;

    private static $prices = null;

    const DOMAIN = 'socolissimo';
    const JSON_PRICE_RESOURCE = "/Config/prices.json";
    const JSON_CONFIG_PATH = "/Config/config.json";

    /**
     * These constants refer to the imported CSV file.
     * IMPORT_NB_COLS: file's number of columns (begin at 1)
     * IMPORT_DELIVERY_REF_COL: file's column where delivery reference is set (begin at 0)
     * IMPORT_ORDER_REF_COL: file's column where order reference is set (begin at 0)
     */
    const IMPORT_NB_COLS = 2;
    const IMPORT_DELIVERY_REF_COL = 0;
    const IMPORT_ORDER_REF_COL = 1;

    /**
     * This method is called by the Delivery  loop, to check if the current module has to be displayed to the customer.
     * Override it to implements your delivery rules/
     *
     * If you return true, the delivery method will de displayed to the customer
     * If you return false, the delivery method will not be displayed
     *
     * @param Country $country the country to deliver to.
     *
     * @return boolean
     */
    public function isValidDelivery(Country $country)
    {
        $cartWeight = $this->getRequest()->getSession()->getSessionCart($this->getDispatcher())->getWeight();

        $areaId = $country->getAreaId();

        $prices = SocolissimoPriceQuery::create()
            ->filterByAreaId($areaId)
            ->filterByWeightMax($cartWeight, Criteria::GREATER_EQUAL)
        ->findOne();

        $freeShipping = SocolissimoDeliveryModeQuery::create()
            ->findOneByFreeshippingActive(1);

        /* check if Colissimo delivers the asked area*/
        if (null !== $prices || null !== $freeShipping) {
            return true;
        }

        return false;
    }

    /**
     * @param $areaId
     * @param $weight
     * @param $cartAmount
     * @param $deliverModeCode
     *
     * @return mixed
     * @throws DeliveryException
     */
    public static function getPostageAmount($areaId, $weight, $cartAmount = 0, $deliverModeCode = null)
    {
        if ($deliverModeCode === null) {
            $deliveryMode = SocolissimoDeliveryModeQuery::create()->find()->getFirst();
        } else {
            $deliveryMode = SocolissimoDeliveryModeQuery::create()->findOneByCode($deliverModeCode);
        }

        $freeshipping = $deliveryMode->getFreeshippingActive();
        $freeshippingFrom = $deliveryMode->getFreeshippingFrom();

        $postage=0;

        if (!$freeshipping) {
            $areaPrices = SocolissimoPriceQuery::create()
                ->filterByDeliveryModeId($deliveryMode->getId())
                ->filterByAreaId($areaId)
                ->orderByWeightMax();

            $lastPrice = $areaPrices->find()
                ->getLast();

            /* check if SoColissimo delivers the asked area */
            if (null === $lastPrice) {
                throw new DeliveryException("SoColissimo delivery unavailable for the chosen delivery country");
            }

            /* check this weight is not too much */
            $maxWeight = $lastPrice->getWeightMax();
            if ($weight > $maxWeight) {
                throw new DeliveryException(sprintf("SoColissimo delivery unavailable for this cart weight (%s kg)", $weight));
            }

            //If a min price for freeshipping is define and the amount of cart reach this montant return 0
            if (null !== $freeshippingFrom && $freeshippingFrom <= $cartAmount) {
                return $postage;
            }

            //Get the closest price from top
            $priceForWeight = $areaPrices->filterByWeightMax($weight, Criteria::GREATER_EQUAL)
                ->find()
                ->getFirst();

            $postage = $priceForWeight->getPrice();
        }

        return $postage;

    }

    /**
     *
     * calculate and return delivery price
     *
     * @param  Country                          $country
     * @return mixed
     * @throws DeliveryException
     */
    public function getPostage(Country $country)
    {
        $request = $this->getRequest();

        $cartWeight = $request->getSession()->getSessionCart($this->getDispatcher())->getWeight();
        $cartAmount = $request->getSession()->getSessionCart($this->getDispatcher())->getTaxedAmount($country);

        $dom = $request->get('socolissimo-home');
        $rdv = $request->get('socolissimo-appointment');
        $pr_code = $request->get('socolissimo_code');

        $deliveryModeCode = null;
        if ($dom || $rdv) {
            $deliveryModeCode = "dom";
        } elseif (!empty($pr_code)) {
            $deliveryModeCode = "pr";
        }

        $postage = self::getPostageAmount(
            $country->getAreaId(),
            $cartWeight,
            $cartAmount,
            $deliveryModeCode
        );

        return $postage;
    }

    public function getCode()
    {
        return 'SoColissimo';
    }

    public static function getPrices(SocolissimoDeliveryMode $deliveryMode)
    {
        self::$prices = null;

        $fileName = sprintf('%s%s', __DIR__, "/Config/prices_".$deliveryMode->getCode().".json");

        // If delivery mode file doesn't exist take global price
        if (!file_exists($fileName)) {
            $fileName = sprintf('%s%s', __DIR__, self::JSON_PRICE_RESOURCE);
            // If global price doesn't exist throw exception
            if (!file_exists($fileName)) {
                throw new Exception("Prices configuration not found.");
            }
        }

        if (null === self::$prices) {
            self::$prices = json_decode(file_get_contents($fileName), true);
        }
        return self::$prices;
    }

    public static function importJsonPrice(SocolissimoDeliveryMode $deliveryMode, ConnectionInterface $con)
    {
        $areaPrices = self::getPrices($deliveryMode);

        $priceExist = SocolissimoPriceQuery::create()
            ->filterByDeliveryModeId($deliveryMode->getId())
            ->findOne();

        //If at least one price exist doesn't import the xml (or it will erase the user price)
        if (null !== $priceExist) {
            return;
        }

        $con->beginTransaction();
        try {
            foreach ($areaPrices as $areaId => $area) {
                // Check if the area exists
                if (null !== AreaQuery::create()->findPk($areaId)) {
                    foreach ($area['slices'] as $weight => $price) {
                        $slice = (new SocolissimoPrice())
                            ->setAreaId($areaId)
                            ->setWeightMax($weight)
                            ->setPrice($price)
                            ->setDeliveryModeId($deliveryMode->getId());
                        $slice->save();
                    }
                    $con->commit();
                }
            }
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    public function postActivation(ConnectionInterface $con = null)
    {
        $database = new Database($con);

        try {
            // Security to not erase user config on reactivation
            SocolissimoDeliveryModeQuery::create()->findOne();
        } catch (\Exception $e) {
            $database->insertSql(null, [__DIR__ . '/Config/thelia.sql', __DIR__ . '/Config/insert.sql']);
        }

        try {
            $deliveryModes = SocolissimoDeliveryModeQuery::create()
                ->find();

            foreach ($deliveryModes as $deliveryMode) {
                self::importJsonPrice($deliveryMode, $con);
            }
        } catch (\Exception $e) {
            throw $e;
        }

        ConfigQuery::write(
            'socolissimo_login',
            ConfigQuery::read('socolissimo_login', null),
            1,
            1
        );

        ConfigQuery::write(
            'socolissimo_pwd',
            ConfigQuery::read('socolissimo_pwd', null),
            1,
            1
        );

        ConfigQuery::write(
            'socolissimo_test_mode',
            ConfigQuery::read('socolissimo_test_mode', 1),
            1,
            1
        );

        ConfigQuery::write(
            'socolissimo_url_prod',
            ConfigQuery::read('socolissimo_url_prod', 'https://ws.colissimo.fr/pointretrait-ws-cxf/PointRetraitServiceWS/2.0?wsdl'),
            1,
            1
        );

        ConfigQuery::write(
            'socolissimo_url_test',
            ConfigQuery::read('socolissimo_url_test', 'https://pfi.telintrans.fr/pointretrait-ws-cxf/PointRetraitServiceWS/2.0?wsdl'),
            1,
            1
        );

        /* insert the images from image folder if first module activation */
        $module = $this->getModuleModel();
        if (ModuleImageQuery::create()->filterByModule($module)->count() == 0) {
            $this->deployImageFolder($module, sprintf('%s/images', __DIR__), $con);
        }
    }

    public static function getModCode()
    {
        return ModuleQuery::create()->findOneByCode("SoColissimo")->getId();
    }
}
