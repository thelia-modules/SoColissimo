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

namespace SoColissimo\Tests\WebService;

/**
 * Class BaseSoColissimoWebServiceTest
 * @package SoColissimo\Tests\WebService
 * @author Thelia <info@thelia.net>
 */
class BaseSoColissimoWebServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testCall()
    {
        $instance = new \SoColissimo\WebService\FindByAddress();
        $this->assertInstanceOf("\\SoapClient", $instance->getSoap());
    }

    /**
     * @expectedException \BadFunctionCallException
     */
    public function testBadFunctionCallException()
    {
        $instance = new \SoColissimo\WebService\FindByAddress();

        $instance->FooBar();
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testGetInvalidArgumentException()
    {
        $instance = new \SoColissimo\WebService\FindByAddress();

        $instance->getFoo("bar");
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testSetInvalidArgumentException()
    {
        $instance = new \SoColissimo\WebService\FindByAddress();

        $instance->setFoo();
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testSetInvalidArgumentException2()
    {
        $instance = new \SoColissimo\WebService\FindByAddress();

        $instance->setFoo("apple", "banana");
    }

    /**
     * @expectedException \BadFunctionCallException
     */
    public function testGetBadFunctionCallException()
    {
        $instance = new \SoColissimo\WebService\FindByAddress();

        $instance->getFoo();
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testExceptFindByAddressExec()
    {
        $instance = new \SoColissimo\WebService\FindByAddress();

        $instance
            ->setAddress("17 rue des gras")
            ->setZipCode("63000")
            ->setCity("Clermont-Ferrand")
            ->setCountryCode("FR")
            ->setFilterRelay("1")
            ->setRequestId("1234")
            ->setLang("FR")
            ->setOptionInter("1")
            ->setShippingDate(date("d/m/Y"))
            ->setWeight("20")
            ->setAccountNumber("123456")
            ->setPassword(utf8_encode(base64_decode("VGEgbehyZSBlbiBzbGlwIDwz")))
        ;
        $instance->exec();
    }

    public function testFindByAddressExec()
    {
        $instance = new \SoColissimo\WebService\FindByAddress();

        $instance
            ->setAddress("17 rue des gras")
            ->setZipCode("63000")
            ->setCity("Clermont-Ferrand")
            ->setCountryCode("FR")
            ->setFilterRelay("1")
            ->setRequestId("1234")
            ->setLang("FR")
            ->setOptionInter("1")
            ->setShippingDate(date("d/m/Y"))
            ->setWeight("20")
            ->setAccountNumber("800734")
            ->setPassword("nass014")
        ;
        $response = $instance->exec();

        $this->assertTrue(is_array($response));
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testExceptFindByIdExec()
    {
        $instance = new \SoColissimo\WebService\FindById();

        $instance
            ->setId("002572")
            ->setLangue("FR")
            ->setDate(date("d/m/Y"))
            ->setWeight("20")
            ->setAccountNumber("123456")
            ->setPassword(utf8_encode(base64_decode("VGhpcyBpcyBvcGVuc3R1ZGlv")))
        ;
        $instance->exec();
    }

    public function testFindByIdExec()
    {
        $instance = new \SoColissimo\WebService\FindById();

        $instance
            ->setId("002572")
            ->setLangue("FR")
            ->setDate(date("d/m/Y"))
            ->setWeight("20")
            ->setAccountNumber("800734")
            ->setPassword("nass014")
        ;
        $response = $instance->exec();

        $this->assertInstanceOf("\\stdclass", $response);
    }
}
