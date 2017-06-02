<?php
namespace App\Telegram;
use ChatWatch\Config;
use App\Utils\S3;

class FileStorage
{
    /**
     *
     * @var array
     */
    private $conf;

    /**
     *
     * @var string
     */
    private $error;
    
    /**
     *
     * @var S3
     */
    private $s3;

    /** 
     * @var string API URL wheter http://wapi.phphive.info/api/message/send.php to send or http://wapi.phphive.info/api/message/receive.php to read messages
     */
    private $urlAPI   = 'https://api.telegram.org/';

    /**
     *
     * @var array
     */
    public $allowedTypes = [
        //video
        'video/x-flv', 'video/mp4', 'application/x-mpegURL', 'video/MP2T', 'video/3gpp',
        'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv',
        
        //image
        'image/jpeg', 'image/png', 'image/gif', 
        
        //audio
        'audio/basic', 'audio/mid', 'audio/mpeg', 'audio/mp4', 'audio/x-mpegurl', 'audio/vnd.wav',

        //voice
        'application/ogg',

        //document
        'application/pdf', 'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
        'text/plain', 'application/excel', 'application/vnd.ms-excel', 'application/x-excel', 
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
        'application/xml', 'text/xml'
    ];
    
    /**
     *
     * @var array 
     */
    private $exts = [
        'photos'    => 'jpg',
        'videos'    => 'mp4',
        'audio'     => 'ogg',
        'voice'     => 'ogg',
        'document'  => 'pdf'
    ];


    public function __construct() 
    {
        $this->conf = (new Config())->getConf('telegram');
        $this->s3   = (new S3())->setBucket('chat-watch');
    }

    public function getFiles(array $message, $type)
    {

        return $this->processFiles($message[$type]);
    }
    
    private function processFiles($files) 
    {
        $file = (\is_array(\end($files)) ? \end($files) : $files);
        $fileId = $file['file_id'];
        $fileData = $this->executeMultipart([
            'file_id' => $fileId
        ], 'getFile');

        return $this->downloadFile($fileData['result']['file_path'], $fileId);
    }
    
    private function downloadFile($filePath, $fileId) 
    {
        $client     = new \GuzzleHttp\Client(['verify' => false]);
        $response   = $client->request('GET', $this->getUrlAPI() .'file/'. $this->conf['botToken'] .'/'. $filePath);

        return $this->manageFile($response->getBody()->getContents(), $filePath, $fileId);
    }
    
    private function manageFile($file, $filePath, $fileId)
    {
        $tmpFileName    = \STORAGE_ROOT ."tmp/" . \uniqid();
        
        \file_put_contents($tmpFileName, $file);
        
        $ext        = \explode('.', $filePath);
        $mimeType   = $this->getFileMimeType($tmpFileName);

        if(\in_array($mimeType, $this->allowedTypes) === true and isset($ext[1])){

            $name   = \bin2hex(\openssl_random_pseudo_bytes(32)) .'.'. $ext[1];
            $type   = \explode('/', $ext[0])[0];
            $md5    = \md5_file($tmpFileName);

            $this->s3->putObject($name, \file_get_contents($tmpFileName));

            \unlink($tmpFileName);

            return [
                'id'        => $fileId,
                'name'      => $name,
                'type'      => $type,
                'mime-type' => $mimeType,
                'md5'       => $md5
            ];

        } else {
            exit("> ". $filePath);
            $this->setError("File($mimeType) without any known extension or type not allowed.");
            return false;
        }
    }
        
    private function getFileMimeType($filename)
    {
        if(\is_readable($filename)){
            $finfo  = \explode(';', (new \finfo(\FILEINFO_MIME))->file($filename));
            return(isset($finfo[0]) ? $finfo[0] : NULL);
        } else {
            return null;
        }
    }


    private function executeMultipart($formParams, $action)
    {
        $multipartData = [];
        foreach ($formParams as $key=>$param){
            $multipartData[] = [
                'name'      => $key,
                'contents'  => $param,
            ];
        }

        $client     = new \GuzzleHttp\Client(['verify' => false]);
        $response   = $client->request('POST', $this->getUrlAPI() . $this->conf['botToken'] .'/'. $action, [
            'multipart' => $multipartData
        ]);

        return \json_decode($response->getBody()->getContents(), true);
    }

    public function getUrlAPI() 
    {
        return $this->urlAPI;
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