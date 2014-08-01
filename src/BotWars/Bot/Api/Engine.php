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
 * Provides the Engine-API to bots.
 * Use this API to move your bot.
 */
class Engine extends BaseApi
{
	const ANGLE_UP=0;
	const ANGLE_RIGHT=90;
	const ANGLE_DOWN=180;
	const ANGLE_LEFT=270;

	private $turnEnergyConsumption;

	/** @var int The current angle. Since we can turn around the bot multiple times this can be negative or positive and can be bigger than 360° */
	private $currentAngle;

	/**
	 * Constructs the engine api.
	 *
	 * @param BotBase $_bot The bot this api is for.
	 * @param float $_turnEnergyConsumption How much energy a turn by 90 degrees costs.
	 */
	function __construct(BotBase $_bot, $_turnEnergyConsumption)
	{
		parent::__construct($_bot);
		$this->turnEnergyConsumption=$_turnEnergyConsumption;
		$this->currentAngle=self::ANGLE_UP;
	}

	/**
	 * Turns the bot right by 90 degrees, starting at the current angle.
	 */
	public function turnRight()
	{
		if ($this->consumeEnergy($this->turnEnergyConsumption))
		{
			$this->currentAngle+=90;
			return true;
		}
		else return false;
	}

	/**
	 * Turns the bot left by 90 degrees, starting at the current angle.
	 * @return bool
	 */
	public function turnLeft()
	{
		if ($this->consumeEnergy($this->turnEnergyConsumption))
		{
			$this->currentAngle-=90;
			return true;
		}
		else return false;
	}

	/**
	 * Returns the current angle the engine is turned to.
	 *
	 * @return int The current angle, this is always only 0, 90, 180 or 270.
	 */
	public function getNormalizedCurrentAngle()
	{
		$angle= $this->currentAngle % 360;
		if ($angle<0) return 360+$angle;
		else return $angle;
	}

	/**
	 * Returns the current angle the engine is turned to.
	 * \b Important: This can also return negative values and values bigger than 360!
	 * To get a normalized angle use getNormalizedCurrentAngle().
	 *
	 * @return int The current angle.
	 */
	public function getCurrentAngle()
	{
		return $this->currentAngle;
	}

	/**
	 * Turn the bot into the direction of the given \c $_angle.
	 * Sine a bot can only walk up,right,down,left the only angles allowed
	 * are 0, 90, 180 and 270 degrees.
	 * Passing any other value will cost energy but do nothing!
	 *
	 * The passed \c $_angle is always interpreted absolute. So if the current direction is
	 * 90°, calling turnToAngle(270) will turn the bot to the left and cost (2*turnRight()-Energy)*1.5 energy.
	 * The method will also figure out the shortest direction to turn so that no energy is wasted.
	 *
	 * Note: Since this is a convenient API that does a lot of calculation, using this method
	 * costs 50% more energy than calling turnRight() or turnLeft().
	 *
	 * @param float $_angle The angle to turn to.
	 * @return bool True if turning succeeded, false if not enough energy was available.
	 */
	public function turnToAngle($_angle)
	{
		//we must first find out what is the shortest direction (right or left) to turn
		$currentAngle=$this->getNormalizedCurrentAngle();

		if ($currentAngle==$_angle)
		{ //we are already walking in this direction, this will not need any energy.
			return true;
		}

		//i tried to find a mathematical algorithm that calculates this, but did not find an easy formula, hence this mapping table
		$shortestDirectionMapping=array("0-90"=>90,"0-180"=>180,"0-270"=>-90,"90-0"=>-90,"90-180"=>90,"90-270"=>180,"180-0"=>180,"180-90"=>-90,"180-270"=>90,"270-0"=>90,"270-90"=>180,"270-180"=>-90);

		$path=$currentAngle."-".$_angle;
		if (isset($shortestDirectionMapping[$path]))
		{ //we have a path, all is well
			$turnNumber=abs($shortestDirectionMapping[$path])/90; //how many 90° turns do we have to make? (should either be 1 or 2)
			if ($this->consumeEnergy($this->turnEnergyConsumption*$turnNumber*1.5))
			{
				$this->currentAngle+=$shortestDirectionMapping[$path];
				return true;
			}
			return false;
		}
		else
		{ //mapping not found, this means that $_angle was not ok, consume energy but do nothing.
			return $this->consumeEnergy(2*$this->turnEnergyConsumption);
		}
	}

} 