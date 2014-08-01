<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena;
use DateTime;


/**
 * Small object that holds information about a communication message.
 */
class CommunicationMessage
{
	/** @var  string the name of the bot that spoke the text. */
	public $fromBotName;
	/** @var  string the text that the bot spoke. */
	public $text;
	/** @var  DateTime the date-time when the text was spoken.  */
	public $dateTime;

	function __construct($_fromBotName, $_text)
	{
		$this->fromBotName = $_fromBotName;
		$this->text = $_text;
		$this->dateTime=new DateTime();
	}


}