<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */

declare(strict_types=1);
namespace plibv4\input;
use PHPUnit\Framework\TestCase;

class StdioInterceptTest extends TestCase {
	function testStdioInterceptValidOutput() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Please enter your name:");
		$stdio->expectOutput("> ");
		$stdio->put("Please enter your name:");
		$stdio->put("> ");
		$this->assertEquals("", "");
	}

	function testStdioInterceptNonExistingOutput() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Please enter your name:");
		$stdio->put("Please enter your name:");
		$this->expectException(StdioInterceptException::class);
		$stdio->put(">");
	}

	
	function testStdioInterceptInvalidOutput() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Please enter your name:");
		$stdio->expectOutput("> ");
		$stdio->put("Please enter your name:");
		$this->expectException(StdioInterceptException::class);
		$stdio->put(">");
	}
	
	function testStdioInterceptInput() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Please enter your name:");
		$stdio->expectOutput("> ");
		$stdio->addInput("Marasek");
		$stdio->put("Please enter your name:");
		$stdio->put("> ");
		$this->assertEquals("Marasek", $stdio->get());
	}
}
