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
use BotWars\WebInterface\WebSocketServer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Ratchet\Http\HttpServer;


/**
 * Please add documentation for Runner!
 */
class Server
{

	private $webSocketPortNumber;

	/**
	 *
	 */
	function __construct()
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
					"width"=>20,
					"height"=>10)
				);


			}


			return $app['twig']->render('war.twig', array(
				"port"=>$this->webSocketPortNumber
			));
		});

		echo "Server running at http://127.0.0.1:$_port\n";

		$stack->listen($_port);

	}

} 