<?php
/**
 * This file is part of BotWars.
 * Please check the file LICENSE.md for information about the license.
 *
 * @copyright Daniel Haas 2014
 * @author Daniel Haas <daniel@file-factory.de>
 */

require __DIR__."/TestBot.php";

class CommunicationTest extends PHPUnit_Framework_TestCase
{

	function testReceiveMessagesWithEnoughEnergy()
	{
		$bot=new TestBot("blue",10);
		$communicationApi=new \BotWars\Bot\Api\Communication($bot,new \BotWars\Arena\CommunicationHub(),20,2);
		$communicationApi->receiveMessage(new \BotWars\Arena\CommunicationMessage("Speaker","Msg1"));
		$communicationApi->receiveMessage(new \BotWars\Arena\CommunicationMessage("Speaker","Msg2"));
		$communicationApi->receiveMessage(new \BotWars\Arena\CommunicationMessage("Speaker","Msg3"));
		$communicationApi->receiveMessage(new \BotWars\Arena\CommunicationMessage("Speaker","Msg4"));

		$receivedMessages=$communicationApi->getReceivedMessages();
		$this->assertEquals(2,$bot->getEnergyAvailable());
		$this->assertEquals(4,count($receivedMessages));
		$this->assertEquals("Msg1",$receivedMessages[0]->text);
		$this->assertEquals("Msg2",$receivedMessages[1]->text);
		$this->assertEquals("Msg3",$receivedMessages[2]->text);
		$this->assertEquals("Msg4",$receivedMessages[3]->text);

	}

	function testReceiveMessagesWithoutEnoughEnergy()
	{
		//We have only have 5 energy but 4 messages that would cost 8 energy. So we should only be able to receive 2 messages and have 1 energy left
		$bot=new TestBot("blue",5);
		$communicationApi=new \BotWars\Bot\Api\Communication($bot,new \BotWars\Arena\CommunicationHub(),20,2);
		$communicationApi->receiveMessage(new \BotWars\Arena\CommunicationMessage("Speaker","Msg1"));
		$communicationApi->receiveMessage(new \BotWars\Arena\CommunicationMessage("Speaker","Msg2"));
		$communicationApi->receiveMessage(new \BotWars\Arena\CommunicationMessage("Speaker","Msg3"));
		$communicationApi->receiveMessage(new \BotWars\Arena\CommunicationMessage("Speaker","Msg4"));

		$receivedMessages=$communicationApi->getReceivedMessages();
		$this->assertEquals(1,$bot->getEnergyAvailable());
		$this->assertEquals(2,count($receivedMessages));
		$this->assertEquals("Msg1",$receivedMessages[0]->text);
		$this->assertEquals("Msg2",$receivedMessages[1]->text);
	}

	/**
	 * This is more an integration test because it also tests the CommunicationHub.
	 */
	function testSendAndReceive()
	{
		$communicationHub=new \BotWars\Arena\CommunicationHub();

		$botA=new TestBot("blue",100);
		$communicationApiA=new \BotWars\Bot\Api\Communication($botA,$communicationHub,20,2);
		$botB=new TestBot("blue",100);
		$communicationApiB=new \BotWars\Bot\Api\Communication($botB,$communicationHub,20,2);

		$communicationApiA->sendText("Hello World");

		//the api of the sender bot should not have any messages
		$this->assertEquals(0,count($communicationApiA->getReceivedMessages()));
		$this->assertEquals(80,$botA->getEnergyAvailable());

		//the api of the bot b should have a message
		$receivedMessages=$communicationApiB->getReceivedMessages();
		$this->assertEquals(1,count($receivedMessages));
		$this->assertEquals("Hello World",$receivedMessages[0]->text);
		$this->assertEquals("BotWars TestBot",$receivedMessages[0]->fromBotName);

	}
}
 