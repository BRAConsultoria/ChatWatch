<?php
namespace App\Messenger;
use ChatWatch\Config;

class MessengerController implements \App\Core\ControllerInterface
{
    /**
     * @var \App\Core\Controller Main Controller
     */
    private $controller;

    /**
    * @var array conf bot information
    */
    private $conf;
    
    protected $requestParams = [];
    
    private $payload;
 
    private $class;

    public function __construct() 
    {
        $this->class = new MessengerClass();
        $this->conf = (new Config())->getConf('messenger');
    }
    
    public function main()
    {
        return \json_encode(['sucess' => false, 'message' => "Not implemeted"]);
    }
    
    public function getRequestParams()
    {
        return $this->requestParams;
    }
   
    public function setRequestParams(array $requestParams)
    {
        $this->requestParams = $requestParams;
        return $this;
    }

    public function setController(\App\Core\Controller $controller)
    {
        $this->controller = $controller;
        return $this;
    }

    public function getPayload() 
    {
        return $this->payload;
    }

    public function setPayload($payload) 
    {
        $this->payload = $payload;
        return $this;
    }
    
    /**
    * @route("GET")
    */
    public function chats()
    {
        $params = $this->requestParams;
        if(isset($params['appToken']) and $params['appToken'] === $this->conf['appToken']){
            $data = $this->class->setParams($this->getRequestParams())->getAvailableChats();
            if(empty($this->class->getError())) {
                return $this->controller->jsonSucessData($data);
            } else {
                (new \App\Utils\Log())->logWrite(["payload" => $this->getPayload(), "error" => $this->class->getError(), "params" => $this->getRequestParams()]);
                $error = ($this->class->getError() ?: 'Undefined error.');
                return $this->controller->jsonError($error);
            }
        } else {
            return ($this->controller->jsonError("401 - Unauthorized"));
        }
    }

    /**
    * @route("POST")
    */
    public function sendMessage()
    {
        $params = $this->requestParams;
        if(isset($params['appToken']) and $params['appToken'] === $this->conf['appToken']){
            if($this->class->setParams($this->getRequestParams())->sendMessage($this->getPayload()) === true) {
                return $this->controller->jsonSucess("Message sent");
            } else {
                (new \App\Utils\Log())->logWrite(["payload" => $this->getPayload(), "error" => $this->class->getError(), "params" => $this->getRequestParams()]);
                $error = ($this->class->getError() ?: 'Undefined error.');
                return $this->controller->jsonError($error);
            }
        } else {
            return ($this->controller->jsonError("401 - Unauthorized"));
        }
    }
}