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

namespace SoColissimo\WebService;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class FindByAddress
 * @package SoColissimo\WebService
 * @author Thelia <info@thelia.net>
 *
 * @method FindByAddress getAddress()
 * @method FindByAddress setAddress($value)
 * @method FindByAddress getZipCode()
 * @method FindByAddress setZipCode($value)
 * @method FindByAddress getCity()
 * @method FindByAddress setCity($value)
 * @method FindByAddress getCountryCode()
 * @method FindByAddress setCountryCode($value)
 * @method FindByAddress getFilterRelay()
 * @method FindByAddress setFilterRelay($value)
 * @method FindByAddress getRequestId()
 * @method FindByAddress setRequestId($value)
 * @method FindByAddress getLang()
 * @method FindByAddress setLang($value)
 * @method FindByAddress getOptionInter()
 * @method FindByAddress setOptionInter($value)
 * @method FindByAddress getShippingDate()
 * @method FindByAddress setShippingDate($value)
 */
class FindByAddress extends BaseSoColissimoWebService
{
    /** @var string */
    protected $address = null;

    /** @var string */
    protected $zip_code = null;

    /** @var string */
    protected $city = null;

    /** @var string */
    protected $country_code = null;

    /** @var string */
    protected $request_id = null;

    /** @var string */
    protected $lang = null;

    /** @var string */
    protected $option_inter = null;

    /** @var string */
    protected $shipping_date=null;

    public function __construct()
    {
        parent::__construct("findRDVPointRetraitAcheminement");
    }

    /**
     * @param \stdClass $response
     * @return bool
     */
    public function isError(\stdClass $response)
    {
        return isset($response->return->errorCode) && $response->return->errorCode != 0;
    }

    /**
     * @param \stdClass $response
     * @return string
     */
    public function getError(\stdClass $response)
    {
        return isset($response->return->errorMessage) ? $response->return->errorMessage : "Unknown error";
    }

    /**
     * @param  \stdClass $response
     * @return array
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function getFormattedResponse(\stdClass $response)
    {
        if (!isset($response->return->listePointRetraitAcheminement)) {
            throw new Exception("An unknown error happened");
        }
        $points = $response->return->listePointRetraitAcheminement;

        return $points;
    }
}
