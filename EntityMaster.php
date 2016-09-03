<?php

namespace ChatWatch;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use ChatWatch\Config;

class EntityMaster
{

    /**
    * @var Doctrine\ORM\EntityManagerInterface EntityManager
    */
    private $entityManager;
    
    /**
    * @var array conf database credentials
    */
    private $conf;

    public function __construct() 
    {
        $this->conf = (new Config())->getConf('db');
        $paths = array(__DIR__ . "/Domain/Entity/");
        $isDevMode = true;

        // the connection configuration
        $dbParams = [
            'driver'    => $this->conf['driver'],
            'user'      => $this->conf['user'],
            'password'  => $this->conf['password'],
            'dbname'    => $this->conf['dbname'],
        ];

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $this->setEntityManager(EntityManager::create($dbParams, $config));
    }

    public function persist($entity) 
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return true;
    }

    public function setEntityManager($entityManager) 
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * 
     * @return Doctrine\ORM\EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

}