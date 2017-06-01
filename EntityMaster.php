<?php
namespace ChatWatch;

use Doctrine\ORM\EntityManager;

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
        $app = require './bootstrap.php';
        $this->setEntityManager($app['orm.em']);
    }

    public function persist($entity) 
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
        return true;
    }

    public function setEntityManager($entityManager) 
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * 
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

}