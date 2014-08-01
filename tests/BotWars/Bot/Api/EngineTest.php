<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

require_once(__DIR__."/TestBot.php");

class EngineTest extends PHPUnit_Framework_TestCase
{
	/** @var  \BotWars\Bot\BotBase */
	private $bot;
	/** @var  \BotWars\Bot\Api\Engine */
	private $engine;

	function setUp()
	{
		//Create a bot with 500 energy.
		$this->bot=new TestBot("red",500);
		//Create an engine api, with 10 movement cost for a bot having 500 energy
		$this->engine=new \BotWars\Bot\Api\Engine($this->bot,10);
	}

	function testTurnToAngle_0_90()
	{
		$this->engine->turnToAngle(90);
		$this->assertEquals(90,$this->engine->getNormalizedCurrentAngle());
		$this->assertEquals(90,$this->engine->getCurrentAngle());
		$this->assertEquals(485,$this->bot->getEnergyAvailable()); //500-15
	}

	function testTurnToAngle_0_180()
	{
		$this->engine->turnToAngle(180);
		$this->assertEquals(180,$this->engine->getNormalizedCurrentAngle());
		$this->assertEquals(180,$this->engine->getCurrentAngle());
		$this->assertEquals(470,$this->bot->getEnergyAvailable()); //500-15-15
	}

	function testTurnToAngle_0_270()
	{
		$this->engine->turnToAngle(270);
		$this->assertEquals(270,$this->engine->getNormalizedCurrentAngle());
		$this->assertEquals(-90,$this->engine->getCurrentAngle()); //turning to the left is the most efficient
		$this->assertEquals(485,$this->bot->getEnergyAvailable()); //500-15
	}

	function testTurnToAngle_180_90()
	{
		$this->engine->turnToAngle(180);
		$this->engine->turnToAngle(90);
		$this->assertEquals(90,$this->engine->getNormalizedCurrentAngle());
		$this->assertEquals(90,$this->engine->getCurrentAngle());
		$this->assertEquals(455,$this->bot->getEnergyAvailable()); //500-30-15
	}

	function testTurnToAngle_270_0()
	{
		$this->engine->turnToAngle(270);
		$this->engine->turnToAngle(0);
		$this->assertEquals(0,$this->engine->getNormalizedCurrentAngle());
		$this->assertEquals(0,$this->engine->getCurrentAngle());
		$this->assertEquals(470,$this->bot->getEnergyAvailable()); //500-15-15
	}

	function testTurnToAngle_NoOp()
	{
		$this->engine->turnToAngle(270);
		$this->engine->turnToAngle(270);
		$this->assertEquals(270,$this->engine->getNormalizedCurrentAngle());
		$this->assertEquals(-90,$this->engine->getCurrentAngle());
		$this->assertEquals(485,$this->bot->getEnergyAvailable()); //500-15
	}



}
 