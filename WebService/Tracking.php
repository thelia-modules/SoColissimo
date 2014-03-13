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


/**
 * Class Tracking
 * @package SoColissimo\WebService 
 * @author Thelia <info@thelia.net>
 */
class Tracking extends BaseSoColissimoWebService {

    const WSDL_URL = "https://www.coliposte.fr/tracking-chargeur-cxf/TrackingServiceWS?wsdl";

    protected $skybill_number=null;

    public function __construct()
    {
        parent::__construct("track");
    }
    /**
     * @return bool
     */
    public function isError(\stdClass $response)
    {
        return isset($response->return->errorCode) && $response->return->errorCode != 0;
    }

    public function getError(\stdClass $response)
    {
        return isset($response->return->errorMessage) ? $response->return->errorMessage : "Unknown error";
    }

    /**
     * @return \stdClass
     */
    public function getFormattedResponse(\stdClass $response)
    {
        if (!isset($response->return->pointRetraitAcheminement)) {
            throw new \Exception("An unknown error happened");
        }
        $points = $response->return->SkybillInformationResult;

        return $points;
    }
} 