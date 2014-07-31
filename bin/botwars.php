<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */



require_once __DIR__ . '/../vendor/autoload.php';

//setup our own autoloader
require_once __DIR__.'/../src/autoloader.php';
$botWarsLoader = new BotWars\SplClassLoader('BotWars', realpath(__DIR__."\\..\\src"));
$botWarsLoader->register();


//now create a Server and run it
$server=new \BotWars\Server();
$server->run();


