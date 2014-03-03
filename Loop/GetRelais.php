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

namespace SoColissimo\Loop;
use SoColissimo\Model\Config;
use SoColissimo\SoColissimo;
use SoColissimo\WebService\FindByAddress;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\AddressQuery;

/**
 * Class GetRelais
 * @package SoColissimo\Loop
 * @author Thelia <info@thelia.net>
 */
class GetRelais extends BaseLoop implements ArraySearchLoopInterface
{
    /**
     * this method returns an array ***Thanks cap'tain obvious \(^.^)/***
     *
     * @return array
     */
    public function buildArray()
    {
        // Find the address ... To find ! \m/
        $zipcode = $this->getZipcode();
        $city = $this->getCity();

        $address = array(
            "zipcode"=>$zipcode,
            "city"=>$city,
            "address"=>"",
            "countrycode"=>"FR",
        );

        if (empty($zipcode) || empty($city)) {
            $search = AddressQuery::create();

            $customer=$this->securityContext->getCustomerUser();
            if ($customer !== null) {
                $search->filterByCustomerId($customer->getId());
                $search->filterByIsDefault("1");
            } else {
                throw new \ErrorException("Customer not connected.");
            }

            $search = $search->findOne();
            $address["zipcode"] = $search->getZipcode();
            $address["city"] = $search->getCity();
            $address["address"] = $search->getAddress1();
            $address["countrycode"] = $search->getCountry()->getIsoalpha2();
        }

        // Then ask the Web Service
        $config = Config::read(SoColissimo::JSON_CONFIG_PATH);
        $request = new FindByAddress();
        $request
            ->setAddress($address["address"])
            ->setZipCode($address["zipcode"])
            ->setCity($address["city"])
            ->setCountryCode($address["countrycode"])
            ->setFilterRelay("1")
            ->setRequestId(md5(microtime()))
            ->setLang("FR")
            ->setOptionInter("1")
            ->setShippingDate(date("d/m/Y"))
            ->setAccountNumber(isset($config['account_number']) ? $config['account_number']:"")
            ->setPassword(isset($config['password']) ? $config['password']:"")
        ;

        try {
            $response = $request->exec();
        } catch (InvalidArgumentException $e) {
            $response = array("Error"=>$e->getMessage());
        }

        return $response;
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $item) {
            $loopResultRow = new LoopResultRow();
            $distance = $item->distanceEnMetre;
            if (strlen($distance) < 4) {
                $distance .= " m";
            } else {
                $distance = (string) floatval($distance) / 1000;
                while (substr($distance, strlen($distance) - 1, 1) == "0") {
                    $distance = substr($distance, 0, strlen($distance) - 1);
                }
                $distance = str_replace(".", ",", $distance) . " km";
            }

            $loopResultRow->set("NAME", $item->nom)
                ->set("LONGITUDE", str_replace(",", ".", $item->coordGeolocalisationLongitude))
                ->set("LATITUDE", str_replace(",", ".", $item->coordGeolocalisationLatitude))
                ->set("CODE", $item->identifiant)
                ->set("ADDRESS", $item->adresse1)
                ->set("ZIPCODE", $item->codePostal)
                ->set("CITY", $item->localite)
                ->set("DISTANCE", $distance);

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }

    /**
     *
     * define all args used in your loop
     *
     *
     * example :
     *
     * public function getArgDefinitions()
     * {
     *  return new ArgumentCollection(
     *       Argument::createIntListTypeArgument('id'),
     *           new Argument(
     *           'ref',
     *           new TypeCollection(
     *               new Type\AlphaNumStringListType()
     *           )
     *       ),
     *       Argument::createIntListTypeArgument('category'),
     *       Argument::createBooleanTypeArgument('new'),
     *       Argument::createBooleanTypeArgument('promo'),
     *       Argument::createFloatTypeArgument('min_price'),
     *       Argument::createFloatTypeArgument('max_price'),
     *       Argument::createIntTypeArgument('min_stock'),
     *       Argument::createFloatTypeArgument('min_weight'),
     *       Argument::createFloatTypeArgument('max_weight'),
     *       Argument::createBooleanTypeArgument('current'),
     *
     *   );
     * }
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createAnyTypeArgument("zipcode", ""),
            Argument::createAnyTypeArgument("city","")
        );
    }

}
