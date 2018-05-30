<?php
/**
 * Created by PhpStorm.
 * User: audreymartel
 * Date: 30/05/2018
 * Time: 10:13
 */

namespace SoColissimo\Form;

use SoColissimo\SoColissimo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;


/**
 * Class CheckColissimoIds
 * @package SoColissimo\Form
 * @author amartel <amartel@openstudio.fr>
 */
class CheckSoColissimoIds extends BaseForm
{
    protected function buildForm()
    {
        $translator = Translator::getInstance();
        $this->formBuilder
            ->add(
                'address',
                'text',
                [
                    'constraints' => [new NotBlank()],
                    'data'        => 'Clermont-Ferrand',
                    'label'       => $translator->trans("Address", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'address']
                ]
            )
            ->add(
                'postcode',
                'text',
                [
                    'constraints' => [new NotBlank()],
                    'data'        => '63000',
                    'label'       => $translator->trans("Postcode", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'postcode']
                ]
            )
            ->add(
                'city',
                'text',
                [
                    'constraints' => [new NotBlank()],
                    'data'        => 'France',
                    'label'       => $translator->trans("City", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'city']
                ]
            )
            ->add(
                'lang',
                'text',
                [
                    'constraints' => [new NotBlank()],
                    'data'        => 'FR',
                    'label'       => $translator->trans("Lang", [], SoColissimo::DOMAIN),
                    'label_attr'  => ['for' => 'lang']
                ]
            )
        ;
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return "checksocolissimoids";
    }
}
