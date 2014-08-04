<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */


class Adam extends \BotWars\Bot\BotBase
{

	/**
	 * This must be implemented by bots.
	 * Use available API's and do whatever you deem intelligent.
	 *
	 */
	public function executeTurn()
	{
		// TODO: Implement executeTurn() method.
	}

	/**
	 * Returns the name of the bot.
	 * Example: "Homer".
	 * @return string the name of the bot.
	 */
	public function getName()
	{
		return "Adam the first";
	}

	/**
	 * Should return the model name of the bot.
	 * Example: "Destroyer V1".
	 *
	 * @return string the model of the bot
	 */
	public function getModelName()
	{
		return "A.D.A.M.";
	}
}


//Return the class-name of our bot implementation
return "Adam";