<?php

namespace SoColissimo\Controller;

use Propel\Runtime\Map\TableMap;
use SoColissimo\Model\SocolissimoPrice;
use SoColissimo\Model\SocolissimoPriceQuery;
use SoColissimo\SoColissimo;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;

class SliceController extends BaseAdminController
{
    public function saveSliceAction()
    {
        $response = $this->checkAuth([], ['socolissimo'], AccessManager::UPDATE);

        if (null !== $response) {
            return $response;
        }

        $this->checkXmlHttpRequest();

        $responseData = [
            "success" => false,
            "message" => '',
            "slice" => null
        ];

        $messages = [];
        $response = null;

        try {
            $requestData = $this->getRequest()->request;

            if (0 !== $id = intval($requestData->get('id', 0))) {
                $slice = SocolissimoPriceQuery::create()->findPk($id);
            } else {
                $slice = new SocolissimoPrice();
            }


            if (0 !== $areaId = intval($requestData->get('area', 0))) {
                $slice->setAreaId($areaId);
            } else {
                $messages[] = $this->getTranslator()->trans(
                    'The area is not valid',
                    [],
                    SoColissimo::DOMAIN
                );
            }

            if (0 !== $deliveryMode = intval($requestData->get('deliveryModeId', 0))) {
                $slice->setDeliveryModeId($deliveryMode);
            } else {
                $messages[] = $this->getTranslator()->trans(
                    'The delivery mode is not valid',
                    [],
                    SoColissimo::DOMAIN
                );
            }


            $requestPriceMax = $requestData->get('priceMax', null);
            $requestWeightMax = $requestData->get('weightMax', null);

            if (empty($requestPriceMax) && empty($requestWeightMax)) {
                $messages[] = $this->getTranslator()->trans(
                    'You must specify at least a price max or a weight max value.',
                    [],
                    SoColissimo::DOMAIN
                );
            } else {
                if (!empty($requestPriceMax)) {
                    $priceMax = $this->getFloatVal($requestPriceMax);
                    if (0 < $priceMax) {
                        $slice->setPriceMax($priceMax);
                    } else {
                        $messages[] = $this->getTranslator()->trans(
                            'The price max value is not valid',
                            [],
                            SoColissimo::DOMAIN
                        );
                    }
                } else {
                    $slice->setPriceMax(null);
                }

                if (!empty($requestWeightMax)) {
                    $weightMax = $this->getFloatVal($requestWeightMax);
                    if (0 < $weightMax) {
                        $slice->setWeightMax($weightMax);
                    } else {
                        $messages[] = $this->getTranslator()->trans(
                            'The weight max value is not valid',
                            [],
                            SoColissimo::DOMAIN
                        );
                    }
                } else {
                    $slice->setWeightMax(null);
                }
            }



            $price = $this->getFloatVal($requestData->get('price', 0));
            if (0 <= $price) {
                $slice->setPrice($price);
            } else {
                $messages[] = $this->getTranslator()->trans(
                    'The price value is not valid',
                    [],
                    SoColissimo::DOMAIN
                );
            }

            if (0 === count($messages)) {
                $slice->save();
                $messages[] = $this->getTranslator()->trans(
                    'Your slice has been saved',
                    [],
                    SoColissimo::DOMAIN
                );

                $responseData['success'] = true;
                $responseData['slice'] = $slice->toArray(TableMap::TYPE_STUDLYPHPNAME);
            }
        } catch (\Exception $e) {
            $message[] = $e->getMessage();
        }

        $responseData['message'] = $messages;

        return $this->jsonResponse(json_encode($responseData));
    }

    protected function getFloatVal($val, $default = -1)
    {
        if (preg_match("#^([0-9\.,]+)$#", $val, $match)) {
            $val = $match[0];
            if (strstr($val, ",")) {
                $val = str_replace(".", "", $val);
                $val = str_replace(",", ".", $val);
            }
            $val = floatval($val);

            return $val;
        }

        return $default;
    }

    public function deleteSliceAction()
    {
        $response = $this->checkAuth([], ['socolissimo'], AccessManager::DELETE);

        if (null !== $response) {
            return $response;
        }

        $this->checkXmlHttpRequest();

        $responseData = [
            "success" => false,
            "message" => '',
            "slice" => null
        ];

        $response = null;

        try {
            $requestData = $this->getRequest()->request;

            if (0 !== $id = intval($requestData->get('id', 0))) {
                $slice = SocolissimoPriceQuery::create()->findPk($id);
                $slice->delete();
                $responseData['success'] = true;
            } else {
                $responseData['message'] = $this->getTranslator()->trans(
                    'The slice has not been deleted',
                    [],
                    SoColissimo::DOMAIN
                );
            }
        } catch (\Exception $e) {
            $responseData['message'] = $e->getMessage();
        }

        return $this->jsonResponse(json_encode($responseData));
    }
}
