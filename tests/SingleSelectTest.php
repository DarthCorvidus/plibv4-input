<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

class SingleSelectTest extends TestCase {
	function getGeneric(): SingleSelectGeneric {
		$generic = new SingleSelectGeneric("What is your favorite pet?");
		$generic->addValue("2", "Cat");
		$generic->addValue("1", "Dog");
		$generic->addValue("7", "Mouse");
	return $generic;
	}

	function testGetSelectableAsSource() {
		$model = $this->getGeneric();

		$select = new SingleSelect($model);
		$this->assertEquals($select->getSelectable(), array(2, 1, 7));
	}
	
	function testGetSelectableAsZero() {
		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::ZERO);

		$select = new SingleSelect($model);
		$this->assertEquals($select->getSelectable(), array(0, 1, 2));
	}

	function testGetSelectableAsNatural() {
		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::NATURAL);

		$select = new SingleSelect($model);
		$this->assertEquals($select->getSelectable(), array(1, 2, 3));
	}

	function testGetMapAsSource() {
		$model = $this->getGeneric();

		$select = new SingleSelect($model);
		$this->assertEquals($select->getMap(), array(2=>2, 1=>1, 7=>7));
	}
	
	function testGetMapAsZero() {
		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::ZERO);

		$select = new SingleSelect($model);
		$this->assertEquals($select->getMap(), array(0=>2, 1=>1, 2=>7));
	}
	
	function testGetMapAsNatural() {
		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::NATURAL);

		$select = new SingleSelect($model);
		$this->assertEquals($select->getMap(), array(1=>2, 2=>1, 3=>7));
	}

	
	function testGetLinesAsSource() {
		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::SOURCE);

		$select = new SingleSelect($model);
		$lines[] = "2 Cat";
		$lines[] = "1 Dog";
		$lines[] = "7 Mouse";
		$this->assertEquals($lines, $select->getLines());
	}
	
	function testGetLinesAsZero() {
		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::ZERO);
		
		$select = new SingleSelect($model);
		$lines[] = "0 Cat";
		$lines[] = "1 Dog";
		$lines[] = "2 Mouse";
		$this->assertEquals($lines, $select->getLines());
	}
	
	function testGetLinesAsNatural() {
		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::NATURAL);
		
		$select = new SingleSelect($model);
		$lines[] = "1 Cat";
		$lines[] = "2 Dog";
		$lines[] = "3 Mouse";
		$this->assertEquals($lines, $select->getLines());
	}
	
	function testGetLinesDefaulted() {
		$model = $this->getGeneric();
		$model->setDefault("1");
		
		$select = new SingleSelect($model);
		$lines[] = " 2  Cat";
		$lines[] = "[1] Dog";
		$lines[] = " 7  Mouse";
		$this->assertEquals($lines, $select->getLines());
	}

	function testGetSelectedAsSource() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("What is your favorite pet?\n");
		$stdio->expectOutput("2 Cat\n");
		$stdio->expectOutput("1 Dog\n");
		$stdio->expectOutput("7 Mouse\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("2\n");
		

		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::SOURCE);

		$select = new SingleSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals("2", $select->getSelected());
	}
	
	function testGetSelectedAsZero() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("What is your favorite pet?\n");
		$stdio->expectOutput("0 Cat\n");
		$stdio->expectOutput("1 Dog\n");
		$stdio->expectOutput("2 Mouse\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("0\n");
		

		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::ZERO);

		$select = new SingleSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals("2", $select->getSelected());
	}

	function testGetSelectedAsNatural() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("What is your favorite pet?\n");
		$stdio->expectOutput("1 Cat\n");
		$stdio->expectOutput("2 Dog\n");
		$stdio->expectOutput("3 Mouse\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("1\n");
		

		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::NATURAL);

		$select = new SingleSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals("2", $select->getSelected());
	}
	
	function testGetSelectedMandatoryEmpty() {
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
		$model->setIndexStyle(SelectModel::NATURAL);

		$select = new SingleSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals("2", $select->getSelected());
		
	}
	
	function testGetSelectedDefaultedEmpty() {
		$stdio = new StdioIntercept();
		$stdio->expectOutput("What is your favorite pet?\n");
		$stdio->expectOutput(" 1  Cat\n");
		$stdio->expectOutput("[2] Dog\n");
		$stdio->expectOutput(" 3  Mouse\n");
		$stdio->expectOutput("> ");
		$stdio->addInput("\n");

		$model = $this->getGeneric();
		$model->setIndexStyle(SelectModel::NATURAL);
		$model->setDefault("1");

		$select = new SingleSelect($model);
		$select->setStdioIntercept($stdio);
		
		$this->assertEquals("2", $select->getSelected());
		
	}

}
