<?php
namespace ChatWatch;

class Config
{
    public function getConf($index = NULL) 
    {
        if(\is_null($index) === true){
            return $this->conf();
        } else {
            $conf = $this->conf();
            return (isset($this->conf()[$index]) ? $conf[$index] : $conf);
        }
    }
    
    private function conf()
    {
        $conf = [
            'db' => [
                'driver'    => 'pdo_mysql',
                'user'      => 'root',
                'password'  => '',
                'dbname'    => 'chat_watch',
            ],
            'telegram' => [
                'botToken'  => 'bot<your-bot-token>',
                'botName'   => '<your-bot-name>',
            ]
        ];
        return $conf;
    }
}