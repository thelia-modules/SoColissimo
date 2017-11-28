<?php

namespace SoColissimo\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class BackHook
 * @package SoColissimo\Hook
 */
class BackHook extends BaseHook
{
    public function onModuleConfiguration(HookRenderEvent $event)
    {
        $event->add($this->render('SoColissimo/module_configuration.html'));
    }

    public function onModuleConfigJs(HookRenderEvent $event)
    {
        $event->add($this->render('SoColissimo/module-config-js.html'));
    }
}
