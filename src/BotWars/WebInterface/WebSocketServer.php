<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\WebInterface;

use BotWars\Ui\Messages;
use BotWars\Ui\SendMessageInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * Please add documentation for WebSocketServer!
 */
class WebSocketServer implements MessageComponentInterface, SendMessageInterface
{
	private $log;
	private $logHandler;
	/** @var \SplObjectStorage|ConnectionInterface[] */
	private $clients;
	/** @var  array An array with all messages that must be sent to every client on connect. */
	private $initMessages;


	function __construct(\Monolog\Logger $_log, LogHandler $_logHandler)
	{
		$this->log=$_log;
		$this->logHandler=$_logHandler;
		$this->clients=new \SplObjectStorage();
		$this->logHandler->registerCallback(array($this,"onLogMessage"));
	}

	function onLogMessage($_message)
	{
		$this->sendMessage(new Messages\AppendToLogMessage(array("logName"=>"global","message"=>$_message)));
	}

	private function sendLogMessage(ConnectionInterface $conn, $_message)
	{
		$conn->send(new Messages\AppendToLogMessage(array("logName"=>"global","message"=>$_message)));
	}

	/**
	 * When a new connection is opened it will be passed to this method
	 * @param \Ratchet\ConnectionInterface $conn The socket/connection that just connected to your application
	 * @throws \Exception
	 */
	function onOpen(ConnectionInterface $conn)
	{
		$this->log->debug("Got incoming websocket connection!");
		$this->sendLogMessage($conn,"Welcome!");
		$this->sendLogMessage($conn,"Flushing last log entries:");
		$buffer=$this->logHandler->getBuffer();
		foreach ($buffer as $message)
		{
			$this->sendLogMessage($conn,$message);
		}
		//now send all init-messages if any
		foreach ($this->initMessages as $initMessage)
		{
			$conn->send($initMessage);
		}
		$this->clients->attach($conn);
	}

	/**
	 * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
	 * @param \Ratchet\ConnectionInterface $conn The socket/connection that is closing/closed
	 * @throws \Exception
	 */
	function onClose(ConnectionInterface $conn)
	{
		$this->clients->detach($conn);
	}

	/**
	 * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
	 * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
	 * @param \Ratchet\ConnectionInterface $conn
	 * @param \Exception
	 * @throws \Exception
	 */
	function onError(ConnectionInterface $conn, \Exception $e)
	{
		// TODO: Implement onError() method.
	}

	/**
	 * Triggered when a client sends data through the socket
	 * @param \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
	 * @param string $msg The message received
	 * @throws \Exception
	 */
	function onMessage(ConnectionInterface $from, $msg)
	{
		// TODO: Implement onMessage() method.
	}


	/**
	 * Sends a message to the user-interface.
	 *
	 * @param Messages\BaseMessage $_message The message that should be sent.
	 */
	public function sendMessage(Messages\BaseMessage $_message)
	{
		// TODO: Implement sendMessage() method.
		foreach ($this->clients as $client)
		{
			$client->send($_message);
		}
	}

	/**
	 * Sends an initialization message to the user-interface and buffers it for later connecting user-interfaces.
	 * Initialization messages are buffered so that when new user-interfaces connect
	 * during the game they get sent all initialization messages. This allows late-coming user-interfaces
	 * to construct a working UI even if the war has already begun.
	 *
	 * @param Messages\BaseMessage $_message
	 */
	public function sendInitializationMessage(Messages\BaseMessage $_message)
	{
		$this->sendMessage($_message);
		$this->initMessages[]=$_message;
	}


}