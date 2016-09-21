<?php 
namespace App\Sender;

class TelegramVO
{
    /** @var string API URL wheter http://wapi.phphive.info/api/message/send.php to send or http://wapi.phphive.info/api/message/receive.php to read messages*/
    private $urlAPI   = 'https://api.telegram.org/';
    
    /** @var string Telegram Bot Token, Get it from https://core.telegram.org/bots */
    private $token      = NULL;
    
    private $chatId     = NULL;
    
    private $msg        = NULL;
    
    private $image      = NULL;
    
    private $audio      = NULL;
    
    private $sticker    = NULL;
    
    private $action     = NULL;
    
    public function getUrlAPI() 
    {
        return $this->urlAPI;
    }
        
    public function getToken() 
    {
        return $this->token;
    }

    public function getChatId() 
    {
        return $this->chatId;
    }

    public function getMsg() 
    {
        return $this->msg;
    }

    public function getImage() 
    {
        return $this->image;
    }

    public function getAudio() 
    {
        return $this->audio;
    }

    public function getSticker() 
    {
        return $this->sticker;
    }
        
    public function getAction() 
    {
        return $this->action;
    }

    protected function setUrlAPI($urlAPI)
    {
        $this->urlAPI = $urlAPI;
    }
    
    public function setToken($token) 
    {
        $this->token = $token;
    }
    
    public function setChatId($chatId) 
    {
        $this->chatId = $chatId;
    }

    public function setMsg($msg) 
    {
        $this->msg = $msg;
    }

    public function setImage($image) 
    {
        if(\is_readable($image) === true){
            $this->image = \fopen($image, 'r');
        } else {
            $this->image = $image;
        }
        return $this;
    }

    public function setAudio($audio) 
    {
        if(\is_readable($audio) === true){
            $this->audio = \fopen($audio, 'r');
        } else {
            $this->audio = $audio;
        }
        return $this;
    }

    public function setSticker($sticker) 
    {
        if(\is_readable($sticker) === true){
            $this->sticker = \fopen($sticker, 'r');
        } else {
            $this->sticker = $sticker;
        }
        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }
}