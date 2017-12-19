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

use SoColissimo\SoColissimo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\ConfigQuery;

/**
 * Class ConfigureSoColissimo
 * @package SoColissimo\Form
 * @author Thelia <info@thelia.net>
 */
class ConfigureSoColissimo extends BaseForm
{
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
        $translator = Translator::getInstance();
        $this->formBuilder
            ->add(
                'accountnumber',
                'text',
                [
                    'constraints' => [new NotBlank()],
                    'data'        => ConfigQuery::read('socolissimo_login'),
                    'label'       => $translator->trans("Account number", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'accountnumber']
                ]
            )
            ->add(
                'password',
                'text',
                [
                    'constraints' => [new NotBlank()],
                    'data'        => ConfigQuery::read('socolissimo_pwd'),
                    'label'       => $translator->trans("Password", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'password']
                ]
            )
            ->add(
                'url_prod',
                'text',
                [
                    'constraints' => [
                        new NotBlank(),
                        new Url([
                            'protocols' => ['https', 'http']
                        ])
                    ],
                    'data'        => ConfigQuery::read('socolissimo_url_prod'),
                    'label'       => $translator->trans("Colissimo URL prod", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'socolissimo_url_prod']
                ]
            )
            ->add(
                'url_test',
                'text',
                [
                    'constraints' => [
                        new NotBlank(),
                        new Url([
                            'protocols' => ['https', 'http']
                        ])
                    ],
                    'data'        => ConfigQuery::read('socolissimo_url_test'),
                    'label'       => $translator->trans("Colissimo URL test", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'socolissimo_url_test']
                ]
            )
            ->add(
                'test_mode',
                'text',
                [
                    'constraints' => [new NotBlank()],
                    'data'        => ConfigQuery::read('socolissimo_test_mode'),
                    'label'       => $translator->trans("Test mode", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'test_mode']
                ]
            )
            ->add(
                'google_map_key',
                'text',
                [
                    'constraints' => [],
                    'data'        => ConfigQuery::read('socolissimo_google_map_key'),
                    'label'       => $translator->trans("Google map API key", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'google_map_key']
                ]
            )
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
