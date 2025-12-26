<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */

declare(strict_types=1);
namespace plibv4\input;
use PHPUnit\Framework\TestCase;

final class StdioInterceptTest extends TestCase {
	function testStdioInterceptValidOutput(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Please enter your name:");
		$stdio->expectOutput("> ");
		$stdio->put("Please enter your name:");
		$stdio->put("> ");
		$this->assertEquals("", "");
	}

	function testStdioInterceptNonExistingOutput(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Please enter your name:");
		$stdio->put("Please enter your name:");
		$this->expectException(StdioInterceptException::class);
		$stdio->put(">");
	}

	
	function testStdioInterceptInvalidOutput(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Please enter your name:");
		$stdio->expectOutput("> ");
		$stdio->put("Please enter your name:");
		$this->expectException(StdioInterceptException::class);
		$stdio->put(">");
	}
	
	function testStdioInterceptInput(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Please enter your name:");
		$stdio->expectOutput("> ");
		$stdio->addInput("Marasek");
		$stdio->put("Please enter your name:");
		$stdio->put("> ");
		$this->assertEquals("Marasek", $stdio->get());
	}
}
