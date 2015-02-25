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

use SoColissimo\SoColissimo;
use Thelia\Controller\Admin\BaseAdminController;
use SoColissimo\Form\ConfigureSoColissimo;
use Thelia\Core\Translation\Translator;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;
use Thelia\Model\ConfigQuery;

class SaveConfig extends BaseAdminController
{
    /**
     * @return mixed|\Thelia\Core\HttpFoundation\Response
     */
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

            if (SoColissimo::checkVersion('<', '2.1.0')) {
                $this->redirectToRoute(
                    "admin.module.configure",
                    [],
                    ['module_code' => 'SoColissimo', 'current_tab' => 'configure']
                );
            } else {
                return $this->generateRedirectFromRoute(
                    "admin.module.configure",
                    [],
                    ['module_code' => 'SoColissimo', 'current_tab' => 'configure']
                );
            }


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
