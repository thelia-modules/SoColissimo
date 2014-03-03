<?php
namespace SoColissimo\Model;

interface ConfigInterface
{
    // Data access
    public function write($file=null);
    public static function read($file=null);

    // variables setters
    /*
     * @return SoColissimo\Model\ConfigInterface
     */
    public function setAccountNumber($account_number);

    /*
     * @return SoColissimo\Model\ConfigInterface
     */
    public function setPassword($password);

}
