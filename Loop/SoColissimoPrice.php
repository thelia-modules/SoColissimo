<?php

namespace SoColissimo\Loop;

use SoColissimo\Model\SocolissimoPriceQuery;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class SoColissimoPrice extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * @return ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('area_id', null, true),
            Argument::createIntTypeArgument('delivery_mode_id', null, true)
        );
    }

    public function buildModelCriteria()
    {
        $areaId = $this->getAreaId();
        $modeId = $this->getDeliveryModeId();

        $areaPrices = SocolissimoPriceQuery::create()
            ->filterByDeliveryModeId($modeId)
            ->filterByAreaId($areaId)
            ->orderByWeightMax();

        return $areaPrices;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var \SoColissimo\Model\SocolissimoPrice $price */
        foreach ($loopResult->getResultDataCollection() as $price) {
            $loopResultRow = new LoopResultRow($price);
            $loopResultRow->set("MAX_WEIGHT", $price->getWeightMax())
                ->set("PRICE", $price->getPrice());
            $loopResult->addRow($loopResultRow);
        }
        return $loopResult;
    }

}