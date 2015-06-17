<?php
/**
 * Created by PhpStorm.
 * User: ducher
 * Date: 17/06/15
 * Time: 11:18
 */

namespace SoColissimo\Loop;

use SoColissimo\Model\OrderAddressSocolissimo;
use SoColissimo\Model\OrderAddressSocolissimoQuery;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class SoColissimoOrderAddressLoop extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * Definition of loop arguments
     *
     * example :
     *
     * public function getArgDefinitions()
     * {
     *  return new ArgumentCollection(
     *
     *       Argument::createIntListTypeArgument('id'),
     *           new Argument(
     *           'ref',
     *           new TypeCollection(
     *               new Type\AlphaNumStringListType()
     *           )
     *       ),
     *       Argument::createIntListTypeArgument('category'),
     *       Argument::createBooleanTypeArgument('new'),
     *       ...
     *   );
     * }
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id', null, true)
        );
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $query = OrderAddressSocolissimoQuery::create();

        if (!is_null($id = $this->getId())) {
            $query->filterById(intval($id));
        }

        return $query;
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var OrderAddressSocolissimo $orderAddressSocolissimo */
        foreach ($loopResult->getResultDataCollection() as $orderAddressSocolissimo) {
            $row = new LoopResultRow();
            $row->set('ID', $orderAddressSocolissimo->getId());
            $row->set('CODE', $orderAddressSocolissimo->getCode());
            $row->set('TYPE', $orderAddressSocolissimo->getType());
            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}
