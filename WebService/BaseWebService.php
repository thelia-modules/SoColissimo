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

namespace SoColissimo\WebService;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;

/**
 * Class BaseWebService
 * @package SoColissimo\WebService
 * @author Thelia <info@thelia.net>
 *
 * @method BaseWebService getSoap()
 * @method BaseWebService setSoap(\SoapClient $soap)
 * @method BaseWebService getWebFunction()
 * @method BaseWebService setWebFunction($value)
 */
abstract class BaseWebService
{
    /** @var \SoapClient */
    protected $soap;

    /** @var null */
    protected $web_function;

    /**
     * @param $wsdl
     * @param null $web_function
     */
    public function __construct($wsdl, $web_function = null)
    {
        $this->soap = new \SoapClient($wsdl);
        $this->web_function=$web_function;
    }

    /**
     * @param $name
     * @return mixed|string
     */
    private function getProprietyRealName($name)
    {
        $propriety_real_name = substr($name, 3);

        if (preg_match("#^[A-Z]$#", substr($propriety_real_name, 0, 1))) {
            $propriety_real_name = strtolower(substr($propriety_real_name, 0, 1)).substr($propriety_real_name, 1);
            $propriety_real_name = preg_replace_callback(
                "#([A-Z])#",
                function ($match) {
                    return strtolower("_".$match[0]);
                },
                $propriety_real_name
            );
        }

        return $propriety_real_name;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \BadFunctionCallException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func($this->$name, $arguments);
        } else {
            if (substr($name, 0, 3) === "get") {
                if (!empty($arguments)) {
                    throw new InvalidArgumentException("The function " . $name . " in " . get_class($this) . " doesn't take any argument.");
                }

                $real_name = $this->getProprietyRealName($name);
                if (property_exists($this, $real_name)) {
                    return $this->$real_name;
                }

            } elseif (substr($name, 0, 3) === "set") {
                if (count($arguments) !== 1) {
                    throw new InvalidArgumentException("The function " . $name . " in " . get_class($this) . "  take only one argument.");
                }

                $real_name = $this->getProprietyRealName($name);
                $this->$real_name = $arguments[array_keys($arguments)[0]];

                return $this;
            }

            throw new \BadFunctionCallException("The function ".$name." doesn't exist in ".get_class($this));
        }
    }

    /**
     * @return mixed
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     */
    public function exec()
    {
        $function = $this->web_function;
        $response = $this->soap->$function($this->getArgs());

        if ($this->isError($response)) {
            throw new InvalidArgumentException($this->getError($response));
        }

        return $this->getFormattedResponse($response);
    }

    /**
     * @return array of web function args
     */
    public function getArgs()
    {
        $args= $this->getSoapNames($this->getThisVars());

        /*
         * Clear array
         */
        foreach ($args as $key => $value) {
            if (empty($value)) {
                unset($args[$key]);
            }
        }

        return $args;
    }

    /**
     * @return array
     */
    protected function getThisVars()
    {
        $this_class_vars = get_object_vars($this);
        $base_class_vars = get_class_vars("\\SoColissimo\\WebService\\BaseWebService");
        $pks = array_diff_key($this_class_vars, $base_class_vars);

        return $pks;
    }

    /**
     * @param  array $names
     * @return array
     */
    protected function getSoapNames(array $names)
    {
        foreach ($names as $name => $value) {
            $real_name = $this->getSoapName($name);
            $names[$real_name] = $value;
            if ($name !== $real_name) {
                unset($names[$name]);
            }
        }

        return $names;
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function getSoapName($name)
    {
        return preg_replace_callback(
            "#_([a-z]{1})#",
            function ($match) {
                return strtoupper($match[1]);
            },
            $name
        );
    }

    /**
     * @return bool
     */
    abstract public function isError(\stdClass $response);

    /**
     * @return string
     */
    abstract public function getError(\stdClass $response);

    /**
     * @return something
     */
    abstract public function getFormattedResponse(\stdClass $response);
}
