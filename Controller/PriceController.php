<?php

namespace SoColissimo\Controller;

use SoColissimo\Form\AddPriceForm;
use SoColissimo\Form\UpdatePriceForm;
use SoColissimo\Model\SocolissimoPrice;
use SoColissimo\Model\SocolissimoPriceQuery;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;

class PriceController extends BaseAdminController
{
    public function addPrice()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::CREATE)) {
            return $response;
        }

        try {
            $form = new AddPriceForm($this->getRequest());
            $data = $this->validateForm($form)->getData();
            $newPrice = new SocolissimoPrice();
            $newPrice->setAreaId($data['area'])
                ->setDeliveryModeId($data['delivery_mode'])
                ->setWeightMax($data['weight'])
                ->setPrice($data['price']);

            if (isset($data['franco'])) {
                $newPrice->setFrancoMinPrice($data['franco']);
            }

            $newPrice->save();

        } catch (\Exception $e) {
            $deliveryModeId = $this->getRequest()->request->get('socolissimo_price_create')['delivery_mode'];
            return $this->priceTabResponse($deliveryModeId, $e->getMessage());
        }

        return $this->priceTabResponse($data['delivery_mode']);
    }

    public function updatePrice()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::UPDATE)) {
            return $response;
        }

        try {
            $form = new UpdatePriceForm($this->getRequest());
            $data = $this->validateForm($form)->getData();
            $price = SocolissimoPriceQuery::create()
            ->filterByAreaId($data['area'])
                ->filterByDeliveryModeId($data['delivery_mode'])
                ->filterByWeightMax($data['weight'])
                ->findOneOrCreate();
            if (isset($data['franco'])) {
                $price->setFrancoMinPrice($data['franco']);
            }
            $price->setPrice($data['price'])
                ->save();

        } catch (\Exception $e) {
            $deliveryModeId = $this->getRequest()->request->get('socolissimo_price_create')['delivery_mode'];
            return $this->priceTabResponse($deliveryModeId, $e->getMessage());
        }

        return $this->priceTabResponse($data['delivery_mode']);
    }

    public function deletePrice()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::DELETE)) {
            return $response;
        }

        $data = $this->getRequest()->request;
        SocolissimoPriceQuery::create()
            ->filterByAreaId($data->get('area'))
            ->filterByDeliveryModeId($data->get('delivery_mode'))
            ->findOneByWeightMax($data->get('weight'))
            ->delete();

        return $this->priceTabResponse($data->get('delivery_mode'));
    }

    protected function priceTabResponse($deliveryModeId, $error = null)
    {
        return $this->generateRedirectFromRoute(
            "admin.module.configure",
            array(),
            array (
                'current_tab'=>'prices_slices_tab_'.$deliveryModeId,
                'module_code'=>"SoColissimo",
                '_controller' => 'Thelia\\Controller\\Admin\\ModuleController::configureAction',
                'price_error_id' => $deliveryModeId,
                'price_error' => $error
            )
        );
    }
}
