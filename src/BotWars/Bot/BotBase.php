<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Bot;
use BotWars\Arena\PlayField;


/**
 * Base-class for all bots.
 * Your Bot must inherit from this and can use all provided API.
 *
 */
class BotBase
{
	/** @var  PlayField The playfield that can be used to query the field.*/
	private $playField;

	/** @var  string The team the bot belongs to. */
	private $team;

	/** @var  int The weight of the bot. */
	private $weight;

	private $energyAvailable;
	/** @var  int How many energy the bot generates per turn. */
	private $energyPerTurn;

	function __construct($_team)
	{
		$this->team=$_team;
	}

	/**
	 * @param \BotWars\Arena\PlayField $playField
	 */
	public final function setPlayField(PlayField $playField)
	{
		$this->playField = $playField;
	}

	final function nextTurn()
	{
		$this->energyAvailable+=$this->energyPerTurn;
	}

	/**
	 * @param int $energyPerTurn
	 */
	public function setEnergyPerTurn($energyPerTurn)
	{
		$this->energyPerTurn = $energyPerTurn;
	}

	/**
	 * @return string
	 */
	protected function getTeam()
	{
		return $this->team;
	}

	protected function discoverField($_x,$_y)
	{
		$this->energyAvailable-=10;
		return $this->playField->getFieldInfo($_x,$_y);
	}

	public function getInfo()
	{
		$botInfo=new Info();
		$botInfo->team=$this->team;
		$botInfo->weight=$this->weight;

		return $botInfo;
	}



}