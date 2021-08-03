<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AssertTest
 *
 * @author hm
 */
class BasicInputTest extends TestCase {
	function testGetInput() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name?\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("Claus");

		BasicInput::setStdioIntercept($stdio);

		$this->assertEquals("Claus", BasicInput::getInput("Your name?"));
	}
	
	function testGetInputMandatory() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name?\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("");
		$stdio->expectOutput("> ");
		$stdio->addInput("Claus");

		BasicInput::setStdioIntercept($stdio);

		$this->assertEquals("Claus", BasicInput::getInputMandatory("Your name?"));
	}
	
	function testGetInputDefaultedEmpty() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name? (Heike)\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("");

		BasicInput::setStdioIntercept($stdio);

		$this->assertEquals("Heike", BasicInput::getInputDefaulted("Your name?", "Heike"));
	}

	function testGetInputDefaultedFilled() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name? (Heike)\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("Claus");

		BasicInput::setStdioIntercept($stdio);

		$this->assertEquals("Claus", BasicInput::getInputDefaulted("Your name?", "Heike"));
	}

}
