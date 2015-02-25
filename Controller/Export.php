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

use Propel\Runtime\ActiveQuery\Criteria;
use SoColissimo\Form\ExportOrder;
use SoColissimo\Format\CSV;
use SoColissimo\Format\CSVLine;
use SoColissimo\Model\OrderAddressSocolissimoQuery;
use SoColissimo\SoColissimo;
use Symfony\Component\Config\Definition\Exception\Exception;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Model\Base\CountryQuery;
use Thelia\Model\ConfigQuery;
use Thelia\Model\CustomerTitleI18nQuery;
use Thelia\Model\OrderAddressQuery;
use Thelia\Model\OrderQuery;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Model\OrderStatus;
use Thelia\Model\OrderStatusQuery;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;

/**
 * Class Export
 * @package SoColissimo\Controller
 * @author Thelia <info@thelia.net>
 */
class Export extends BaseAdminController
{
    const CSV_SEPARATOR = ";";

    const DEFAULT_PHONE = "0100000000";
    const DEFAULT_CELLPHONE = "0600000000";

    public function export()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('SoColissimo'), AccessManager::UPDATE)) {
            return $response;
        }

        $csv = new CSV(self::CSV_SEPARATOR);

        try {
            $form  = new ExportOrder($this->getRequest());
            $vform = $this->validateForm($form);

            // Check status_id
            $status_id = $vform->get("new_status_id")->getData();
            if (!preg_match("#^nochange|processing|sent$#", $status_id)) {
                throw new Exception("Bad value for new_status_id field");
            }

            $status = OrderStatusQuery::create()
                ->filterByCode(
                    array(
                        OrderStatus::CODE_PAID,
                        OrderStatus::CODE_PROCESSING,
                        OrderStatus::CODE_SENT
                    ),
                    Criteria::IN
                )
                ->find()
                ->toArray("code")
            ;

            $query = OrderQuery::create()
                ->filterByDeliveryModuleId(SoColissimo::getModCode())
                ->filterByStatusId(
                    array(
                        $status[OrderStatus::CODE_PAID]['Id'],
                        $status[OrderStatus::CODE_PROCESSING]['Id']),
                    Criteria::IN
                )
                ->find();

            // check form && exec csv
            /** @var \Thelia\Model\Order $order */
            foreach ($query as $order) {
                $value = $vform->get('order_'.$order->getId())->getData();

                // If checkbox is checked
                if ($value) {
                    /**
                     * Retrieve user with the order
                     */
                    $customer = $order->getCustomer();

                    /**
                     * Retrieve address with the order
                     */
                    $address = OrderAddressQuery::create()
                        ->findPk($order->getInvoiceOrderAddressId());

                    if ($address === null) {
                        throw new Exception("Could not find the order's invoice address");
                    }

                    /**
                     * Retrieve country with the address
                     */
                    $country = CountryQuery::create()
                        ->findPk($address->getCountryId());

                    if ($country === null) {
                        throw new Exception("Could not find the order's country");
                    }

                    /**
                     * Retrieve Title
                     */
                    $title = CustomerTitleI18nQuery::create()
                        ->filterById($customer->getTitleId())
                        ->findOneByLocale(
                            $this->getSession()
                                ->getAdminEditionLang()
                                    ->getLocale()
                        );

                    /**
                     * Get user's phone & cellphone
                     * First get invoice address phone,
                     * If empty, try to get default address' phone.
                     * If still empty, set default value
                     */
                    $phone = $address->getPhone();
                    if (empty($phone)) {
                        $phone = $customer->getDefaultAddress()->getPhone();

                        if (empty($phone)) {
                            $phone = self::DEFAULT_PHONE;
                        }
                    }

                    /**
                     * Cellp
                     */
                     $cellphone = $customer->getDefaultAddress()->getCellphone();

                    if (empty($cellphone)) {
                        $cellphone = self::DEFAULT_CELLPHONE;
                    }
                    /**
                     * Compute package weight
                     */
                    $weight = 0;
                    /** @var \Thelia\Model\OrderProduct $product */
                    foreach ($order->getOrderProducts() as $product) {
                        $weight+=(double) $product->getWeight();
                    }

                    /**
                     * Get relay ID
                     */
                    $relay_id = OrderAddressSocolissimoQuery::create()
                        ->findPk($order->getDeliveryOrderAddressId());

                    if ($relay_id === null) {
                        throw new Exception("Invalid order ".$order->getRef().", no relay id found");
                    }

                    /**
                     * Get store's name
                     */
                    $store_name = ConfigQuery::read("store_name");
                    /**
                     * Write CSV line
                     */
                    $csv->addLine(
                        CSVLine::create(
                            array(
                                $address->getFirstname(),
                                $address->getLastname(),
                                $address->getCompany(),
                                $address->getAddress1(),
                                $address->getAddress2(),
                                $address->getAddress3(),
                                $address->getZipcode(),
                                $address->getCity(),
                                $country->getIsoalpha2(),
                                $phone,
                                $cellphone,
                                $order->getRef(),
                                $title->getShort(),
                                $relay_id->getCode(),
                                $customer->getEmail(),
                                $weight,
                                $store_name,
                                $relay_id->getType()
                            )
                        )
                    );

                    /**
                     * Then update order's status if necessary
                     */
                    if ($status_id == "processing") {
                        $event = new OrderEvent($order);
                        $event->setStatus($status[OrderStatus::CODE_PROCESSING]['Id']);
                        $this->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $event);
                    } elseif ($status_id == "sent") {
                        $event = new OrderEvent($order);
                        $event->setStatus($status[OrderStatus::CODE_SENT]['Id']);
                        $this->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $event);
                    }

                }
            }
        } catch (\Exception $e) {
            return Response::create($e->getMessage(), 500);
        }

        return Response::create(
            $csv->parse(),
            200,
            array(
                "Content-Type"=>"application/csv-tab-delimited-table",
                "Content-disposition"=>"filename=export.csv"
            )
        );
    }
}
