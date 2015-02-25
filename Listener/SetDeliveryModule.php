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

namespace SoColissimo\Listener;

use SoColissimo\WebService\FindById;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\ConfigQuery;
use Thelia\Model\OrderAddressQuery;
use SoColissimo\SoColissimo;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Model\AddressQuery;
use SoColissimo\Model\AddressSocolissimoQuery;
use SoColissimo\Model\AddressSocolissimo;
use SoColissimo\Model\OrderAddressSocolissimo;

/**
 * Class SetDeliveryModule
 * @package SoColissimo\Listener
 * @author Thelia <info@thelia.net>
 */
class SetDeliveryModule implements EventSubscriberInterface
{
    /** @var Request */
    protected $request;

    /** @var ContainerAwareEventDispatcher */
    protected $dispatcher;

    /**
     * @param Request $request
     */
    public function __construct(Request $request, ContainerAwareEventDispatcher $dispatcher)
    {
        $this->request = $request;

        $this->dispatcher = $dispatcher;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param $id
     * @return bool
     */
    protected function check_module($id)
    {
        return $id == SoColissimo::getModCode();
    }

    public function isModuleSoColissimo(OrderEvent $event)
    {
        if ($this->check_module($event->getDeliveryModule())) {
            $request = $this->getRequest();

            $pr_code = $request->get('socolissimo_code');
            $dom = $request->get('domicile');

            $request->getSession()->set('SoColissimoDeliveryId', 0);
            $request->getSession()->set('SoColissimoDomiciile', 0);

            if ($dom) {
                $request->getSession()->set('SoColissimoDomiciile', 1);

            } elseif (!empty($pr_code)) {
                $req = new FindById();

                $req->setId($pr_code)
                    ->setLangue("FR")
                    ->setDate(date("d/m/Y"))
                    ->setAccountNumber(ConfigQuery::read('socolissimo_login'))
                    ->setPassword(ConfigQuery::read('socolissimo_pwd'))
                ;

                $response = $req->exec();

                $customer_name = AddressQuery::create()
                    ->findPk($event->getDeliveryAddress());

                $address = AddressSocolissimoQuery::create()
                    ->findPk($event->getDeliveryAddress());

                $request->getSession()->set('SoColissimoDeliveryId', $event->getDeliveryAddress());
                if ($address === null) {
                    $address = new AddressSocolissimo();
                    $address->setId($event->getDeliveryAddress());
                }

                // France Métropolitaine
                $address->setCode($pr_code)
                    ->setType($response->typeDePoint)
                    ->setCompany($response->nom)
                    ->setAddress1($response->adresse1)
                    ->setAddress2($response->adresse2)
                    ->setAddress3($response->adresse3)
                    ->setZipcode($response->codePostal)
                    ->setCity($response->localite)
                    ->setFirstname($customer_name->getFirstname())
                    ->setLastname($customer_name->getLastname())
                    ->setTitleId($customer_name->getTitleId())
                    ->setCountryId($customer_name->getCountryId())
                    ->save();
            }
        }
    }

    public function updateDeliveryAddress(OrderEvent $event)
    {
        if ($this->check_module($event->getOrder()->getDeliveryModuleId())) {
            $request = $this->getRequest();

            $tmp_address = AddressSoColissimoQuery::create()
                ->findPk($request->getSession()->get('SoColissimoDeliveryId'));

            if ($request->getSession()->get('SoColissimoDomiciile') == 1 || $tmp_address === null) {
                $savecode = new OrderAddressSocolissimo();
                $savecode->setId($event->getOrder()->getDeliveryOrderAddressId())
                    ->setCode(0)
                    ->setType('DOM')
                    ->save();
            } else {
                $savecode = new OrderAddressSocolissimo();
                $savecode->setId($event->getOrder()->getDeliveryOrderAddressId())
                    ->setCode($tmp_address->getCode())
                    ->setType($tmp_address->getType())
                    ->save();

                $update = OrderAddressQuery::create()
                    ->findPK($event->getOrder()->getDeliveryOrderAddressId())
                    ->setCompany($tmp_address->getCompany())
                    ->setAddress1($tmp_address->getAddress1())
                    ->setAddress2($tmp_address->getAddress2())
                    ->setAddress3($tmp_address->getAddress3())
                    ->setZipcode($tmp_address->getZipcode())
                    ->setCity($tmp_address->getCity())
                    ->save();

                $tmp_address->delete();
            }

        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            TheliaEvents::ORDER_SET_DELIVERY_MODULE => array('isModuleSoColissimo', 64),
            TheliaEvents::ORDER_BEFORE_PAYMENT => array('updateDeliveryAddress', 256)

        );
    }
}
