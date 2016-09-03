<?php

namespace ChatWatch;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class EntityMaster
{

    /**
    * @var Doctrine\ORM\EntityManagerInterface EntityManager
    */
    private $entityManager;

    public function __construct() 
    {
        
        $paths = array(__DIR__ . "/Domain/Entity/");
        $isDevMode = true;

        // the connection configuration
        $dbParams = [
            'driver'    => 'pdo_mysql',
            'user'      => 'root',
            'password'  => '',
            'dbname'    => 'chat_watch',
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