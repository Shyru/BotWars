<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Arena\Fields;
use BotWars\Arena\FieldInfo;


/**
 * Please add documentation for BaseField!
 */
class BaseField
{
	/** This means the field is empty. */
	const HEIGHT_NONE=0;
	/** This means the field is half-full. It cannot be entered, but you can shoot over it. */
	const HEIGHT_MEDIUM=1;
	/** This means the field is full. It cannot be entered, and you cannot shoot through it. */
	const HEIGHT_FULL=2;

	/**
	 * Returns true if the field is filled.
	 */
	function isFilled()
	{
		return false;
	}

	/**
	 * Returns the fill-height of the field.
	 *
	 * @return int
	 */
	function height()
	{
		return self::HEIGHT_NONE;
	}

	function getInfo()
	{
		$fieldInfo=new FieldInfo();
		$fieldInfo->movable=!$this->isFilled();
		$fieldInfo->height=$this->height();
		return $fieldInfo;
	}



} 