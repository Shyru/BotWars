<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena\Fields;
use BotWars\Arena\FieldInfo;
use BotWars\Bot\BotBase;


/**
 * The base-class for all fields.
 */
class FillableField extends Basefield
{
	/** @var  BotBase */
	private $bot;

	function setFilledWithBot(BotBase $_bot)
	{
		$this->bot=$_bot;
	}

	/**
	 * Overrides isFilled() to check if bot is in this field or not.
	 * @return bool
	 */
	function isFilled()
	{
		if ($this->bot) return true;
		else return false;
	}

	function getInfo()
	{
		$fieldInfo= parent::getInfo();
		if ($this->bot)
		{
			$fieldInfo->containsBot=true;
			$fieldInfo->botInfo=$this->bot->getInfo();
		}
		return $fieldInfo;
	}


}