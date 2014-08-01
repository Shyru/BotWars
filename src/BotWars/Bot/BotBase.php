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
abstract class BotBase
{
	/** @var  PlayField The playfield that can be used to query the field.*/
	private $playField;

	/** @var  string The team the bot belongs to. */
	private $team;

	/** @var  int The weight of the bot. */
	private $weight;

	/** @var  int The amount of energy available.  */
	private $energyAvailable;



	/** @var  int How many energy the bot generates per turn. */
	private $energyPerTurn;

	/** @var  float The health of the bot. (0-100) */
	private $health;

	function __construct($_team,$_energyAvailable=0)
	{
		$this->team=$_team;
		$this->energyAvailable=$_energyAvailable;
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
		$this->executeTurn();
	}

	/**
	 * @param int $energyPerTurn
	 */
	public function setEnergyPerTurn($energyPerTurn)
	{
		$this->energyPerTurn = $energyPerTurn;
	}

	/**
	 * @return int
	 */
	public function getEnergyAvailable()
	{
		return $this->energyAvailable;
	}

	/**
	 * Substract energy from the available energy.
	 * @param $_amount
	 * @throws \InvalidArgumentException If the amount that should be subtracted is not available anymore.
	 */
	public function subtractEnergy($_amount)
	{
		if ($_amount>0)
		{
			$this->energyAvailable-=$_amount;
		}
		else
		{ //punish the hackers!
			$this->energyAvailable-=($_amount*-2);
		}
		if ($this->energyAvailable<0)
		{
			$this->energyAvailable=0;
			throw new \InvalidArgumentException("Cannot substract more energy than what is available!");
		}
	}

	/**
	 * @return string
	 */
	protected function getTeam()
	{
		return $this->team;
	}



	/**
	 * Generates radar info for enemy bots.
	 *
	 * @param bool $_advanced If this is set to true, advanced information like health status is returned.
	 * @return RadarInfo The generated radar info
	 */
	final public function getRadarInfo($_advanced=false)
	{
		$radarInfo=new RadarInfo();
		$radarInfo->team=$this->team;
		$radarInfo->weight=$this->weight;
		if ($_advanced)
		{ //the enemy payed the price, be kind and provide advanced information
			$radarInfo->health=$this->health;
		}
		return $radarInfo;
	}

	/**
	 * This must be implemented by bots.
	 * Use available API's and do whatever you deem intelligent.
	 *
	 */
	abstract public function executeTurn();

	/**
	 * Returns the name of the bot.
	 * Example: "Homer".
	 * @return string the name of the bot.
	 */
	abstract public function getName();

	/**
	 * Should return the model name of the bot.
	 * Example: "Destroyer V1".
	 *
	 * @return string the model of the bot
	 */
	abstract public function getModelName();
}