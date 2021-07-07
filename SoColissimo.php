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

use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SoColissimo\Model\SocolissimoAreaFreeshippingDomQuery;
use SoColissimo\Model\SocolissimoAreaFreeshippingPrQuery;
use SoColissimo\Model\SocolissimoDeliveryMode;
use SoColissimo\Model\SocolissimoDeliveryModeQuery;
use SoColissimo\Model\SocolissimoPrice;
use SoColissimo\Model\SocolissimoPriceQuery;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Finder\Finder;
use Thelia\Model\AreaQuery;
use Thelia\Model\ConfigQuery;
use Thelia\Model\Country;
use Thelia\Model\ModuleImageQuery;
use Thelia\Model\ModuleQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Module\AbstractDeliveryModule;
use Thelia\Module\Exception\DeliveryException;
use Thelia\Tools\Version\Version;

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
        if (null === $deliverModeCode) {
            $deliveryMode = SocolissimoDeliveryModeQuery::create()->find()->getFirst();
        } else {
            $deliveryMode = SocolissimoDeliveryModeQuery::create()->findOneByCode($deliverModeCode);
        }

        $freeshipping = $deliveryMode->getFreeshippingActive();
        $freeshippingFrom = $deliveryMode->getFreeshippingFrom();
        $deliveryModeQuery = $deliveryMode->getCode();


        $postage = 0;

        if (!$freeshipping) {
            $areaPrices = SocolissimoPriceQuery::create()
                ->filterByDeliveryModeId($deliveryMode->getId())
                ->filterByAreaId($areaId)
                ->filterByWeightMax($weight, Criteria::GREATER_EQUAL)
                ->_or()
                ->filterByWeightMax(null)
                ->filterByPriceMax($cartAmount, Criteria::GREATER_EQUAL)
                ->_or()
                ->filterByPriceMax(null)
                ->orderByWeightMax()
                ->orderByPriceMax();

            $firstPrice = $areaPrices->find()
                ->getFirst();

            if (null === $firstPrice) {
                throw new DeliveryException("Colissimo delivery unavailable for your cart weight or delivery country");
            }

            //If a min price for freeshipping is define and the amount of cart reach this montant return 0
            if (null !== $freeshippingFrom && $freeshippingFrom <= $cartAmount) {
                $postage = 0;
                return $postage;
            }

            if ($deliveryModeQuery === 'dom') {
                $cartAmountDom = SocolissimoAreaFreeshippingDomQuery::create()
                    ->filterByAreaId($areaId)
                    ->findOne();
                if ($cartAmountDom) {
                    $cartAmountDom = $cartAmountDom->getCartAmount();
                }
                if (null !== $cartAmountDom && $cartAmountDom <= $cartAmount) {
                    $postage = 0;
                    return $postage;
                }
            } elseif ($deliveryModeQuery === 'pr') {
                $cartAmountPr = SocolissimoAreaFreeshippingPrQuery::create()
                    ->filterByAreaId($areaId)
                    ->findOne();
                if ($cartAmountPr) {
                    $cartAmountPr = $cartAmountPr->getCartAmount();
                }
                if (null !== $cartAmountPr && $cartAmountPr <= $cartAmount) {
                    $postage = 0;
                    return $postage;
                }
            }
            $postage = $firstPrice->getPrice();
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
        $pr_code = $request->get('socolissimo_code');

        $deliveryModeCode = null;
        if ($dom) {
            $deliveryModeCode = "dom";
        } elseif (!empty($pr_code)) {
            $deliveryModeCode = "pr";
        }

        if (null == $deliveryModeCode) {
            $session = $request->getSession();
            $dom = $session->get('SoColissimoDomicile');
            $pr_code = $session->get('SoColissimoDeliveryId');

            if ($dom) {
                $deliveryModeCode = "dom";
            } elseif (!empty($pr_code)) {
                $deliveryModeCode = "pr";
            }
        }

        $areaIdArray = $this->getAllAreasForCountry($country);
        if (empty($areaIdArray)) {
            throw new DeliveryException("Your delivery country is not covered by Colissimo.");
        }
        $postage = null;

        if (null === $postage = $this->getMinPostage($areaIdArray, $cartWeight, $cartAmount, $deliveryModeCode)) {
            $postage = $this->getMinPostage($areaIdArray, $cartWeight, $cartAmount, 'dom');
            if (null === $postage) {
                throw new DeliveryException("Colissimo delivery unavailable for your cart weight or delivery country");
            }
        }
        return $postage;
    }


    private function getMinPostage($areaIdArray, $cartWeight, $cartAmount, $deliveryModeCode)
    {
        $minPostage = null;

        foreach ($areaIdArray as $areaId) {
            try {
                $postage = self::getPostageAmount($areaId, $cartWeight, $cartAmount, $deliveryModeCode);
                if ($minPostage === null || $postage < $minPostage) {
                    $minPostage = $postage;
                    if ($minPostage == 0) {
                        break;
                    }
                }
            } catch (\Exception $ex) {
            }
        }

        return $minPostage;
    }

    /**
     * Returns ids of area containing this country and covers by this module
     * @param Country $country
     * @return array Area ids
     */
    private function getAllAreasForCountry(Country $country)
    {
        $areaArray = [];

        $sql = "SELECT ca.area_id as area_id FROM country_area ca
               INNER JOIN area_delivery_module adm ON (ca.area_id = adm.area_id AND adm.delivery_module_id = :p0)
               WHERE ca.country_id = :p1";

        $con = Propel::getConnection();

        $stmt = $con->prepare($sql);
        $stmt->bindValue(':p0', $this->getModuleModel()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':p1', $country->getId(), PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $areaArray[] = $row['area_id'];
        }

        return $areaArray;
    }

    /** Return the module code */
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

    protected function checkModuleConfig() {
        /** If this isn't set, this means it's the first time we start the module or it's updating from < 2.0.0 */
        if (null === self::getConfigValue('socolissimo-rework-2')) {
            /**
             * We check for every ConfigQuery the old version of the module set.
             * We delete them if they exist, and we set a module config instead
             */

            /** Colissimo Username / Account number */
            self::setConfigValue('socolissimo_username', '');
            if (null !== $value = ConfigQuery::read('socolissimo_login')) {
                self::setConfigValue('socolissimo_username', $value);
                ConfigQuery::create()->findOneByValue($value)->delete();
            }

            /** Colissimo password */
            self::setConfigValue('socolissimo_password', '');
            if (null !== $value = ConfigQuery::read('socolissimo_pwd')) {
                self::setConfigValue('socolissimo_password', $value);
                ConfigQuery::create()->findOneByValue($value)->delete();
            }

            /** Colissimo Google Map key */
            self::setConfigValue('socolissimo_google_map_key', '');
            if (null !== $value = ConfigQuery::read('socolissimo_google_map_key')) {
                self::setConfigValue('socolissimo_google_map_key', $value);
                ConfigQuery::create()->findOneByValue($value)->delete();
            }

            /** Colissimo Endpoint url for relay point (point relais) */
            self::setConfigValue('socolissimo_endpoint_url', '');
            if (null !== $value = ConfigQuery::read('socolissimo_url_prod')) {
                self::setConfigValue('socolissimo_endpoint_url', $value);
                ConfigQuery::create()->findOneByValue($value)->delete();
            }

            /** Delete useless config value */
            if (null !== $value = ConfigQuery::read('socolissimo_test_mode')) {
                ConfigQuery::create()->findOneByValue($value)->delete();
            }

            /** Delete useless config value */
            if (null !== $value = ConfigQuery::read('socolissimo_url_test')) {
                ConfigQuery::create()->findOneByValue($value)->delete();
            }

            self::setConfigValue('socolissimo-rework-2', 1);
        }

        /** Shows dom delivery in the config */
        if (!self::getConfigValue('socolissimo_dom_delivery_authorized')) {
            self::setConfigValue('socolissimo_dom_delivery_authorized', '0');
        }
    }


    public function postActivation(ConnectionInterface $con = null)
    {
        try {
            // Security to not erase user config on reactivation
            SocolissimoDeliveryModeQuery::create()->findOne();
            SocolissimoAreaFreeshippingDomQuery::create()->findOne();
            SocolissimoAreaFreeshippingPrQuery::create()->findOne();
        } catch (\Exception $e) {
            $database = new Database($con->getWrappedConnection());
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

        $this->checkModuleConfig();

        /** Insert the images from image folder if first module activation */
        $module = $this->getModuleModel();
        if (ModuleImageQuery::create()->filterByModule($module)->count() == 0) {
            $this->deployImageFolder($module, sprintf('%s/images', __DIR__), $con);
        }
    }

    /** Return the module ID */
    public static function getModCode()
    {
        return ModuleQuery::create()->findOneByCode("SoColissimo")->getId();
    }

    /**
     * @inheritDoc
     */
    public function update($currentVersion, $newVersion, ConnectionInterface $con = null)
    {
        $this->checkModuleConfig();

        $finder = (new Finder)
            ->files()
            ->name('#.*?\.sql#')
            ->sortByName()
            ->in(__DIR__ . DS . 'Config' . DS . 'update' . DS . 'sql');

        $database = new Database($con);

        /** @var \Symfony\Component\Finder\SplFileInfo $updateSQLFile */
        foreach ($finder as $updateSQLFile) {
            if (version_compare($currentVersion, str_replace('.sql', '', $updateSQLFile->getFilename()), '<')) {
                $database->insertSql(
                    null,
                    [
                        $updateSQLFile->getPathname()
                    ]
                );
            }
        }
    }
}
