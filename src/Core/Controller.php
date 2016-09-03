<?php

namespace App\Core;

class Controller {
    
    private $controller;
    private $action;
    private $params = [];

    public function __construct() 
    {
        
    }

    public function run($URI) 
    {
        $this->setRequestEnvironment($URI);

        $controller = $this->getController();
        $action = $this->getAction();

        try {

            $namespace = \DEFAULT_NAMESPACE . '\\'. $controller . '\\'. $controller .'Controller';

            if(empty($controller)){
                return $this->main();
            }

            if(\class_exists($namespace) === false){
               throw new \RuntimeException("Controller not found");
            }

            $controllerClass = new $namespace();

            if(\method_exists($controllerClass, $action) === false) {
                throw new \RuntimeException("Controller Action not found");
            }

            return $controllerClass->setRequestParams($this->getParams())->setController($this)->{$action}();
        } catch (\RuntimeException $e) {
            return $this->jsonError($e->getMessage());
        } catch (\Exception $e) {
            print "<pre>".$e->getTraceAsString()."</pre>";
            return $this->jsonError($e->getMessage());
        }
    }
    
    public function main()
    {
        return "<h1>HTTP/1.1 200 OK</h1>";
    }
    
    private function setRequestEnvironment($URI)
    {
        $exURI = \explode('/', $URI);
        list($controller, $action) = $exURI;
        
        $this->setController($controller);
        $this->setAction($action);

        if (\count($exURI) > 2 and ! empty($exURI[2])) {
            $i = 0;
            $offset = 0;

            $queryString = \array_slice($exURI, 2);
            $params = [];
            while ($offset < \count($queryString)){
                list($key, $val) = (\array_slice($queryString, $offset, 2));
                if(\strlen($key) > 0){
                    $params[$key] = $val;
                }
                $offset += 2;
            }
            $this->setParams($params);
        }        
    }

    public function jsonSucess($message = '')
    {
        return \json_encode(array('sucess' => 'true', 'message' => $message));
    }

    public function jsonError($error)
    {
        return \json_encode(array('sucess' => 'false', 'message' => $error));
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction() 
    {
        return $this->action;
    }

    public function getParams() 
    {
        return $this->params;
    }

    private function setController($controller) 
    {
        $this->controller = $controller;
        return $this;
    }

    private function setAction($action) 
    {
        $this->action = (empty($action) ? 'main' : $action);
        return $this;
    }

    private function setParams(array $params) 
    {
        $this->params = $params;
        return $this;
    }
}