<?php
namespace App\Sender;
use ChatWatch\Config;

class Telegram extends TelegramVO 
{
    private $confs;

    public function __construct($token = NULL)
    {
        $this->confs        = (new Config())->getConf('telegram');

        if(\is_null($token) === false){
            $this->setToken($token);
        } else {
            $this->setToken($this->confs['botToken']);
        }

        return $this;
    }

    public function sendMessage($msg, $chatId = NULL, $parse_mode = 'HTML')
    {
        if(\is_null($chatId) === false){
            $this->setChatId($chatId);
        }

        return $this->setMsg($msg)
            ->setAction('sendMessage')
            ->execute(
            [
                'chat_id'                       => $this->getChatId(),
                'text'                          => $this->getMsg(),
                'parse_mode'                    => $parse_mode,
                'disable_web_page_preview'      => 'true'
            ]
        );
    }

    public function sendPhoto($fileSource, $caption = NULL, $chatId = NULL)
    {
        if(\is_null($chatId) === false){
            $this->setChatId($chatId);
        }

        return $this->setAction('sendPhoto')
            ->setImage($fileSource)
            ->execute(
            [
                'chat_id'                       => $this->getChatId(),
                'photo'                         => $this->getImage(),
                'caption'                       => $caption,
                'parse_mode'                    => 'HTML',
                'disable_web_page_preview'      => 'true'
            ]
        );
    }
    public function sendSticker($sticker, $chatId = NULL)
    {
        if(\is_null($chatId) === false){
            $this->setChatId($chatId);
        }

        return $this->setAction('sendSticker')
            ->setSticker($sticker)
            ->execute(
            [
                'chat_id'                       => $this->getChatId(),
                'sticker'                       => $this->getSticker(),
                'parse_mode'                    => 'HTML',
                'disable_web_page_preview'      => 'true'
            ]
        );
    }
    
    public function postsendAudio($audio, $chatId = NULL)
    {
        if(\is_null($chatId) === false){
            $this->setChatId($chatId);
        }

        return $this->setAction('sendAudio')
            ->setAudio($audio)
            ->execute(
            [
                'chat_id'                       => $this->getChatId(),
                'sticker'                       => $this->getAudio(),
                'parse_mode'                    => 'HTML',
                'disable_web_page_preview'      => 'true'
            ]
        );
    }

    public function readMessages()
    {
        return $this->setAction('getUpdates')
            ->execute([]);
    }
    
    private function execute($formParams)
    {
        $multipartData = [];
        foreach ($formParams as $key=>$param){
            $multipartData[] = [
                'name'      => $key,
                'contents'  => $param,
            ];
        }

        $client     = new \GuzzleHttp\Client(['verify' => false]);
        $response   = $client->request('POST', $this->getUrlAPI() . $this->getToken() .'/'. $this->getAction(), [
            'multipart' => $multipartData
        ]);

        return \json_decode($response->getBody()->getContents(), true);
    }    
    
    /**
     * 
     * SOBRECARGA DE MÉTODOS
     * 
     */
    public function setUrlAPI($urlAPI)
    {
        parent::setUrlAPI($urlAPI);
        return $this;
    }
    
    public function setToken($token) 
    {
        parent::setToken($token);
        return $this;
    }
    
    public function setChatId($chatId) 
    {
        parent::setChatId($chatId);
        return $this;
    }

    public function setMsg($msg) 
    {
        parent::setMsg($msg);
        return $this;
    }

    public function setAction($action)
    {
        parent::setAction($action);
        return $this;
    }
}