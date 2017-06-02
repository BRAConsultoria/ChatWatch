<?php

namespace App\Utils;
use Aws\S3\S3Client;
use ChatWatch\Config;

class S3 {
    
    /**
     *
     * @var S3Client
     */
    private $s3;

    /**
     *
     * @var string 
     */
    private $bucket;
    
    /**
     *
     * @var array
     */
    private $conf;
    
    public function __construct() 
    {
        $this->conf = (new Config())->getConf('aws');
        $this->setS3();
    }
    
    public function putObject(string $key, $body): bool
    {
        $result = $this->s3->putObject([
            'Bucket' => $this->getBucket(),
            'Key'    => $key,
            'Body'   => $body
        ]);

        if($result){
            return true;
        } else {
            return false;
        }
    }
    
    public function setS3()
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-2',
            'credentials' => [
                'key'    => $this->conf['key'],
                'secret' => $this->conf['secret']
            ]
        ]);
    }

    public function getBucket() 
    {
        return $this->bucket;
    }

    public function setBucket($bucket) 
    {
        $this->bucket = $bucket;
        return $this;
    }
}