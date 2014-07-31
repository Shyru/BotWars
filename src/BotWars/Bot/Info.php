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
 * Contains all information another bot can get about a bot.
 */
class Info
{
	/** @var  string the identifier of the team the bot belongs to. */
	public $team;

	/** @var  int the Weight of the bot (in t) */
	public $weight;
} 