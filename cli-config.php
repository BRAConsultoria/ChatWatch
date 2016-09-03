<?php
namespace ChatWatch;

use Doctrine\ORM\Tools\Console\ConsoleRunner;
require_once "vendor/autoload.php";

return ConsoleRunner::createHelperSet((new \ChatWatch\EntityMaster())->getEntityManager());