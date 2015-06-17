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

use SoColissimo\SoColissimo;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\OrderQuery;

class PdfHook extends BaseHook
{
    public function onInvoiceAfterDeliveryModule(HookRenderEvent $event)
    {
            (SoColissimo::getModuleId() == $event->getArgument('module_id'))
        and !(is_null($order = OrderQuery::create()->findOneById($event->getArgument('order'))))
        and $event->add($this->render(
            'delivery_mode_infos.html',
            ['delivery_address_id' => $order->getDeliveryOrderAddressId()]
        ));
    }
}
