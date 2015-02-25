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
 * Class FindById
 * @package SoColissimo\WebService
 * @author Thelia <info@thelia.net>
 *
 * @method FindById getId()
 * @method FindById setId($value)
 * @method FindById getReseau()
 * @method FindById setReseau($value)
 * @method FindById getLangue()
 * @method FindById setLangue($value)
 * @method FindById getDate()
 * @method FindById setDate($value)
 */
class FindById extends BaseSoColissimoWebService
{
    /** @var string */
    protected $id;

    /** @var  string if belgique: R12, else empty */
    protected $reseau;

    protected $langue;

    protected $date;

    public function __construct()
    {
        parent::__construct("findPointRetraitAcheminementByID");
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
     * @param  \stdClass                                                $response
     * @return \stdClass
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function getFormattedResponse(\stdClass $response)
    {
        if (!isset($response->return->pointRetraitAcheminement)) {
            throw new Exception("An unknown error happened");
        }
        $points = $response->return->pointRetraitAcheminement;

        return $points;
    }
}
