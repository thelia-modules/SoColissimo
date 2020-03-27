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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'socolissimo_username',
                TextType::class,
                [
                    'constraints' => [new NotBlank()],
                    'data'        => SoColissimo::getConfigValue('socolissimo_username'),
                    'label'       => $translator->trans("Account number", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'socolissimo_username']
                ]
            )
            ->add(
                'socolissimo_password',
                TextType::class,
                [
                    'constraints' => [new NotBlank()],
                    'data'        => SoColissimo::getConfigValue('socolissimo_password'),
                    'label'       => $translator->trans("Password", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'socolissimo_password']
                ]
            )
            ->add(
                'socolissimo_endpoint_url',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Url([
                            'protocols' => ['https', 'http']
                        ])
                    ],
                    'data'        => SoColissimo::getConfigValue('socolissimo_endpoint_url'),
                    'label'       => $translator->trans("Colissimo URL prod", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'socolissimo_endpoint_url']
                ]
            )
            ->add(
                'socolissimo_google_map_key',
                TextType::class,
                [
                    'constraints' => [],
                    'data'        => SoColissimo::getConfigValue('socolissimo_google_map_key'),
                    'label'       => $translator->trans("Google map API key", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'socolissimo_google_map_key']
                ]
            )
            ->add(
                'socolissimo_dom_delivery_authorized',
                CheckboxType::class,
                [
                    'constraints' => [],
                    'required'      => false,
                    'data'          => (bool)SoColissimo::getConfigValue('socolissimo_dom_delivery_authorized'),
                    'value'         => (bool)SoColissimo::getConfigValue('socolissimo_dom_delivery_authorized'),
                    'label'       => $translator->trans("[DEPRECATED] Activate Dom delivery", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'socolissimo_dom_delivery_authorized']
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
