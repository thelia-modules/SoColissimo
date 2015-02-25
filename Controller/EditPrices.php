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
use Thelia\Model\AreaQuery;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;

/**
 * Class EditPrices
 * @package SoColissimo\Controller
 * @author Thelia <info@thelia.net>
 */
class EditPrices extends BaseAdminController
{
    public function editprices()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::UPDATE)) {
            return $response;
        }

        // Get data & treat
        $post = $this->getRequest()->request;
        $operation = $post->get('operation');
        $area = $post->get('area');
        $weight = $post->get('weight');
        $price = $post->get('price');

        if (preg_match("#^add|delete$#", $operation) &&
            preg_match("#^\d+$#", $area) &&
            preg_match("#^\d+\.?\d*$#", $weight)
          ) {
            // check if area exists in db
            $exists = AreaQuery::create()
                ->findPK($area);
            if ($exists !== null) {
                $json_path= __DIR__."/../".SoColissimo::JSON_PRICE_RESOURCE;

                if (is_readable($json_path)) {
                    $json_data = json_decode(file_get_contents($json_path), true);
                } elseif (!file_exists($json_path)) {
                    $json_data = array();
                } else {
                    throw new \Exception("Can't read So Colissimo" . SoColissimo::JSON_PRICE_RESOURCE . ". Please change the rights on the file.");
                }
                if ((float) $weight > 0 && $operation == "add"
                  && preg_match("#\d+\.?\d*#", $price)) {
                    $json_data[$area]['slices'][$weight] = $price;
                } elseif ($operation == "delete") {
                    if (isset($json_data[$area]['slices'][$weight])) {
                        unset($json_data[$area]['slices'][$weight]);
                    }
                } else {
                    throw new \Exception("Weight must be superior to 0");
                }

                ksort($json_data[$area]['slices']);
                if ((file_exists($json_path) ?is_writable($json_path):is_writable(__DIR__."/../"))) {
                    $file = fopen($json_path, 'w');
                    fwrite($file, json_encode($json_data));
                    fclose($file);
                } else {
                    throw new \Exception("Can't write So Colissimo" . SoColissimo::JSON_PRICE_RESOURCE . ". Please change the rights on the file.");
                }
            } else {
                throw new \Exception("Area not found");
            }
        } else {
            throw new \ErrorException("Arguments are missing or invalid");
        }

        if (SoColissimo::checkVersion('<', '2.1.0')) {
            $this->redirectToRoute(
                "admin.module.configure",
                array(),
                array (
                    'current_tab'=>'price_slices_tab',
                    'module_code'=>"SoColissimo",
                    '_controller' => 'Thelia\\Controller\\Admin\\ModuleController::configureAction'
                )
            );
        } else {
            return $this->generateRedirectFromRoute(
                "admin.module.configure",
                array(),
                array (
                    'current_tab'=>'price_slices_tab',
                    'module_code'=>"SoColissimo",
                    '_controller' => 'Thelia\\Controller\\Admin\\ModuleController::configureAction'
                )
            );
        }
    }
}
