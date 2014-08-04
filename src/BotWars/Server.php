<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars;

use BotWars\Arena\PlayField;
use BotWars\Server\Stack;
use BotWars\Ui\Messages\BotUpdatedMessage;
use BotWars\WebInterface\WebSocketServer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Ratchet\Http\HttpServer;


/**
 * The botwars Server!
 */
class Server
{

	private $webSocketPortNumber;
	private $botPaths;
	private $nextTurnBot;
	/** @var  BotProxy[] */
	private $bots;

	/**
	 *
	 */
	function __construct($_arguments)
	{

		$logFormatter=new LogFormatter();
		//create a stdout log handler
		$stdoutHandler=new StreamHandler('php://stdout');
		$stdoutHandler->setFormatter($logFormatter);
		$this->logHandlers[]=$stdoutHandler;

		//create a log handler for the webinterface
		$this->webInterfaceLogHandler=new WebInterface\LogHandler(100);
		$this->webInterfaceLogHandler->setFormatter($logFormatter);
		$this->logHandlers[]=$this->webInterfaceLogHandler;

		//now create a base log for our self
		$this->log=$this->initializeLogger(new Logger("Server"));
		$this->botPaths=array_slice($_arguments,1);


	}

	/**
	 * Initialize a logger.
	 * This pushes all necessary handlers to the logger.
	 *
	 * @param Logger $_log The logger that should be initialized
	 * @return Logger The initialized logger
	 */
	private function initializeLogger(Logger $_log)
	{
		foreach ($this->logHandlers as $logHandler)
		{
			$_log->pushHandler($logHandler);
		}
		return $_log;
	}

	/**
	 * Runs the botwars Server.
	 */
	public function run($_port=1337)
	{

		$app = require_once __DIR__ . '/WebInterface/App.php';
		$stack = new Stack($app);


		$app->get('/war', function() use ($app,$stack,$_port)
		{
			//ok generate a playfield
			if (!isset($app['playfield']))
			{

				$this->log->info("Trying to load bots from commandline...");

				$this->bots=array();

				foreach ($this->botPaths as $botPath)
				{
					$this->log->debug("Creating BotProxy from $botPath ...");
					$this->bots[]=new BotProxy($botPath,"red",100);
				}
				$this->nextTurnBot=0;


				//initialize websocket Server:
				$this->webSocketPortNumber=$_port+1;
				$this->log->info("Creating websocket server on port $this->webSocketPortNumber...");
				$webSocketPort=new \React\Socket\Server($app['loop']);

				$webSocketServer=new WebSocketServer($this->initializeLogger(new Logger("WebInterface")),$this->webInterfaceLogHandler);
				$ratchetWebsocketServer = new \Ratchet\WebSocket\WsServer($webSocketServer);
				$ratchetWebsocketServer->disableVersion("Hixie76");

				//we must create an io-server so that all gets wired up correctly, but we cannot call run() on it because that would block our loop
				$ioServer = new \Ratchet\Server\IoServer(new HttpServer($ratchetWebsocketServer),$webSocketPort,$stack['loop']);

				$webSocketPort->listen($this->webSocketPortNumber);

				$this->log->info("Generating new playfield...");
				$app['playfield']=new PlayField($webSocketServer,$this->initializeLogger(new Logger("PlayField")),array(
					"width"=>15,
					"height"=>15)
				);

				foreach ($this->bots as $bot)
				{
					$bot->setPlayField($app['playfield']);
				}

				$weaponDirection=0;

				//test bot-updated messages
				$botUpdatedMessage=new BotUpdatedMessage(array(
					"id"=>1,
					"x"=>4,
					"y"=>5,
					"botStatus"=>false,
					"engineDirection"=>0,
					"weaponDirection"=>$weaponDirection,
					"headDirection"=>0,
					"engineImageUrl"=>"/static/images/testbot_torso.png",
					"weaponsImageUrl"=>"/static/images/testbot_weapons.png"
				));
				$webSocketServer->sendInitializationMessage($botUpdatedMessage);
				$stack["loop"]->addPeriodicTimer(5,function() use ($webSocketServer, &$weaponDirection) {
					//test bot-updated messages
					$weaponDirection+=45;
					$this->log->debug("Updating weapon angle! New value: ".$weaponDirection);
					$botUpdatedMessage=new BotUpdatedMessage(array(
						"id"=>1,
						"x"=>3,
						"y"=>5,
						"botStatus"=>false,
						"engineDirection"=>0,
						"weaponDirection"=>$weaponDirection,
						"headDirection"=>0,
						"engineImageUrl"=>"/static/images/testbot_torso.png",
						"weaponsImageUrl"=>"/static/images/testbot_weapons.png"
					));
					$webSocketServer->sendMessage($botUpdatedMessage);
				});


			}


			return $app['twig']->render('war.twig', array(
				"port"=>$this->webSocketPortNumber
			));
		});

		echo "Server running at http://127.0.0.1:$_port\n";

		$stack->listen($_port);

	}

	function nextBotTurn()
	{
		$currentBot=$this->bots[$this->nextTurnBot];
		$currentBot->nextTurn();

		$this->nextTurnBot++;
		if ($this->nextTurnBot==count($this->bots)) $this->nextTurnBot=0;
	}

} 