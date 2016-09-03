<?php
namespace App\Telegram;
use ChatWatch\EntityMaster;

class TelegramClass
{
    private $entityManager;
    private $entityMaster;
    private $params;

    public function __construct()
    {
        $this->entityMaster = new EntityMaster();
        $this->entityManager = $this->entityMaster->getEntityManager();
    }
    
    public function setNewUpdates(array $payload)
    {
        $message    = $payload['message'];
        $user       = $message['from'];
        $chat       = $message['chat'];

        $this->entityManager->beginTransaction();
        
        $chatRepository = $this->getChat($chat);
        $userRepository = $this->getUser($user);

        $this->setMessage($message, $chatRepository, $userRepository);

        $this->entityManager->commit();
        
        return true;
    }
    
    /**
    * @param array $chat Chat info
    * @return Entities\Chat Chat Entity
    */
    public function getChat(array $chat)
    {
        $chatId         = $chat['id'];
        $repository     = $this->entityManager->getRepository('\Entities\Chat');
        $chatRepository = $repository->findBy(['chatId' => $chatId]);

        if(isset($chatRepository[0]) and \get_class($chatRepository[0]) === 'Entities\Chat') {
            return $chatRepository[0];
        } else {

            $chatInsert = new \Entities\Chat();
            $chatInsert->setChatId($chatId)
                ->setTitle($chat['first_name'])
                ->setType($chat['type']);
            $this->entityMaster->persist($chatInsert);

            return $chatInsert;
        }
    }

    /**
    * @param array $user User info
    * @return Entities\User Chat Entity
    */
    public function getUser(array $user)
    {
        $userId         = $user['id'];
        $repository     = $this->entityManager->getRepository('\Entities\User');
        $userRepository = $repository->findBy(['userId' => $userId]);

        if(isset($userRepository[0]) and \get_class($userRepository[0]) === 'Entities\User') {
            return $userRepository[0];
        } else {

            $userInsert = new \Entities\User();
            $userInsert->setUserId($userId)
                ->setFirstName($user['first_name']);
            $this->entityMaster->persist($userInsert);

            return $userInsert;
        }
    }

    /**
    * @param array $message Message info
    * @param \Entities\Chat $chat Chat Entity Object
    * @param \Entities\User $user Entity Object
    * @return bool
    */
    public function setMessage(array $message, \Entities\Chat $chat, \Entities\User $user)
    {
        $messageId          = $message['message_id'];
        $repository         = $this->entityManager->getRepository('\Entities\Message');
        $messageRepository  = $repository->findBy(['messageId' => $messageId]);

        if(isset($messageRepository[0]) and \get_class($messageRepository[0]) === 'Entities\Message') {
            return true;
        } else {

            $messageInsert = new \Entities\Message();
            $messageInsert->setMessageId($messageId)
                ->setDate(new \DateTime(\date('Y-m-d H:i:s', $message['date'])))
                ->setText($message['text'])
                ->setChatId($chat)
                ->setUserId($user);
            $this->entityMaster->persist($messageInsert);
            return true;
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


}