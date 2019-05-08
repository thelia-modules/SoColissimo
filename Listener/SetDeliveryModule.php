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

use SoColissimo\Utils\ColissimoCodeReseau;
use SoColissimo\WebService\FindById;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Delivery\DeliveryPostageEvent;
use Thelia\Core\Event\TheliaEvents;

use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Translation\Translator;
use Thelia\Model\Address;
use Thelia\Model\ConfigQuery;
use Thelia\Model\CountryQuery;
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
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    protected function check_module($id)
    {
        return $id == SoColissimo::getModCode();
    }

    private function callWebServiceFindRelayPointByIdFromRequest(Request $request)
    {
        $relay_infos = explode(':', $request->get('socolissimo_code'));

        $pr_code = $relay_infos[0];
        $relayType = count($relay_infos) > 1 ? $relay_infos[1] : null ;
        $relayCountryCode = count($relay_infos) > 2 ? $relay_infos[2] : null ;

        if (!empty($pr_code)) {
            $req = new FindById();

            $req->setId($pr_code)
                ->setLangue("FR")
                ->setDate(date("d/m/Y"))
                ->setAccountNumber(ConfigQuery::read('socolissimo_login'))
                ->setPassword(ConfigQuery::read('socolissimo_pwd'));

            // An argument "Code réseau" is now required in addition to the Relay Point Code to identify a relay point outside France.
            // This argument is optional for relay points inside France.
            if ($relayType != null && $relayCountryCode != null) {
                $codeReseau = ColissimoCodeReseau::getCodeReseau($relayCountryCode, $relayType);
                if ($codeReseau !== null) {
                    $req->setReseau($codeReseau);
                }
            }

            return $req->exec();
        } else {
            return null;
        }
    }

    public function isModuleSoColissimo(OrderEvent $event)
    {
        if ($this->check_module($event->getDeliveryModule())) {
            $request = $this->getRequest();

            $dom = $request->get('socolissimo-home');

            $request->getSession()->set('SoColissimoDeliveryId', 0);
            $request->getSession()->set('SoColissimoDomicile', 0);


            $customer_name = AddressQuery::create()
                ->findPk($event->getDeliveryAddress());

            $address = AddressSocolissimoQuery::create()
                ->findPk($event->getDeliveryAddress());

            $request->getSession()->set('SoColissimoDeliveryId', $event->getDeliveryAddress());
            if ($address === null) {
                $address = new AddressSocolissimo();
                $address->setId($event->getDeliveryAddress());
            }


            if ($dom) {
                $request->getSession()->set('SoColissimoDomicile', 1);

                $address->setCode(null)
                    ->setType("DOM")
                    ->setCompany($customer_name->getCompany())
                    ->setAddress1($customer_name->getAddress1())
                    ->setAddress2($customer_name->getAddress2())
                    ->setAddress3($customer_name->getAddress3())
                    ->setZipcode($customer_name->getZipcode())
                    ->setCity($customer_name->getCity())
                    ->setFirstname($customer_name->getFirstname())
                    ->setLastname($customer_name->getLastname())
                    ->setTitleId($customer_name->getTitleId())
                    ->setCountryId($customer_name->getCountryId())
                    ->setCellphone(null)
                    ->save();
            } else {
                $response = $this->callWebServiceFindRelayPointByIdFromRequest($request);

                if ($response !== null) {
                    $customer_name = AddressQuery::create()
                        ->findPk($event->getDeliveryAddress());

                    $address = AddressSocolissimoQuery::create()
                        ->findPk($event->getDeliveryAddress());

                    $request->getSession()->set('SoColissimoDeliveryId', $event->getDeliveryAddress());
                    if ($address === null) {
                        $address = new AddressSocolissimo();
                        $address->setId($event->getDeliveryAddress());
                    }

                    $relayCountry = CountryQuery::create()->findOneByIsoalpha2($response->codePays);
                    if ($relayCountry == null) {
                        $relayCountry = $customer_name->getCountry();
                    }

                    $address->setCode($response->identifiant)
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
                        ->setCountryId($relayCountry->getId())
                        ->save();
                } else {
                    $message = Translator::getInstance()->trans('No relay points were selected', [], SoColissimo::DOMAIN);
                    throw new \Exception($message);
                }
            }
            else {
                
                throw new \ErrorException("No relay chosen for Socolissimo delivery module");
            }
        }
    }

    public function updateDeliveryAddress(OrderEvent $event)
    {
        if ($this->check_module($event->getOrder()->getDeliveryModuleId())) {
            $request = $this->getRequest();

            if ($request->getSession()->get('SoColissimoDomicile') == 1) {
                $tmp_address = AddressSoColissimoQuery::create()
                    ->findPk($request->getSession()->get('SoColissimoDeliveryId'));

                if ($tmp_address === null) {
                    throw new \ErrorException("Got an error with So Colissimo module. Please try again to checkout.");
                }

                $savecode = new OrderAddressSocolissimo();
                $savecode->setId($event->getOrder()->getDeliveryOrderAddressId())
                    ->setCode(0)
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

            } else {
                $tmp_address = AddressSoColissimoQuery::create()
                    ->findPk($request->getSession()->get('SoColissimoDeliveryId'));

                if ($tmp_address === null) {
                    throw new \ErrorException("Got an error with So Colissimo module. Please try again to checkout.");
                }

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
            }
        }
    }

    public function getPostageRelayPoint(DeliveryPostageEvent $event)
    {
        if ($this->check_module($event->getModule()->getModuleModel()->getId())) {
            $request = $this->getRequest();

            $dom = $request->get('socolissimo-home');

            if (!$dom) {
                // If the relay point service was chosen, we store the address of the chosen relay point in
                //    the DeliveryPostageEvent in order for Thelia to recalculate the postage cost from this address.

                $response = $this->callWebServiceFindRelayPointByIdFromRequest($request);

                if ($response !== null) {
                    $address = new Address();

                    $relayCountry = CountryQuery::create()->findOneByIsoalpha2($response->codePays);

                    $address->setCompany($response->nom)
                        ->setAddress1($response->adresse1)
                        ->setAddress2($response->adresse2)
                        ->setAddress3($response->adresse3)
                        ->setZipcode($response->codePostal)
                        ->setCity($response->localite)
                        ->setCountryId($relayCountry->getId());

                    $event->setAddress($address);
                }
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
            TheliaEvents::ORDER_BEFORE_PAYMENT => array('updateDeliveryAddress', 256),
            TheliaEvents::MODULE_DELIVERY_GET_POSTAGE => array('getPostageRelayPoint', 257)
        );
    }
}
