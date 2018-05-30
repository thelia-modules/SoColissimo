<?php

namespace SoColissimo\Controller;

use SoColissimo\Form\CheckSoColissimoIds;
use SoColissimo\SoColissimo;
use SoColissimo\WebService\FindByAddress;
use Symfony\Component\HttpFoundation\JsonResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Translation\Translator;
use Thelia\Model\ConfigQuery;

class CheckAccountController extends BaseAdminController
{
    /**
     * @return JsonResponse
     * @throws \Exception
     */
    public function check()
    {
        $translator = Translator::getInstance();
        $statusCode = 500;
        $message = '';
        $status = $translator->trans('Connection Failed', [], SoColissimo::DOMAIN);
        try {
            $form = new CheckSoColissimoIds($this->getRequest());
            $validateForm = $this->validateForm($form);
            $findAddressRequest = new FindByAddress();
            $findAddressRequest->setAddress($validateForm->get('address')->getData())
                ->setZipCode($validateForm->get('postcode')->getData())
                ->setCity($validateForm->get('city')->getData())
                ->setLang($validateForm->get('lang')->getData())
                ->setFilterRelay(0)
                ->setShippingDate(date("d/m/Y"))
                ->setAccountNumber(ConfigQuery::read('socolissimo_login'))
                ->setPassword(ConfigQuery::read('socolissimo_pwd'))
            ;
            $findAddressRequest->exec();
            $status = $translator->trans('Connection Success', [], SoColissimo::DOMAIN);
            $statusCode = 200;
        } catch (\Exception $e) {
           $message = $e->getMessage();
        }
        return new JsonResponse([
            'status' => $status,
            'message' => $message,
        ], $statusCode);
    }
}