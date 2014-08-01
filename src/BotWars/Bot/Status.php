<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Bot;


/**
 * The Status of a bot. Used to update the UI.
 */
class Status
{
	/** @var  float The health of the bot. (0-100) */
	public $health;
	/** @var  int How much energy the bot has available. (0-10000) */
	public $energyAvailable;
	/** @var  string the Team the bot belongs to */
	public $team;
	/** @var  string the name of the bot */
	public $name;
	/** @var  string the model name of the bot */
	public $modelName;

}