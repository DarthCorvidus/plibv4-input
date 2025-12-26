<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
declare(strict_types=1);
namespace plibv4\input;
use PHPUnit\Framework\TestCase;

final class SingleSelectTest extends TestCase {
	function getGeneric(): SingleSelectGeneric {
		$generic = new SingleSelectGeneric("What is your favorite pet?");
		$generic->addValue("2", "Cat");
		$generic->addValue("1", "Dog");
		$generic->addValue("7", "Mouse");
	return $generic;
	}

	function testGetSelectableAsSource(): void {
		$model = $this->getGeneric();

		$select = new SingleSelect($model);
		$this->assertEquals($select->getSelectable(), array(2, 1, 7));
	}
	
	function testGetSelectableAsZero(): void {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::ZERO);

		$select = new SingleSelect($model);
		$this->assertEquals($select->getSelectable(), array(0, 1, 2));
	}

	function testGetSelectableAsNatural(): void {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);

		$select = new SingleSelect($model);
		$this->assertEquals($select->getSelectable(), array(1, 2, 3));
	}

	function testGetMapAsSource(): void {
		$model = $this->getGeneric();

		$select = new SingleSelect($model);
		$this->assertEquals($select->getMap(), array(2=>2, 1=>1, 7=>7));
	}
	
	function testGetMapAsZero(): void {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::ZERO);

		$select = new SingleSelect($model);
		$this->assertEquals($select->getMap(), array(0=>2, 1=>1, 2=>7));
	}
	
	function testGetMapAsNatural(): void {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);

		$select = new SingleSelect($model);
		$this->assertEquals($select->getMap(), array(1=>2, 2=>1, 3=>7));
	}

	
	function testGetLinesAsSource(): void {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::SOURCE);

		$select = new SingleSelect($model);
		$lines = [];
		$lines[] = "2 Cat";
		$lines[] = "1 Dog";
		$lines[] = "7 Mouse";
		$this->assertEquals($lines, $select->getLines());
	}
	
	function testGetLinesAsZero(): void {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::ZERO);
		
		$select = new SingleSelect($model);
		$lines = [];
		$lines[] = "0 Cat";
		$lines[] = "1 Dog";
		$lines[] = "2 Mouse";
		$this->assertEquals($lines, $select->getLines());
	}
	
	function testGetLinesAsNatural(): void {
		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);
		
		$select = new SingleSelect($model);
		$lines = [];
		$lines[] = "1 Cat";
		$lines[] = "2 Dog";
		$lines[] = "3 Mouse";
		$this->assertEquals($lines, $select->getLines());
	}
	
	function testGetLinesDefaulted(): void {
		$model = $this->getGeneric();
		$model->setDefault("1");
		
		$select = new SingleSelect($model);
		$lines = [];
		$lines[] = " 2  Cat";
		$lines[] = "[1] Dog";
		$lines[] = " 7  Mouse";
		$this->assertEquals($lines, $select->getLines());
	}

	function testGetSelectedAsSource(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("What is your favorite pet?\n");
		$stdio->expectOutput("2 Cat\n");
		$stdio->expectOutput("1 Dog\n");
		$stdio->expectOutput("7 Mouse\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("2\n");
		

		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::SOURCE);

		$select = new SingleSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals("2", $select->getSelected());
	}
	
	function testGetSelectedAsZero(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("What is your favorite pet?\n");
		$stdio->expectOutput("0 Cat\n");
		$stdio->expectOutput("1 Dog\n");
		$stdio->expectOutput("2 Mouse\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("0\n");
		

		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::ZERO);

		$select = new SingleSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals("2", $select->getSelected());
	}

	function testGetSelectedAsNatural(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("What is your favorite pet?\n");
		$stdio->expectOutput("1 Cat\n");
		$stdio->expectOutput("2 Dog\n");
		$stdio->expectOutput("3 Mouse\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("1\n");
		

		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);

		$select = new SingleSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals("2", $select->getSelected());
	}
	
	function testGetSelectedMandatoryEmpty(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("What is your favorite pet?\n");
		$stdio->expectOutput("1 Cat\n");
		$stdio->expectOutput("2 Dog\n");
		$stdio->expectOutput("3 Mouse\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("\n");
		$stdio->expectOutput("What is your favorite pet?\n");
		$stdio->expectOutput("1 Cat\n");
		$stdio->expectOutput("2 Dog\n");
		$stdio->expectOutput("3 Mouse\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("1\n");

		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);

		$select = new SingleSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals("2", $select->getSelected());
		
	}
	
	function testGetSelectedDefaultedEmpty(): void {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("What is your favorite pet?\n");
		$stdio->expectOutput(" 1  Cat\n");
		$stdio->expectOutput("[2] Dog\n");
		$stdio->expectOutput(" 3  Mouse\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("\n");

		$model = $this->getGeneric();
		$model->setIndexStyle(IndexStyle::NATURAL);
		$model->setDefault("1");

		$select = new SingleSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals("2", $select->getSelected());
		
	}

}
