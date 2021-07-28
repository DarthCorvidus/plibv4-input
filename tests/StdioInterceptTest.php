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
