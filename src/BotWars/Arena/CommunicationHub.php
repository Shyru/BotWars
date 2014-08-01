<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena;
use BotWars\Bot\BotBase;
use Evenement\EventEmitter;


/**
 * Manages communication between bots.
 * This fires an event for every spoken text. This allows the Communication-Api keep track
 * on all the texts that are spoken by other bots.
 */
class CommunicationHub extends EventEmitter
{
	/**
	 * Notifies the communication hub that the bot \c $_bot wants to speak text $_text.
	 *
	 * @param BotBase $_bot The bot that speaks.
	 * @param string $_text The text that the bot speaks.
	 */
	function speakText(BotBase $_bot, $_text)
	{
		$message=new CommunicationMessage($_bot->getName(),$_text);
		$this->emit("newCommunicationMessage",array($message));
		//TODO send message to the frontend so that it can be displayed
	}
} 