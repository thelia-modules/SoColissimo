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


namespace SoColissimo\Form;
use SoColissimo\Model\Config;
use SoColissimo\SoColissimo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;


/**
 * Class ConfigureSoColissimo
 * @package SoColissimo\Form 
 * @author Thelia <info@thelia.net>
 */
class ConfigureSoColissimo extends BaseForm {
    /**
     *
     * in this function you add all the fields you need for your Form.
     * Form this you have to call add method on $this->formBuilder attribute :
     *
     * $this->formBuilder->add("name", "text")
     *   ->add("email", "email", array(
     *           "attr" => array(
     *               "class" => "field"
     *           ),
     *           "label" => "email",
     *           "constraints" => array(
     *               new \Symfony\Component\Validator\Constraints\NotBlank()
     *           )
     *       )
     *   )
     *   ->add('age', 'integer');
     *
     * @return null
     */
    protected function buildForm()
    {
        $config = Config::read(SoColissimo::JSON_CONFIG_PATH);
        $this->formBuilder
            ->add("accountnumber","text",array(
                "constraints" => array(new NotBlank()),
                "data"=> isset($config["account_number"]) ? $config["account_number"]:"",
                "label" => Translator::getInstance()->trans("Account number"),
                "label_attr"=>array("for"=>"accountnumber")
            ))
            ->add("password","text",array(
                "constraints" => array(new NotBlank()),
                "data"=> isset($config["password"]) ? $config["password"]:"",
                "label" => Translator::getInstance()->trans("Password"),
                "label_attr"=>array("for"=>"password")
            ))
        ;
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return "configuresocolissimo";
    }

} 