<?php

namespace SoColissimo\Smarty\Plugins;

use SoColissimo\SoColissimo;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\CountryQuery;
use Thelia\Module\Exception\DeliveryException;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;

class SocolissimoDeliveryPrice extends AbstractSmartyPlugin
{
    protected $request;
    protected $dispatcher;

    public function __construct(
        Request $request,
        EventDispatcherInterface $dispatcher = null
    ) {
        $this->request = $request;
        $this->dispatcher = $dispatcher;
    }

    public function getPluginDescriptors()
    {
        return array(
            new SmartyPluginDescriptor("function", "socolissimoDeliveryPrice", $this, "socolissimoDeliveryPrice")
        );
    }

    public function socolissimoDeliveryPrice($params, $smarty)
    {
        $deliveryMode = $params["delivery-mode"];

        $country = Country::getShopLocation();
        if (isset($params["country"])) {
            $country = CountryQuery::create()->findOneById($params["country"]);
        }

        $cartWeight = $this->request->getSession()->getSessionCart($this->dispatcher)->getWeight();
        $cartAmount = $this->request->getSession()->getSessionCart($this->dispatcher)->getTaxedAmount($country);

        try {
            $price = SoColissimo::getPostageAmount(
                $country->getAreaId(),
                $cartWeight,
                $cartAmount,
                $deliveryMode
            );
        } catch (DeliveryException $ex) {
            $smarty->assign('isValidMode', false);
        }

        $smarty->assign('deliveryModePrice', $price);
    }
}