<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Bot\Api;
use BotWars\Arena\CommunicationHub;
use BotWars\Arena\CommunicationMessage;
use BotWars\Bot\BotBase;


/**
 * Allows bots to communicate with each other.
 * Use sendText() to send a message and getReceivedMessages() to retrieved all messages that other bots sent.
 *
 */
class Communication extends BaseApi
{
	private $communicationHub;
	private $currentText=null;
	/** @var  CommunicationMessage[] */
	private $receivedMessages;

	private $sendEnergyCost;
	private $receiveEnergyCost;


	/**
	 * Constructs a new communication api.
	 *
	 * @param BotBase $_bot The bot this api is for.
	 * @param CommunicationHub $_communicationHub The communication hub that should be used to send/receive messages.
	 * @param int $_sendEnergyCost Defines how much it should cost to send a message.
	 * @param int $_receiveEnergyCost Defines how much it should cost to receive a message.
	 */
	function __construct(BotBase $_bot, CommunicationHub $_communicationHub, $_sendEnergyCost=10, $_receiveEnergyCost=1)
	{
		parent::__construct($_bot);
		$this->communicationHub=$_communicationHub;
		$this->sendEnergyCost=$_sendEnergyCost;
		$this->receiveEnergyCost=$_receiveEnergyCost;
		$this->receivedMessages=array();
		$this->communicationHub->on("newCommunicationMessage",array($this,"receiveMessage"));
	}

	/**
	 * Sends the given $_text.
	 *
	 * Sending a text costs a certain amount of energy. (See constructor)
	 *
	 * @param string $_text The text that should be sent.
	 * @return bool True if sending the text worked, false if not.
	 */
	public function sendText($_text)
	{
		if ($this->consumeEnergy($this->sendEnergyCost))
		{
			$this->currentText=$_text; //we need to save the current text so that we can discard it when we receive it (we also receive everything we send)
			$this->communicationHub->speakText($this->bot,$_text);
			return true;
		}
		else return false;
	}

	/**
	 * Returns all received messages that where spoken since the last call to this method.
	 * This allows bots to "listen" to what other bots say.
	 *
 	 * Receiving one message costs a certain amount of energy. (See constructor)
	 * The cost is multiplied with the amount of messages that are received.
	 * If there is not enough energy available to receive all messages the call returns as many messages that where
	 * receivable with the available energy.
	 *
	 * @return CommunicationMessage[]
	 */
	public function getReceivedMessages()
	{
		$energyRequired=$this->receiveEnergyCost*count($this->receivedMessages);
		$energyAvailable=$this->bot->getEnergyAvailable();
		if ($energyRequired>$energyAvailable)
		{ //we do not have enough energy available, receive as many messages as possible
			$numMessagesToReceive=(int)floor($energyAvailable/$this->receiveEnergyCost);

		}
		else $numMessagesToReceive=count($this->receivedMessages);

		if ($this->consumeEnergy($numMessagesToReceive*$this->receiveEnergyCost))
		{
			$receivedMessages=array_slice($this->receivedMessages,0,$numMessagesToReceive);
			$this->receivedMessages=array_slice($this->receivedMessages,$numMessagesToReceive);
			return $receivedMessages;
		}
	}

	/**
	 * Event-Handler for the communication hub newCommunicationMessage-event.
	 *
	 * @param CommunicationMessage $_message
	 */
	function receiveMessage(CommunicationMessage $_message)
	{
		if ($this->currentText && $this->currentText==$_message->text)
		{ //we got the text that we sent, ignore it and reset the state
			$this->currentText=null;
		}
		else $this->receivedMessages[]=$_message;
	}
} 