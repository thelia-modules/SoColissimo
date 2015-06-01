<?php

namespace SoColissimo\Form;

use SoColissimo\Model\SocolissimoDeliveryModeQuery;
use Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\ExecutionContextInterface;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\AreaQuery;

class AddPriceForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add("area", "integer", array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(array(
                        "methods" => array(
                            array($this,
                                "verifyAreaExist")
                        )
                    ))
                )
            ))
            ->add("delivery_mode", "integer", array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(array(
                        "methods" => array(
                            array($this,
                                "verifyDeliveryModeExist")
                        )
                    ))
                )
            ))
            ->add("weight", "number", array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(array(
                        "methods" => array(
                            array($this,
                                "verifyValidWeight")
                        )
                    ))
                )
            ))
            ->add("price", "number", array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(array(
                        "methods" => array(
                            array($this,
                                "verifyValidPrice")
                        )
                    ))
                )
            ))
        ;
    }

    public function verifyAreaExist($value, ExecutionContextInterface $context)
    {
        $area = AreaQuery::create()->findPk($value);
        if (null === $area) {
            $context->addViolation(Translator::getInstance()->trans("This area doesn't exists."));
        }
    }

    public function verifyDeliveryModeExist($value, ExecutionContextInterface $context)
    {
        $mode = SocolissimoDeliveryModeQuery::create()->findPk($value);
        if (null === $mode) {
            $context->addViolation(Translator::getInstance()->trans("This delivery mode doesn't exists."));
        }
    }

    public function verifyValidWeight($value, ExecutionContextInterface $context)
    {
        if (!preg_match("#^\d+\.?\d*$#", $value)) {
            $context->addViolation(Translator::getInstance()->trans("The weight value is not valid."));
        }

        if ($value < 0) {
            $context->addViolation(Translator::getInstance()->trans("The weight value must be superior to 0."));
        }
    }

    public function verifyValidPrice($value, ExecutionContextInterface $context)
    {
        if (!preg_match("#^\d+\.?\d*$#", $value)) {
            $context->addViolation(Translator::getInstance()->trans("The price value is not valid."));
        }
    }

    public function getName()
    {
        return "socolissimo_price_create";
    }
}