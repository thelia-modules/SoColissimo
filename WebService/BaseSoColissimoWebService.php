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

use Thelia\Model\ConfigQuery;

/**
 * Class BaseSoColissimoWebService
 * @package SoColissimo\WebService
 * @author Thelia <info@thelia.net>
 *
 * @method BaseSoColissimoWebService getAccountNumber()
 * @method BaseSoColissimoWebService setAccountNumber($value)
 * @method BaseSoColissimoWebService getPassword()
 * @method BaseSoColissimoWebService setPassword($value)
 * @method BaseSoColissimoWebService getWeight()
 * @method BaseSoColissimoWebService setWeight($value)
 */
abstract class BaseSoColissimoWebService extends BaseWebService
{
    /** @var null|string */
    protected $account_number = null;

    /** @var null|string */
    protected $password = null;

    /** @var null|string */
    protected $filter_relay = null;

    /** @var string Weight in grammes !*/
    protected $weight = null;

    public function __construct($function)
    {
        $testMode = ConfigQuery::read('socolissimo_test_mode');
        if ($testMode) {
            $url = ConfigQuery::read('socolissimo_url_test');
        } else {
            $url = ConfigQuery::read('socolissimo_url_prod');
        }
        parent::__construct($url, $function);
    }
}
