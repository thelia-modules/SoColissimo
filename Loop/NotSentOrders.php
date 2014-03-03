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
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\OrderQuery;
use Thelia\Core\Template\Loop\Order;

/**
 * Class NotSentOrders
 * @package SoColissimo\Loop
 * @author Thelia <info@thelia.net>
 */
class NotSentOrders extends Order
{
    public function getArgDefinitions()
    {
        return new ArgumentCollection();
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $query = OrderQuery::create()
            ->filterByDeliveryModuleId(SoColissimo::getModCode())
            ->filterByStatusId(array(SoColissimo::STATUS_PAID, SoColissimo::STATUS_PROCESSING), Criteria::IN);

        return $query;
    }

}
