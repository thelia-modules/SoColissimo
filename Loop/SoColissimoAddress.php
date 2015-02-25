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

use SoColissimo\Model\AddressSocolissimoQuery;
use Thelia\Core\Template\Loop\Address;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;

/**
 * Class SoColissimoDelivery
 * @package SoColissimo\Loop
 * @author Thelia <info@thelia.net>
 */
class SoColissimoAddress extends Address
{
    /** @var bool */
    protected $exists = false;

    /** @var bool */
    protected $timestampable = false;

    /**
     * @param $id
     */
    protected function setExists($id)
    {
        $this->exists = AddressSocolissimoQuery::create()->findPK($id) !== null;
    }

    /**
     * @return AddressSocolissimoQuery|\Thelia\Model\AddressQuery
     */
    public function buildModelCriteria()
    {
        $id = $this->getId();
        $this->setExists($id[0]);

        return $this->exists ?
                AddressSoColissimoQuery::create()->filterById($id[0]) :
                parent::buildModelCriteria();
    }

    /**
     * @param LoopResult $loopResult
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        if (!$this->exists) {
            return parent::parseResults($loopResult);
        } else {
            /** @var \SoColissimo\Model\AddressSocolissimo $address */
            foreach ($loopResult->getResultDataCollection() as $address) {
                $loopResultRow = new LoopResultRow();
                $loopResultRow
                    ->set("TITLE", $address->getTitleId())
                    ->set("COMPANY", $address->getCompany())
                    ->set("FIRSTNAME", $address->getFirstname())
                    ->set("LASTNAME", $address->getLastname())
                    ->set("ADDRESS1", $address->getAddress1())
                    ->set("ADDRESS2", $address->getAddress2())
                    ->set("ADDRESS3", $address->getAddress3())
                    ->set("ZIPCODE", $address->getZipcode())
                    ->set("CITY", $address->getCity())
                    ->set("COUNTRY", $address->getCountryId());
                $loopResult->addRow($loopResultRow);
            }

            return $loopResult;
        }
    }
}
