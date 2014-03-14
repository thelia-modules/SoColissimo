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

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Template\TemplateDefinition;

/**
 * Class SearchCityController
 * @package IciRelais\Controller
 * @author Thelia <info@thelia.net>
 */
class GetSpecificLocation extends BaseFrontController
{
    public function get($zipcode, $city)
    {
        $parser = $this->getParser();
        $parser->setTemplateDefinition(
            new TemplateDefinition(
                'module_socolissimo',
                TemplateDefinition::FRONT_OFFICE
            )
        );

        return Response::create(
            $parser->render(
                "getSpecificLocationSoColissimo.html",
                array(
                    "_zipcode_"=>$zipcode,
                    "_city_"=>$city)
            ),
            200,
            array(
                "Content-type"=>"application/json",
            )
        );
    }
}
