<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
declare(strict_types=1);
namespace plibv4\input;
use PHPUnit\Framework\TestCase;
use ConvertDate;
use ValidateDate;

class InputTextTest extends TestCase {
	function testGetInput() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name?\n> ");
		$stdio->addInput("Claus");
		$input = new InputText("Your name?");
		$input->setStdioIntercept($stdio);
		$this->assertEquals("Claus", $input->getInput());
	}

	function testGetDefaultedEmpty() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name? (Claus)\n> ");
		$stdio->addInput("\n");
		$input = new InputText("Your name?");
		$input->setDefault("Claus");
		$input->setStdioIntercept($stdio);
		$this->assertEquals("Claus", $input->getInput());
	}

	function testGetDefaultedEntry() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name? (Claus)\n> ");
		$stdio->addInput("Lisa\n");
		$input = new InputText("Your name?");
		$input->setDefault("Claus");
		$input->setStdioIntercept($stdio);
		$this->assertEquals("Lisa", $input->getInput());
	}
	
	function testGetMandatory() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Your name?\n> ");
		$stdio->addInput("\n");
		$stdio->expectOutput("Input is mandatory.\n");
		$stdio->expectOutput("Your name?\n> ");
		$stdio->addInput("Claus\n");
		
		$input = new InputText("Your name?");
		$input->mandatory(true);
		$input->setStdioIntercept($stdio);
		$input->getInput();
		$this->assertEquals("Claus", $input->getInput());
	}
	
	function testValidate() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Date of birth?\n> ");
		$stdio->addInput("08/01/2000\n");
		$stdio->expectOutput("Invalid format, DD.MM.YYYY expected\n");
		$stdio->expectOutput("Date of birth?\n> ");
		$stdio->addInput("01.08.2000\n");
		
		$input = new InputText("Date of birth?");
		$input->setStdioIntercept($stdio);
		$input->setValidate(new ValidateDate(ValidateDate::GERMAN));
		$this->assertEquals("01.08.2000", $input->getInput());
	}
	
	function testInputConvert() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Date of birth?\n> ");
		$stdio->addInput("01.08.2000\n");

		$input = new InputText("Date of birth?");
		$input->setInputConvert(new ConvertDate(ConvertDate::GERMAN, ConvertDate::ISO));
		$input->setStdioIntercept($stdio);
		$this->assertEquals("2000-08-01", $input->getInput());
	}

	function testInputConvertEmpty() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Date of birth?\n> ");
		$stdio->addInput("\n");

		$input = new InputText("Date of birth?");
		$input->setInputConvert(new ConvertDate(ConvertDate::GERMAN, ConvertDate::ISO));
		$input->setStdioIntercept($stdio);
		$this->assertEquals("", $input->getInput());
	}

	function testInputConvertDefault() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Date of birth? (01.08.2020)\n> ");
		$stdio->addInput("\n");

		$input = new InputText("Date of birth?");
		$input->setDefault("2020-08-01");
		$input->setInputConvert(new ConvertDate(ConvertDate::GERMAN, ConvertDate::ISO));
		$input->setDefaultConvert(new ConvertDate(ConvertDate::ISO, ConvertDate::GERMAN));
		$input->setStdioIntercept($stdio);
		$this->assertEquals("2020-08-01", $input->getInput());
	}

	function testInputConvertDefaultAndResult() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Date of birth? (01.08.2020)\n> ");
		$stdio->addInput("\n");

		$input = new InputText("Date of birth?");
		$input->setDefault("2020-08-01");
		$input->setInputConvert(new ConvertDate(ConvertDate::GERMAN, ConvertDate::US));
		$input->setDefaultConvert(new ConvertDate(ConvertDate::ISO, ConvertDate::GERMAN));
		$input->setStdioIntercept($stdio);
		$this->assertEquals("08/01/2020", $input->getInput());
	}

	
}
