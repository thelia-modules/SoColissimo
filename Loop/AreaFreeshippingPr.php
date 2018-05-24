<?php

namespace SoColissimo\Loop;

use SoColissimo\Model\SocolissimoAreaFreeshippingPrQuery;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class AreaFreeshippingPr extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * @return ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id')
        );
    }

    public function buildModelCriteria()
    {
        $mode = $this->getId();

        $modes = SocolissimoAreaFreeshippingPrQuery::create();

        if (null !== $mode) {
            $modes->filterById($mode);
        }

        return $modes;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var \SoColissimo\Model\SocolissimoAreaFreeshippingPr $mode */
        foreach ($loopResult->getResultDataCollection() as $mode) {
            $loopResultRow = new LoopResultRow($mode);
            $loopResultRow->set("ID", $mode->getId())
                ->set("AREA_ID", $mode->getAreaId())
                ->set("DELIVERY_MODE_ID", $mode->getDeliveryModeId())
                ->set("CART_AMOUNT", $mode->getCartAmount());
            $loopResult->addRow($loopResultRow);
        }
        return $loopResult;
    }

}