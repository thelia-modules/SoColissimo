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

use SoColissimo\WebService\FindById;
use Symfony\Component\HttpFoundation\JsonResponse;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Model\ConfigQuery;

/**
 * Class SearchCityController
 * @package IciRelais\Controller
 * @author Thelia <info@thelia.net>
 */
class GetSpecificLocation extends BaseFrontController
{
    public function get($zipcode, $city)
    {

        return Response::create(
            $this->render(
                "getSpecificLocationSoColissimo",
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

    public function getPointInfo($point_id)
    {
        $req = new FindById();

        $req->setId($point_id)
            ->setLangue("FR")
            ->setDate(date("d/m/Y"))
            ->setAccountNumber(ConfigQuery::read('socolissimo_login'))
            ->setPassword(ConfigQuery::read('socolissimo_pwd'))
        ;

        $response = $req->exec();

        $response = new JsonResponse($response);

        return $response;
    }


    /**
     * @return ParserInterface instance parser
     */
    protected function getParser($template = null)
    {
        $parser = $this->container->get("thelia.parser");

        // Define the template that should be used
        $parser->setTemplateDefinition(
            new TemplateDefinition(
                'module_socolissimo',
                TemplateDefinition::FRONT_OFFICE
            )
        );

        return $parser;
    }
}
