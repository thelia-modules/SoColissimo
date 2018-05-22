<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia                                                                       */
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
/*      along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace SoColissimo\Controller;

use SoColissimo\Model\SocolissimoAreaFreeshippingDom;
use SoColissimo\Model\SocolissimoAreaFreeshippingDomQuery;
use SoColissimo\Model\SocolissimoAreaFreeshippingPr;
use SoColissimo\Model\SocolissimoAreaFreeshippingPrQuery;
use SoColissimo\Model\SocolissimoDeliveryModeQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Response;

use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;
use Thelia\Model\AreaQuery;

class FreeShipping extends BaseAdminController
{
    public function toggleFreeShippingActivation()
    {
        if (null !== $response = $this
                ->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::UPDATE)) {
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
        if (null !== $response = $this
                ->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::UPDATE)) {
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

    /**
     * @return mixed|null|\Symfony\Component\HttpFoundation\Response
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setAreaFreeShipping()
    {
        if (null !== $response = $this
                ->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::UPDATE)) {
            return $response;
        }

        $data = $this->getRequest()->request;

        try {
            $data = $this->getRequest()->request;

            $socolissimo_area_id = $data->get('area-id');
            $socolissimo_delivery_id = $data->get('delivery-mode');
            $cartAmount = $data->get("cart-amount");

            if ($cartAmount < 0 || $cartAmount === '') {
                $cartAmount = null;
            }

            $aeraQuery = AreaQuery::create()->findOneById($socolissimo_area_id);
            if (null === $aeraQuery) {
                return null;
            }

            $deliveryModeQuery = SocolissimoDeliveryModeQuery::create()->findOneById($socolissimo_delivery_id);
            if (null === $deliveryModeQuery) {
                return null;
            }

            //Price slices for "Domicile"
            if ($socolissimo_delivery_id === '1') {
                $socolissimoFreeShippingDom = new SocolissimoAreaFreeshippingDom();

                $socolissimoAreaFreeshippingDomQuery = SocolissimoAreaFreeshippingDomQuery::create()
                    ->filterByAreaId($socolissimo_area_id)
                    ->filterByDeliveryModeId($socolissimo_delivery_id)
                    ->findOne();

                if (null === $socolissimoAreaFreeshippingDomQuery) {
                    $socolissimoFreeShippingDom
                        ->setAreaId($socolissimo_area_id)
                        ->setDeliveryModeId($socolissimo_delivery_id)
                        ->setCartAmount($cartAmount)
                        ->save();
                }

                $cartAmountDomQuery = SocolissimoAreaFreeshippingDomQuery::create()
                    ->filterByAreaId($socolissimo_area_id)
                    ->filterByDeliveryModeId($socolissimo_delivery_id)
                    ->findOneOrCreate()
                    ->setCartAmount($cartAmount)
                    ->save();
            }

            //Price slices for "Point Relais"
            if ($socolissimo_delivery_id === '2') {
                $socolissimoFreeShippingPr = new SocolissimoAreaFreeshippingPr();

                $socolissimoAreaFreeshippingPrQuery = SocolissimoAreaFreeshippingPrQuery::create()
                    ->filterByAreaId($socolissimo_area_id)
                    ->filterByDeliveryModeId($socolissimo_delivery_id)
                    ->findOne();

                if (null === $socolissimoAreaFreeshippingPrQuery) {
                    $socolissimoFreeShippingPr
                        ->setAreaId($socolissimo_area_id)
                        ->setDeliveryModeId($socolissimo_delivery_id)
                        ->setCartAmount($cartAmount)
                        ->save();
                }

                $cartAmountPrQuery = SocolissimoAreaFreeshippingPrQuery::create()
                    ->filterByAreaId($socolissimo_area_id)
                    ->filterByDeliveryModeId($socolissimo_delivery_id)
                    ->findOneOrCreate()
                    ->setCartAmount($cartAmount)
                    ->save();
            }
        } catch (\Exception $e) {
        }

        return $this->generateRedirectFromRoute(
            "admin.module.configure",
            array(),
            array(
                'current_tab' => 'prices_slices_tab_' . $data->get('area_freeshipping'),
                'module_code' => "SoColissimo",
                '_controller' => 'Thelia\\Controller\\Admin\\ModuleController::configureAction',
                'price_error_id' => null,
                'price_error' => null
            )
        );
    }
}
