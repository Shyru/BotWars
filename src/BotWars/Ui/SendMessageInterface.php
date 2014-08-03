<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

namespace BotWars\Ui;

/**
 * This interface describes how messages are delivered to a user-interface.
 * An implementation of this class is used to drive the user interface (s).
 * This allows to implement different user interfaces.
 *
 */
interface SendMessageInterface
{
	/**
	 * Sends a message to the user-interface.
	 *
	 * @param Messages\BaseMessage $_message The message that should be sent.
	 */
	public function sendMessage(Messages\BaseMessage $_message);

	/**
	 * Sends an initialization message to the user-interface.
	 * Initialization messages are buffered so that when new user-interfaces connect
	 * during the game they get sent all initialization messages. This allows late-coming user-interfaces
	 * to construct a working UI even if the war has already begun.
	 *
	 * @param Messages\BaseMessage $_message
	 */
	public function sendInitializationMessage(Messages\BaseMessage $_message);
} 