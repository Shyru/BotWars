<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena\Tiles;
use BotWars\Arena\TileInfo;
use BotWars\Bot\BotBase;


/**
 * The base-class for all fields.
 */
class FillableTile extends BaseTile
{
	/** @var  BotBase */
	private $bot;

	function setFilledWithBot(BotBase $_bot)
	{
		$this->bot=$_bot;
	}

	/**
	 * Overrides isFilled() to check if bot is in this tile or not.
	 * @return bool
	 */
	function isFilled()
	{
		if ($this->bot) return true;
		else return false;
	}

	function getInfo($_advanced=false)
	{
		$fieldInfo= parent::getInfo();
		if ($this->bot)
		{
			$fieldInfo->containsBot=true;
			$fieldInfo->botInfo=$this->bot->getRadarInfo($_advanced);
		}
		return $fieldInfo;
	}


}