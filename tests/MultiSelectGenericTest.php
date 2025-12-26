<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
declare(strict_types=1);
namespace plibv4\input;
use PHPUnit\Framework\TestCase;

final class MultiSelectGenericTest extends TestCase {
	/**
	 * @return string[]
	 *
	 * @psalm-return array{8: 'Android', 2: 'Debian Linux', 7: 'iOS', 6: 'macOS', 5: 'OS X', 3: 'Redhat Linux', 4: 'Ubuntu Linux', 1: 'Windows'}
	 */
	function getExampleValues(): array {
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
	
	function testConstruct(): void {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->assertInstanceOf(MultiSelectGeneric::class, $generic);
	}
	
	function testGetQuestion(): void {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->assertEquals("Available operating systems?", $generic->getQuestion());
	}
	
	function testSetQuestion(): void {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$generic->setQuestion("Available applications?");
		$this->assertEquals("Available applications?", $generic->getQuestion());
	}
	
	function testAddValue(): void {
		$values = $this->getExampleValues();
		$generic = new MultiSelectGeneric("Available operating systems?");
		foreach($values as $key => $value) {
			$generic->addValue((string)$key, $value);
		}

		$this->assertEquals($values, $generic->getValues());
	}
	
	function testSetValues(): void {
		$values = $this->getExampleValues();

		$generic = new MultiSelectGeneric("Available operating systems?");
		$generic->setValues($values);

		$this->assertEquals($values, $generic->getValues());
	}
	
	function testGetEmptyDefault(): void {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->assertEquals(array(), $generic->getDefault());
	}

	function testSetDefault(): void {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$generic->setDefault(array("2", "5", "1"));
		$this->assertEquals(array("2", "5", "1"), $generic->getDefault());
	}
	
	function testMandatory(): void {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->assertEquals(TRUE, $generic->isMandatory());
		$generic->setMandatory(FALSE);
		$this->assertEquals(FALSE, $generic->isMandatory());
	}
	
	function testSetStyle(): void {
		$generic = new MultiSelectGeneric("Available operating systems?");
		$this->assertEquals(IndexStyle::SOURCE, $generic->getIndexStyle());
		
		$generic->setIndexStyle(IndexStyle::SOURCE);
		$this->assertEquals(IndexStyle::SOURCE, $generic->getIndexStyle());
		
		$generic->setIndexStyle(IndexStyle::ZERO);
		$this->assertEquals(IndexStyle::ZERO, $generic->getIndexStyle());
		
		$generic->setIndexStyle(IndexStyle::NATURAL);
		$this->assertEquals(IndexStyle::NATURAL, $generic->getIndexStyle());
	}
}
