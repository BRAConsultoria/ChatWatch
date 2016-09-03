<?php
namespace App\Core;

interface ControllerInterface
{
    /**
    * constructor
    */
    public function __construct();
    /**
    * main
    */
    public function main();

    /**
    * setRequestParams
    */
    public function setRequestParams(array $param);

    /**
    * setController
    */
    public function setController(\App\Core\Controller $requestParams);
    
    /**
    * @return array requestParams
    */
    public function getRequestParams();
    
}