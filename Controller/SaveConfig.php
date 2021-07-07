<?php

namespace SoColissimo\Controller;

use SoColissimo\SoColissimo;
use Thelia\Controller\Admin\BaseAdminController;
use SoColissimo\Form\ConfigureSoColissimo;
use Thelia\Core\Translation\Translator;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;
use Thelia\Model\ConfigQuery;
use Thelia\Tools\URL;

class SaveConfig extends BaseAdminController
{
    public function save()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::UPDATE)) {
            return $response;
        }

        $form = new ConfigureSoColissimo($this->getRequest());
        try {
            $vform = $this->validateForm($form);

            SoColissimo::setConfigValue('socolissimo_username', $vform->get('socolissimo_username')->getData());
            SoColissimo::setConfigValue('socolissimo_password', $vform->get('socolissimo_password')->getData());
            SoColissimo::setConfigValue('socolissimo_google_map_key', $vform->get('socolissimo_google_map_key')->getData());
            SoColissimo::setConfigValue('socolissimo_endpoint_url', $vform->get('socolissimo_endpoint_url')->getData());
            SoColissimo::setConfigValue('socolissimo_dom_delivery_authorized', $vform->get('socolissimo_dom_delivery_authorized')->getData());

            return $this->generateRedirect(
                URL::getInstance()->absoluteUrl('/admin/module/SoColissimo', ['current_tab' => 'configure'])
            );
        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans("So Colissimo update config"),
                $e->getMessage(),
                $form,
                $e
            );

            return $this->render(
                'module-configure',
                [
                    'module_code' => 'SoColissimo',
                    'current_tab' => 'configure',
                ]
            );
        }
    }
}
