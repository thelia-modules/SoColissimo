<?php

namespace SoColissimo\Loop;

use SoColissimo\Model\SocolissimoDeliveryModeQuery;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class SoColissimoDeliveryMode extends BaseLoop implements PropelSearchLoopInterface
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

        $modes = SocolissimoDeliveryModeQuery::create();

        if (null !== $mode) {
            $modes->filterById($mode);
        }

        return $modes;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var \SoColissimo\Model\SocolissimoDeliveryMode $mode */
        foreach ($loopResult->getResultDataCollection() as $mode) {
            $loopResultRow = new LoopResultRow($mode);
            $loopResultRow->set("ID", $mode->getId())
                ->set("TITLE", $mode->getTitle())
                ->set("FREESHIPPING_ACTIVE", $mode->getFreeshippingActive())
                ->set("FREESHIPPING_FROM", $mode->getFreeshippingFrom());
            $loopResult->addRow($loopResultRow);
        }
        return $loopResult;
    }

}