<?php
namespace App\Utils;

class Log 
{
    private $logFile;
    private $logPath;
    private $logAvailable;

    public function __construct($logFile = 'log.txt')
    {
        $this->setLogFile($logFile);
        $this->setLogPath(\DIR_ROOT .'/log/');
        $fileLog = $this->logPath . $this->getLogFile();

        if(\defined('LOG_FILE_REQUIRED') and \LOG_FILE_REQUIRED === true) {

            if(\is_file($fileLog) === false or \is_readable($fileLog) === false){

                if(\is_dir($this->getLogPath()) === true){
                    if(\file_put_contents($fileLog, "\n") === false){
                        throw new \Exception("Log file couldn't be created.");
                    }
                } else {
                    throw new \Exception("Log path couldn't be created.");
                }
                $logAvailable = true;
            }
            $logAvailable = true;
        } else {
            if(\is_writable($fileLog) === false){
                $logAvailable = false;
            }
        }
        $this->setLogAvailable($logAvailable);
    }
    
    public function logWrite($data) 
    {
        $dataLog = [
            'datetime' => \date('Y-m-d H:i:s'),
            'data' => $data
        ];
        \file_put_contents($this->getLogPath() . $this->getLogFile(), \json_encode($dataLog) ."\n", \FILE_APPEND);
    }

    public function getLogPath() 
    {
        return $this->logPath;
    }

    private function setLogPath($logPath) 
    {
        $this->logPath = $logPath;
        return $this;
    }

    public function getLogAvailable() 
    {
        return $this->logAvailable;
    }
    
    private function setLogAvailable($logAvailable) 
    {
        $this->logAvailable = $logAvailable;
        return $this;
    }

    private function getLogFile() 
    {
        return $this->logFile;
    }

    private function setLogFile($logFile) 
    {
        $this->logFile = $logFile;
        return $this;
    }
}