<?php
namespace App\Messenger;
use ChatWatch\EntityMaster;
use App\Sender\Telegram;
use ChatWatch\Domain\Entities\Chat;

class MessengerClass
{
    private $entityManager;
    private $entityMaster;
    private $params;
    
    private $error;
    
    private $telegram;

    public function __construct()
    {
        $this->entityMaster     = new EntityMaster();
        $this->entityManager    = $this->entityMaster->getEntityManager();
        $this->telegram         = new Telegram();
    }
    
    public function getAvailableChats()
    {
        $repository     = $this->entityManager->getRepository(Chat::class);
        $chatRepository = $repository->findAll();
        $data           = [];

        foreach ($chatRepository as $chat){
            $data[] = [
                'id'    => $chat->getId(),
                'title' => $chat->getTitle(),
            ];
        }
        return $data;
    }
    
    public function sendMessage($payload) 
    {
        $data = \json_decode($payload, true);
        //user submited data
        $id         = $data['id'];
        $message    = $data['message'];
        
        $chat   = $this->entityManager->getRepository(Chat::class)->find($data['id']);
        $chatId = $chat->getChatId();
        if(!empty($chatId)){
            $this->telegram->sendMessage($message, $chatId, 'markdown');
            return true;
        } else {
            $this->setError("Invalid chatId");
            return false;
        }
    }
    
    private function trataPayload($payload) 
    {
        $expPayload = \explode("\n", $payload);
        if(\count($expPayload) === 4){
            return $expPayload[2];
        } else {
            return $payload;
        }
    }
    
    public function getParams()
    {
        return $this->params;
    }

    public function setParams(array $params) 
    {
        $this->params = $params;
        return $this;
    }

    public function getError() 
    {
        return $this->error;
    }

    private function setError($error) 
    {
        $this->error = $error;
        return $this;
    }
}