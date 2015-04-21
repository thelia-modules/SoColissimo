<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace SoColissimo\Hook;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;


/**
 * Class FrontHook
 * @package SoColissimo\Hook
 * @author Michaël Espeche <mespeche@openstudio.fr>
 */
class FrontHook extends BaseHook {



    public function onOrderDeliveryExtra(HookRenderEvent $event)
    {
        $content = $this->render("socolissimo.html", $event->getArguments());
        $event->add($content);
    }

    public function onOrderInvoiceDeliveryAddress(HookRenderEvent $event)
    {
        $content = $this->render("delivery-address.html", $event->getArguments());
        $event->add($content);
    }

    public function onMainHeadBottom(HookRenderEvent $event)
    {
        $content = $this->addCSS('assets/css/styles.css');
        $event->add($content);
    }
} 