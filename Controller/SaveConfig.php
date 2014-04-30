<?php

namespace SoColissimo\Controller;

use SoColissimo\SoColissimo;
use Thelia\Controller\Admin\BaseAdminController;
use SoColissimo\Form\ConfigureSoColissimo;
use Thelia\Core\Translation\Translator;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;
use Thelia\Model\ConfigQuery;

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

            ConfigQuery::write('socolissimo_login', $vform->get('accountnumber')->getData(), 1, 1);
            ConfigQuery::write('socolissimo_pwd', $vform->get('password')->getData(), 1, 1);
            ConfigQuery::write('socolissimo_url_prod', $vform->get('url_prod')->getData(), 1, 1);
            ConfigQuery::write('socolissimo_url_test', $vform->get('url_test')->getData(), 1, 1);
            ConfigQuery::write('socolissimo_test_mode', $vform->get('test_mode')->getData(), 1, 1);

            $this->redirectToRoute("admin.module.configure", [], ['module_code' => 'SoColissimo', 'current_tab' => 'configure']);
        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans("SO Colissimo update config"),
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
