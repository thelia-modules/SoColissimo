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

use Propel\Runtime\ActiveQuery\Criteria;
use SoColissimo\SoColissimo;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;

use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\OrderQuery;

/**
 * Class SoColissimoUrlTracking
 * @package SoColissimo\Loop
 * @author Thelia <info@thelia.net>
 */
class SoColissimoUrlTracking extends BaseLoop implements ArraySearchLoopInterface
{
    /**
     * @return ArgumentCollection
     */
    const BASE_URL="http://e-trace.ils-consult.fr/ici-webtrace/webclients.aspx?verknr=%s&versdat=&kundenr=%s&cmd=VERKNR_SEARCH";
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createAnyTypeArgument('ref', null, true)
        );
    }

    public function buildArray()
    {
            $query = OrderQuery::create()
                ->filterByStatusId(array(SoColissimo::STATUS_PAID, SoColissimo::STATUS_PROCESSING),Criteria::IN)
                ->findOneByRef($this->getRef());

            if($query !== null && $query->getDeliveryModuleId() == SoColissimo::getModCode()) {
                return array($this->getRef()=>"s");
            } else {
                return array();
            }
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $ref => $code) {
            $loopResultRow = new LoopResultRow();
            $loopResultRow->set("URL", sprintf(self::BASE_URL,$ref,$code));

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;

    }
}
