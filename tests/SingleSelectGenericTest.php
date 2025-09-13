<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
declare(strict_types=1);
namespace plibv4\input;
use PHPUnit\Framework\TestCase;

class SingleSelectGenericTest extends TestCase {
	function testConstruct() {
		$generic = new SingleSelectGeneric("Your favorite pet?");
		$this->assertInstanceOf(SingleSelectGeneric::class, $generic);
	}
	
	function testGetQuestion() {
		$generic = new SingleSelectGeneric("Your favorite pet?");
		$this->assertEquals("Your favorite pet?", $generic->getQuestion());
	}
	
	function testSetQuestion() {
		$generic = new SingleSelectGeneric("Your favorite pet?");
		$generic->setQuestion("Your favorite breed?");
		$this->assertEquals("Your favorite breed?", $generic->getQuestion());
	}
	
	function testAddValue() {
		$values = array();
		$values["2"] = "Cat";
		$values["1"] = "Dog";
		$values["7"] = "Mouse";

		$generic = new SingleSelectGeneric("Your favorite pet?");
		foreach($values as $key => $value) {
			$generic->addValue((string)$key, $value);
		}

		$this->assertEquals($values, $generic->getValues());
	}
	
	function testSetValues() {
		$values = array();
		$values["2"] = "Cat";
		$values["1"] = "Dog";
		$values["7"] = "Mouse";

		$generic = new SingleSelectGeneric("Your favorite pet?");
		$generic->setValues($values);

		$this->assertEquals($values, $generic->getValues());
	}
	
	function testGetEmptyDefault() {
		$generic = new SingleSelectGeneric("Your favorite pet?");
		$this->assertEquals("", $generic->getDefault());
	}

	function testSetDefault() {
		$generic = new SingleSelectGeneric("Your favorite pet?");
		$generic->setDefault("2");
		$this->assertEquals("2", $generic->getDefault());
	}
	
	function testMandatory() {
		$generic = new SingleSelectGeneric("Your favorite pet?");
		$this->assertEquals(TRUE, $generic->isMandatory());
		$generic->setMandatory(FALSE);
		$this->assertEquals(FALSE, $generic->isMandatory());
	}
	
	function testSetStyle() {
		$generic = new SingleSelectGeneric("Your favorite pet?");
		$this->assertEquals(IndexStyle::SOURCE, $generic->getIndexStyle());
		
		$generic->setIndexStyle(IndexStyle::SOURCE);
		$this->assertEquals(IndexStyle::SOURCE, $generic->getIndexStyle());
		
		$generic->setIndexStyle(IndexStyle::ZERO);
		$this->assertEquals(IndexStyle::ZERO, $generic->getIndexStyle());
		
		$generic->setIndexStyle(IndexStyle::NATURAL);
		$this->assertEquals(IndexStyle::NATURAL, $generic->getIndexStyle());
	}
}
