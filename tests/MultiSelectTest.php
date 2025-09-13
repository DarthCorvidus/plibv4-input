<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
declare(strict_types=1);
namespace plibv4\input;
use PHPUnit\Framework\TestCase;
class MultiSelectTest extends TestCase {
	function getGeneric(): MultiSelectGeneric {
		$values = array();
		$values["8"] = "Android";
		$values["2"] = "Debian Linux";
		$values["7"] = "iOS";
		$values["6"] = "macOS";
		$values["5"] = "OS X";
		$values["3"] = "Redhat Linux";
		$values["4"] = "Ubuntu Linux";
		$values["1"] = "Windows";

		$generic = new MultiSelectGeneric("Available operating systems?");
		$generic->setValues($values);
		$generic->setContinue("x");
	return $generic;
	}

	function testGetSelectableAsSource() {
		$model = $this->getGeneric();

		$select = new MultiSelect($model);
		$this->assertEquals($select->getSelectable(), array(8, 2, 7, 6, 5, 3, 4, 1));
	}
	
	function testGetSelectableAsZero() {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::ZERO);

		$select = new MultiSelect($model);
		$this->assertEquals($select->getSelectable(), array(0, 1, 2, 3, 4, 5, 6, 7));
	}

	function testGetSelectableAsNatural() {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);

		$select = new MultiSelect($model);
		$this->assertEquals($select->getSelectable(), array(1, 2, 3, 4, 5, 6, 7, 8));
	}

	function testGetMapAsSource() {
		$model = $this->getGeneric();

		$select = new MultiSelect($model);
		$this->assertEquals($select->getMap(), array(8=>8, 2=>2, 7=>7, 6=>6, 5=>5, 3=>3, 4=>4, 1=>1));
	}
	
	function testGetMapAsZero() {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::ZERO);

		$select = new MultiSelect($model);
		$this->assertEquals($select->getMap(), array(0=>8, 1=>2, 2=>7, 3=>6, 4=>5, 5=>3, 6=>4, 7=>1));
	}
	
	function testGetMapAsNatural() {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);

		$select = new MultiSelect($model);
		$this->assertEquals($select->getMap(), array(1=>8, 2=>2, 3=>7, 4=>6, 5=>5, 6=>3, 7=>4, 8=>1));
	}

	
	function testGetLinesAsSource() {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::SOURCE);

		$select = new MultiSelect($model);

		$lines[] = "8 Android";
		$lines[] = "2 Debian Linux";
		$lines[] = "7 iOS";
		$lines[] = "6 macOS";
		$lines[] = "5 OS X";
		$lines[] = "3 Redhat Linux";
		$lines[] = "4 Ubuntu Linux";
		$lines[] = "1 Windows";
		$this->assertEquals($lines, $select->getLines());
	}
	
	function testGetLinesAsZero() {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::ZERO);
		
		$select = new MultiSelect($model);
		$lines[] = "0 Android";
		$lines[] = "1 Debian Linux";
		$lines[] = "2 iOS";
		$lines[] = "3 macOS";
		$lines[] = "4 OS X";
		$lines[] = "5 Redhat Linux";
		$lines[] = "6 Ubuntu Linux";
		$lines[] = "7 Windows";
		$this->assertEquals($lines, $select->getLines());
	}
	
	function testGetLinesAsNatural() {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);
		
		$select = new MultiSelect($model);
		$lines[] = "1 Android";
		$lines[] = "2 Debian Linux";
		$lines[] = "3 iOS";
		$lines[] = "4 macOS";
		$lines[] = "5 OS X";
		$lines[] = "6 Redhat Linux";
		$lines[] = "7 Ubuntu Linux";
		$lines[] = "8 Windows";
		$this->assertEquals($lines, $select->getLines());
	}
	
	function testGetLinesDefaulted() {
		$model = $this->getGeneric();
		$model->setDefault(array(8, 3));

		$select = new MultiSelect($model);
		
		$reflection = new \ReflectionClass($select);
		$property = $reflection->getProperty('selected');
		$property->setAccessible(true);
		$property->setValue($select, $model->getDefault());

		
		$lines[] = "[8] Android";
		$lines[] = " 2  Debian Linux";
		$lines[] = " 7  iOS";
		$lines[] = " 6  macOS";
		$lines[] = " 5  OS X";
		$lines[] = "[3] Redhat Linux";
		$lines[] = " 4  Ubuntu Linux";
		$lines[] = " 1  Windows";
		$this->assertEquals($lines, $select->getLines());
	}

	function testGetSelectedAsSource() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput("8 Android\n");
		$stdio->expectOutput("2 Debian Linux\n");
		$stdio->expectOutput("7 iOS\n");
		$stdio->expectOutput("6 macOS\n");
		$stdio->expectOutput("5 OS X\n");
		$stdio->expectOutput("3 Redhat Linux\n");
		$stdio->expectOutput("4 Ubuntu Linux\n");
		$stdio->expectOutput("1 Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("2\n");
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 8  Android\n");
		$stdio->expectOutput("[2] Debian Linux\n");
		$stdio->expectOutput(" 7  iOS\n");
		$stdio->expectOutput(" 6  macOS\n");
		$stdio->expectOutput(" 5  OS X\n");
		$stdio->expectOutput(" 3  Redhat Linux\n");
		$stdio->expectOutput(" 4  Ubuntu Linux\n");
		$stdio->expectOutput(" 1  Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("1\n");
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 8  Android\n");
		$stdio->expectOutput("[2] Debian Linux\n");
		$stdio->expectOutput(" 7  iOS\n");
		$stdio->expectOutput(" 6  macOS\n");
		$stdio->expectOutput(" 5  OS X\n");
		$stdio->expectOutput(" 3  Redhat Linux\n");
		$stdio->expectOutput(" 4  Ubuntu Linux\n");
		$stdio->expectOutput("[1] Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("x\n");

		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::SOURCE);

		$select = new MultiSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals(array(2, 1), $select->getSelected());
	}
	
	function testGetSelectedAsZero() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput("0 Android\n");
		$stdio->expectOutput("1 Debian Linux\n");
		$stdio->expectOutput("2 iOS\n");
		$stdio->expectOutput("3 macOS\n");
		$stdio->expectOutput("4 OS X\n");
		$stdio->expectOutput("5 Redhat Linux\n");
		$stdio->expectOutput("6 Ubuntu Linux\n");
		$stdio->expectOutput("7 Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("1\n");
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 0  Android\n");
		$stdio->expectOutput("[1] Debian Linux\n");
		$stdio->expectOutput(" 2  iOS\n");
		$stdio->expectOutput(" 3  macOS\n");
		$stdio->expectOutput(" 4  OS X\n");
		$stdio->expectOutput(" 5  Redhat Linux\n");
		$stdio->expectOutput(" 6  Ubuntu Linux\n");
		$stdio->expectOutput(" 7  Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("7\n");
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 0  Android\n");
		$stdio->expectOutput("[1] Debian Linux\n");
		$stdio->expectOutput(" 2  iOS\n");
		$stdio->expectOutput(" 3  macOS\n");
		$stdio->expectOutput(" 4  OS X\n");
		$stdio->expectOutput(" 5  Redhat Linux\n");
		$stdio->expectOutput(" 6  Ubuntu Linux\n");
		$stdio->expectOutput("[7] Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("x\n");

		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::ZERO);

		$select = new MultiSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals(array(2, 1), $select->getSelected());
	}
	
	function testGetSelectedAsNatural() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput("1 Android\n");
		$stdio->expectOutput("2 Debian Linux\n");
		$stdio->expectOutput("3 iOS\n");
		$stdio->expectOutput("4 macOS\n");
		$stdio->expectOutput("5 OS X\n");
		$stdio->expectOutput("6 Redhat Linux\n");
		$stdio->expectOutput("7 Ubuntu Linux\n");
		$stdio->expectOutput("8 Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("2\n");
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 1  Android\n");
		$stdio->expectOutput("[2] Debian Linux\n");
		$stdio->expectOutput(" 3  iOS\n");
		$stdio->expectOutput(" 4  macOS\n");
		$stdio->expectOutput(" 5  OS X\n");
		$stdio->expectOutput(" 6  Redhat Linux\n");
		$stdio->expectOutput(" 7  Ubuntu Linux\n");
		$stdio->expectOutput(" 8  Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("8\n");
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 1  Android\n");
		$stdio->expectOutput("[2] Debian Linux\n");
		$stdio->expectOutput(" 3  iOS\n");
		$stdio->expectOutput(" 4  macOS\n");
		$stdio->expectOutput(" 5  OS X\n");
		$stdio->expectOutput(" 6  Redhat Linux\n");
		$stdio->expectOutput(" 7  Ubuntu Linux\n");
		$stdio->expectOutput("[8] Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("x\n");

		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);

		$select = new MultiSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals(array(2, 1), $select->getSelected());
	}

	function testGetSelectedMandatoryEmpty() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput("1 Android\n");
		$stdio->expectOutput("2 Debian Linux\n");
		$stdio->expectOutput("3 iOS\n");
		$stdio->expectOutput("4 macOS\n");
		$stdio->expectOutput("5 OS X\n");
		$stdio->expectOutput("6 Redhat Linux\n");
		$stdio->expectOutput("7 Ubuntu Linux\n");
		$stdio->expectOutput("8 Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("x\n");
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput("1 Android\n");
		$stdio->expectOutput("2 Debian Linux\n");
		$stdio->expectOutput("3 iOS\n");
		$stdio->expectOutput("4 macOS\n");
		$stdio->expectOutput("5 OS X\n");
		$stdio->expectOutput("6 Redhat Linux\n");
		$stdio->expectOutput("7 Ubuntu Linux\n");
		$stdio->expectOutput("8 Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("2\n");
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 1  Android\n");
		$stdio->expectOutput("[2] Debian Linux\n");
		$stdio->expectOutput(" 3  iOS\n");
		$stdio->expectOutput(" 4  macOS\n");
		$stdio->expectOutput(" 5  OS X\n");
		$stdio->expectOutput(" 6  Redhat Linux\n");
		$stdio->expectOutput(" 7  Ubuntu Linux\n");
		$stdio->expectOutput(" 8  Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("8\n");
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 1  Android\n");
		$stdio->expectOutput("[2] Debian Linux\n");
		$stdio->expectOutput(" 3  iOS\n");
		$stdio->expectOutput(" 4  macOS\n");
		$stdio->expectOutput(" 5  OS X\n");
		$stdio->expectOutput(" 6  Redhat Linux\n");
		$stdio->expectOutput(" 7  Ubuntu Linux\n");
		$stdio->expectOutput("[8] Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("x\n");

		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);

		$select = new MultiSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals(array(2, 1), $select->getSelected());
	}

	function testGetSelectedDefaulted() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 1  Android\n");
		$stdio->expectOutput("[2] Debian Linux\n");
		$stdio->expectOutput(" 3  iOS\n");
		$stdio->expectOutput(" 4  macOS\n");
		$stdio->expectOutput(" 5  OS X\n");
		$stdio->expectOutput(" 6  Redhat Linux\n");
		$stdio->expectOutput(" 7  Ubuntu Linux\n");
		$stdio->expectOutput("[8] Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("x\n");

		$model = $this->getGeneric();
		$model->setDefault(array(1, 2));
		$model->setIndexStyle(IndexStyle::NATURAL);

		$select = new MultiSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals(array(1, 2), $select->getSelected());
	}
	
	function testGetSelectedDefaultedOverride() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 1  Android\n");
		$stdio->expectOutput("[2] Debian Linux\n");
		$stdio->expectOutput(" 3  iOS\n");
		$stdio->expectOutput(" 4  macOS\n");
		$stdio->expectOutput(" 5  OS X\n");
		$stdio->expectOutput(" 6  Redhat Linux\n");
		$stdio->expectOutput(" 7  Ubuntu Linux\n");
		$stdio->expectOutput("[8] Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("2\n");
		$stdio->expectOutput("Available operating systems?\n");
		$stdio->expectOutput(" 1  Android\n");
		$stdio->expectOutput(" 2  Debian Linux\n");
		$stdio->expectOutput(" 3  iOS\n");
		$stdio->expectOutput(" 4  macOS\n");
		$stdio->expectOutput(" 5  OS X\n");
		$stdio->expectOutput(" 6  Redhat Linux\n");
		$stdio->expectOutput(" 7  Ubuntu Linux\n");
		$stdio->expectOutput("[8] Windows\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("x\n");
		

		$model = $this->getGeneric();
		$model->setDefault(array(1, 2));
		$model->setIndexStyle(IndexStyle::NATURAL);

		$select = new MultiSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals(array(1), $select->getSelected());
	}

}