<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Bot\Api;
use BotWars\Bot\BotBase;


/**
 * A base-class for all api classes.
 * Provides a method to consume energy. This should be used
 * by from all api-calls that consume energy.
 */
class BaseApi
{
	protected $bot;
	function __construct(BotBase $_bot)
	{
		$this->bot=$_bot;
	}

	/**
	 * Checks if the bot has the needed energy available.
	 * If yes it consumes the amount of energy and returns true, otherwise it returns false.
	 * If it returns false, this means the API-call must fail!
	 *
	 * @param int $_amount The amount of energy the API-call needs.
	 * @return bool True if there is enough energy, false otherwise.
	 */
	function consumeEnergy($_amount)
	{
		if ($_amount<0) return false;
		if ($this->bot->getEnergyAvailable()>=$_amount)
		{ //we have the needed amount of energy, consume it and return true
			$this->bot->subtractEnergy($_amount);
			return true;
		}
		else return false;
	}

} 