<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Bot\Api;
use BotWars\Arena\PlayField;
use BotWars\Bot\BotBase;


/**
 * Provides the Radar-API to Bots.
 * Use discoverField() to find out what is in a given field.
 *
 */
class Radar extends BaseApi
{
	private $playField;
	private $discoveryEnergyCost;
	private $advancedEnergyCostFactor;

	function __construct(BotBase $_bot, PlayField $_playField, $_discoveryEnergyCost=10, $_advancedEnergyCostFactor=1.5)
	{
		parent::__construct($_bot);
		$this->playField=$_playField;
		$this->discoveryEnergyCost=$_discoveryEnergyCost;
		$this->advancedEnergyCostFactor=$_advancedEnergyCostFactor;
	}

	/**
	 * Discovers the field at \c $_x and \c $_y.
	 *
	 * @param int $_x The x-coordinate of the field to discover
	 * @param int $_y The y-coordinate of the field to discover
	 * @param bool $_advanced Set to true to gather advanced information.
	 * @return \BotWars\Arena\FieldInfo|null The FieldInfo-object or null if the coordinates where invalid or when there is not enough energy to discover.
	 */
	public function discoverField($_x, $_y, $_advanced=false)
	{
		$factor=1;
		if ($_advanced) $factor=$this->advancedEnergyCostFactor;
		if ($this->consumeEnergy($this->discoveryEnergyCost*$factor))
		{
			return $this->playField->getFieldInfo($_x, $_y, $_advanced);
		}
		else return null;
	}
} 