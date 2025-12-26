<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
declare(strict_types=1);
namespace plibv4\input;
use PHPUnit\Framework\TestCase;
final class BasicInputTest extends TestCase {
	function testGetInput(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name?\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("Claus");

		BasicInput::setStdioIntercept($stdio);

		$this->assertEquals("Claus", BasicInput::getInput("Your name?"));
	}
	
	function testGetInputMandatory(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name?\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("");
		$stdio->expectOutput("> ");
		$stdio->addInput("Claus");

		BasicInput::setStdioIntercept($stdio);

		$this->assertEquals("Claus", BasicInput::getInputMandatory("Your name?"));
	}
	
	function testGetInputDefaultedEmpty(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name? (Heike)\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("");

		BasicInput::setStdioIntercept($stdio);

		$this->assertEquals("Heike", BasicInput::getInputDefaulted("Your name?", "Heike"));
	}

	function testGetInputDefaultedFilled(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name? (Heike)\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("Claus");

		BasicInput::setStdioIntercept($stdio);

		$this->assertEquals("Claus", BasicInput::getInputDefaulted("Your name?", "Heike"));
	}

}
