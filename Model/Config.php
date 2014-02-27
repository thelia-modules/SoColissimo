<?php

namespace SoColissimo\Model;

use Thelia\Core\Translation\Translator;

class Config implements ConfigInterface {
    protected $account_number=null;
    protected $password=null;

    public function __construct()
    {
        $config=null;
        try {
            $config=$this->read();
        } catch(\Exception $e) {}
        if($config !== null) {
            foreach($config as $key=>$val) {
                try {
                    $this->__set($key,$val);
                } catch(\Exception $e) {}
            }
        }
    }

    public function write($file=null) {
        $path = __DIR__."/../".$file;
        if((file_exists($path) ? is_writable($path):is_writable(__DIR__."/../Config/"))) {
            $vars= get_object_vars($this);
            $cond = true;
            foreach($vars as $key=>$var)
                $cond &= !empty($var);
            if($cond) {
                $file = fopen($path, 'w');
                fwrite($file, json_encode($vars));
                fclose($file);
            }
        } else {
            throw new \Exception(Translator::getInstance()->trans("Can't write file ").$file.". ".
                Translator::getInstance()->trans("Please change the rights on the file and/or directory."));

        }
    }
    /**
     * @return array
     */
    public static function read($file=null) {
        $path = __DIR__."/../".$file;
        $ret = null;
        if(is_readable($path)) {
            $json = json_decode(file_get_contents($path), true);
            if($json !== null) {
                $ret = $json;
            } else {
                throw new \Exception(Translator::getInstance()->trans("Can't read file ").$file.". ".
                    Translator::getInstance()->trans("The file is corrupted."));
            }
        } elseif(!file_exists($path)) {
            throw new \Exception(Translator::getInstance()->trans("The file ").$file.
                                Translator::getInstance()->trans(" doesn't exist. You have to create it in order to use this module. Please see module's configuration page."));
        } else {
            throw new \Exception(Translator::getInstance()->trans("Can't read file ").$file.". ".
                                Translator::getInstance()->trans("Please change the rights on the file."));

        }
        return $ret;
    }

    /**
     * @param $account_number
     * @return $this
     */
    public function setAccountNumber($account_number)
    {
        $this->account_number = $account_number;
        return $this;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

}

