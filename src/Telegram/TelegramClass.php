<?php
namespace App\Telegram;
use ChatWatch\EntityMaster;

class TelegramClass
{
    private $entityManager;
    private $entityMaster;
    private $params;
    
    private $error;
    
    private $fileStorage;

    public function __construct()
    {
        $this->entityMaster     = new EntityMaster();
        $this->entityManager    = $this->entityMaster->getEntityManager();
        $this->fileStorage      = new FileStorage();
    }
    
    /**
    * @param array $payload Telegram JSON-serialized Update object
    * @return bool
    */    
    public function setNewUpdates(array $payload)
    {
        $file       = false;

        $message    = $payload['message'];
        $user       = $message['from'];
        $chat       = $message['chat'];

        if($this->isChatIgnored($chat['id']) === false) {

            if(isset($message['text']) === false){
                $fileStored = $this->fileStorage->getFiles($payload['message']);
                if($fileStored === false){
                    $this->setError($this->fileStorage->getError());
                    return false;
                } else {
                    $file = true;
                }
            }

            $this->entityManager->beginTransaction();

            $chatRepository = $this->getChat($chat);
            $userRepository = $this->getUser($user);

            if($file === true){
                $message['text'] = 'file upload';
                $message['file'] = true;
            }

            $message = $this->setMessage($message, $chatRepository, $userRepository);
            if($message === false){
                return false;
            }

            if($file === true){
                $this->getFile($fileStored, $message);
            }

            $this->entityManager->commit();
            return true;
        } else {
            $this->setError("The chat id '". $chat['id'] ."' is set to ignored.");
            return false;
        }

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

            $title = ($chat['type'] === 'private' ? $chat['first_name'] : $chat['title']);
            $chatInsert = new \Entities\Chat();
            $chatInsert->setChatId($chatId)
                ->setTitle($title)
                ->setType($chat['type'])
                ->setUserName((isset($chat['username']) ? $chat['username'] : NULL))
                ->setIgnored(false);
            $this->entityMaster->persist($chatInsert);

            return $chatInsert;
        }
    }

    /**
    * @param array $user User info
    * @return Entities\User User Entity
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
                ->setFirstName($user['first_name'])
                ->setLastName((isset($user['last_name']) ? $user['last_name'] : NULL))
                ->setUserName((isset($user['username']) ? $user['username'] : NULL));
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
            $this->setError("Message aleady stored. message_id: ". $messageId);
            return false;
        } else {

            $messageInsert = new \Entities\Message();
            $messageInsert->setMessageId($messageId)
                ->setDate(new \DateTime(\date('Y-m-d H:i:s', $message['date'])))
                ->setText(\utf8_decode($message['text']))
                ->setChatId($chat)
                ->setUserId($user)
                ->setFile(isset($message['file']));
            $this->entityMaster->persist($messageInsert);
            return $messageInsert;
        }
    }
    
    /**
    * @param array $file File info
    * @return Entities\File File Entity
    */
    public function getFile(array $file, \Entities\Message $message)
    {
        $fileId         = $file['id'];
        $repository     = $this->entityManager->getRepository('\Entities\File');
        $fileRepository = $repository->findBy(['fileId' => $fileId]);

        if(isset($fileRepository[0]) and \get_class($fileRepository[0]) === 'Entities\File') {
            $this->setError("File aleady stored. file_id: ". $fileId);
            return false;
        } else {

            $fileInsert = new \Entities\File();
            $fileInsert->setFileId($fileId)
                ->setFileName($file['name'])
                ->setTelegramType($file['type'])
                ->setfileType($file['mime-type'])
                ->setMd5($file['md5'])
                ->setMessageId($message);
            $this->entityMaster->persist($fileInsert);

            return $fileInsert;
        }
    }

    public function isChatIgnored($chatId) 
    {
        $repository     = $this->entityManager->getRepository('\Entities\Chat');
        $chatRepository = $repository->findBy(['chatId' => $chatId]);

        if(isset($chatRepository[0]) and \get_class($chatRepository[0]) === 'Entities\Chat') {
            return ($chatRepository[0]->getIgnored() ? true : false);
        } else {
            return false;
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