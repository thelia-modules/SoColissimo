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

namespace SoColissimo\Controller;

use SoColissimo\Model\SocolissimoDeliveryModeQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Response;

use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;

class FreeShipping extends BaseAdminController
{
    public function toggleFreeShippingActivation()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::UPDATE)) {
            return $response;
        }

        $form = new \SoColissimo\Form\FreeShipping($this->getRequest());
        $response=null;

        try {
            $vform = $this->validateForm($form);
            $freeshipping = $vform->get('freeshipping')->getData();
            $deliveryModeId = $vform->get('delivery_mode')->getData();

            $deliveryMode = SocolissimoDeliveryModeQuery::create()->findOneById($deliveryModeId);
            $deliveryMode->setFreeshippingActive($freeshipping)
                ->save();
            $response = Response::create('');
        } catch (\Exception $e) {
            $response = JsonResponse::create(array("error"=>$e->getMessage()), 500);
        }

        return $response;
    }

    public function setFreeShippingFrom()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::UPDATE)) {
            return $response;
        }

        $data = $this->getRequest()->request;
        $deliveryMode = SocolissimoDeliveryModeQuery::create()->findOneById($data->get('delivery-mode'));

        $price = $data->get("price") === "" ? null : $data->get("price");

        if ($price < 0) {
            $price = null;
        }

        $deliveryMode->setFreeshippingFrom($price)
            ->save();

        return $this->generateRedirectFromRoute(
            "admin.module.configure",
            array(),
            array (
                'current_tab'=>'prices_slices_tab_'.$data->get('delivery-mode'),
                'module_code'=>"SoColissimo",
                '_controller' => 'Thelia\\Controller\\Admin\\ModuleController::configureAction',
                'price_error_id' => null,
                'price_error' => null
            )
        );
    }
}
