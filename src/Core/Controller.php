<?php

namespace App\Core;
use App\Utils\Log;

class Controller {
    
    private $controller;
    private $action;
    private $params = [];
    
    private $log;

    public function __construct() 
    {
        $this->log = new Log();
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

            $route = $this->getControllerMethodAnnotationsRoute($namespace, $action);
            if(\filter_input(\INPUT_SERVER, 'REQUEST_METHOD') != $route){
                throw new \RuntimeException("Controller Action not found for requested route");
            }

            return $controllerClass->setRequestParams($this->getParams())->setController($this)->{$action}();
        } catch (\RuntimeException $e) {
            return $this->jsonError($e->getMessage());
        } catch (\Exception $e) {
            if(\DEBUG_MODE === true){
                $Error = "<pre>".$e->getTraceAsString()."</pre>";
            }
            if(\LOG_FILE === true and $this->log->getLogAvailable() === true){
                $this->log->logWrite([
                    'params'    => $this->params,
                    'msg'       => $e->getMessage(),
                    'trace'     => $e->getTraceAsString(),
                ]);
            }
            return $this->jsonError($e->getMessage() . $Error);
        }
    }
    
    public function main()
    {
        return "<h1>HTTP/1.1 200 OK</h1>";
    }
    
    private function getControllerMethodAnnotationsRoute($controllerNS, $method = 'main') 
    {
        $annotations = [];
        \preg_match_all('/@[route]{5}(.*?)\n/i', (new \ReflectionClass($controllerNS))->getMethod('setNewUpdates'), $annotations);

        if(isset($annotations[1][0])){
            return \strtoupper(\preg_replace('/[^a-z]/i', '', $annotations[1][0]));
        } else {
            throw new \Exception("Missing route annotation for controller method called");
        }
    }
    
    private function setRequestEnvironment($URI)
    {
        $exURI = \explode('/', $URI);

        if(\count($exURI) > 1){
            list($controller, $action) = $exURI;
        } else {
            $controller = $exURI[0];
            $action     = 'main';
        }

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
        $this->action = $action;
        return $this;
    }

    private function setParams(array $params) 
    {
        $this->params = $params;
        return $this;
    }
}