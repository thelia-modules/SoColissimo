<?php

namespace SoColissimo\Controller;

use SoColissimo\SoColissimo;
use Thelia\Controller\Admin\BaseAdminController;
use SoColissimo\Model\Config;
use SoColissimo\Form\ConfigureSoColissimo;
use Thelia\Core\Translation\Translator;

class SaveConfig extends BaseAdminController {
    public function save() {
        $error_message="";
        $conf = new Config();
        $form = new ConfigureSoColissimo($this->getRequest());
        try {
            $vform = $this->validateForm($form);
            // After post checks (PREG_MATCH) & create json file
            if(preg_match("#^[a-z\d]+$#i", $vform->get('password')->getData()) &&
                preg_match("#^[\d]+$#", $vform->get('accountnumber')->getData())
            ) {
                $conf->setAccountNumber($vform->get('accountnumber')->getData())
                    ->setPassword($vform->get('password')->getData())
                    ->write(SoColissimo::JSON_CONFIG_PATH)
                ;
            } else {
                throw new \Exception(Translator::getInstance()->trans("Error in form syntax, please check that your values are correct."));
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
        }
        $this->setupFormErrorContext(
            'erreur sauvegarde configuration',
            $error_message,
            $form
        );
        $this->redirectToRoute("admin.module.configure",array(),
            array ( 'module_code'=>"SoColissimo",
                '_controller' => 'Thelia\\Controller\\Admin\\ModuleController::configureAction'));
    }
}

