<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */


//require vendor autoloader
require_once __DIR__ . '/../vendor/autoload.php';

//require our own autoloader
require_once __DIR__ . '/../src/autoload.php';


//now create a Server and run it
$server=new \BotWars\Server($argv);
$server->run();


