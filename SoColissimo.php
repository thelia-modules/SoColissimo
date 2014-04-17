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

use Propel\Runtime\Connection\ConnectionInterface;
use SoColissimo\Model\SocolissimoFreeshippingQuery;
use Thelia\Install\Database;
use Thelia\Model\Country;
use Thelia\Model\ModuleQuery;
use Thelia\Module\AbstractDeliveryModule;
use Thelia\Module\Exception\DeliveryException;

class SoColissimo extends AbstractDeliveryModule
{
    protected $request;
    protected $dispatcher;

    private static $prices = null;

    const JSON_PRICE_RESOURCE = "/Config/prices.json";
    const JSON_CONFIG_PATH = "/Config/config.json";

    public static function getPrices()
    {
        if (null === self::$prices) {
            self::$prices = json_decode(file_get_contents(sprintf('%s%s', __DIR__, self::JSON_PRICE_RESOURCE)), true);
        }

        return self::$prices;
    }

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
        $cartWeight = $this->getRequest()->getSession()->getCart()->getWeight();

        $areaId = $country->getAreaId();

        $prices = self::getPrices();

        /* check if Colissimo delivers the asked area */
        if (isset($prices[$areaId]) && isset($prices[$areaId]["slices"])) {

            $areaPrices = $prices[$areaId]["slices"];
            ksort($areaPrices);

            /* check this weight is not too much */
            end($areaPrices);

            $maxWeight = key($areaPrices);
            if ($cartWeight <= $maxWeight) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $areaId
     * @param $weight
     *
     * @return mixed
     * @throws DeliveryException
     */
    public static function getPostageAmount($areaId, $weight)
    {
        $freeshipping = SocolissimoFreeshippingQuery::create()->getLast();
        $postage=0;
        if (!$freeshipping) {
            $prices = self::getPrices();

            /* check if Colissimo delivers the asked area */
            if (!isset($prices[$areaId]) || !isset($prices[$areaId]["slices"])) {
                throw new DeliveryException("SoColissimo delivery unavailable for the chosen delivery country");
            }

            $areaPrices = $prices[$areaId]["slices"];
            ksort($areaPrices);

            /* check this weight is not too much */
            end($areaPrices);
            $maxWeight = key($areaPrices);
            if ($weight > $maxWeight) {
                throw new DeliveryException(sprintf("SoColissimo delivery unavailable for this cart weight (%s kg)", $weight));
            }

            $postage = current($areaPrices);

            while (prev($areaPrices)) {
                if ($weight > key($areaPrices)) {
                    break;
                }

                $postage = current($areaPrices);
            }
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
        $cartWeight = $this->getRequest()->getSession()->getCart()->getWeight();

        $postage = self::getPostageAmount(
            $country->getAreaId(),
            $cartWeight
        );

        return $postage;
    }

    public function getCode()
    {
        return 'SoColissimo';
    }

    public function postActivation(ConnectionInterface $con = null)
    {
        $database = new Database($con);

        $database->insertSql(null, array(__DIR__ . '/Config/thelia.sql'));
        if (!file_exists(__DIR__.self::JSON_CONFIG_PATH)) {
            if (!is_writable(__DIR__."/Config")) {
                throw new \Exception("The directory config is not writable");
            }

            if (!file_put_contents(__DIR__.self::JSON_CONFIG_PATH, "{}")) {
                throw new \Exception("The file ".self::JSON_CONFIG_PATH." doesn't exists, please create it.");
            }
        }
    }

    public static function getModCode()
    {
        return ModuleQuery::create()->findOneByCode("SoColissimo")->getId();
    }

}
