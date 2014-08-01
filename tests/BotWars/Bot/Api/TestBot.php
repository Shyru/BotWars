<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

/**
 * A simple bot that can be used for testing.
 */
class TestBot extends \BotWars\Bot\BotBase
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

	public function getName()
	{
		return "BotWars TestBot";
	}

	public function getModelName()
	{
		return "TestBot_v1";
	}
}