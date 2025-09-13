<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
declare(strict_types=1);
namespace plibv4\input;
use PHPUnit\Framework\TestCase;

class MultiSelectGenericTest extends TestCase {
	function getExampleValues() {
		$values = array();
		$values["8"] = "Android";
		$values["2"] = "Debian Linux";
		$values["7"] = "iOS";
		$values["6"] = "macOS";
		$values["5"] = "OS X";
		$values["3"] = "Redhat Linux";
		$values["4"] = "Ubuntu Linux";
		$values["1"] = "Windows";
	return $values;
	}
	
	function testConstruct() {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->assertInstanceOf(MultiSelectGeneric::class, $generic);
	}
	
	function testGetQuestion() {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->assertEquals("Available operating systems?", $generic->getQuestion());
	}
	
	function testSetQuestion() {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$generic->setQuestion("Available applications?");
		$this->assertEquals("Available applications?", $generic->getQuestion());
	}
	
	function testAddValue() {
		$values = $this->getExampleValues();
		$generic = new MultiSelectGeneric("Available operating systems?");
		foreach($values as $key => $value) {
			$generic->addValue((string)$key, $value);
		}

		$this->assertEquals($values, $generic->getValues());
	}
	
	function testSetValues() {
		$values = $this->getExampleValues();

		$generic = new MultiSelectGeneric("Available operating systems?");
		$generic->setValues($values);

		$this->assertEquals($values, $generic->getValues());
	}
	
	function testGetEmptyDefault() {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->assertEquals(array(), $generic->getDefault());
	}

	function testSetDefault() {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$generic->setDefault(array("2", "5", "1"));
		$this->assertEquals(array("2", "5", "1"), $generic->getDefault());
	}
	
	function testMandatory() {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->assertEquals(TRUE, $generic->isMandatory());
		$generic->setMandatory(FALSE);
		$this->assertEquals(FALSE, $generic->isMandatory());
	}
	
	function testSetStyle() {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->assertEquals(SelectModel::SOURCE, $generic->getIndexStyle());
		
		$generic->setIndexStyle(SelectModel::SOURCE);
		$this->assertEquals(SelectModel::SOURCE, $generic->getIndexStyle());
		
		$generic->setIndexStyle(SelectModel::ZERO);
		$this->assertEquals(SelectModel::ZERO, $generic->getIndexStyle());
		
		$generic->setIndexStyle(SelectModel::NATURAL);
		$this->assertEquals(SelectModel::NATURAL, $generic->getIndexStyle());
	}
	
	function testSetStyleInvalid() {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->expectException(\InvalidArgumentException::class);
		$generic->setIndexStyle(17);
	}

}
