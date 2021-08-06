<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */

/**
 * SingleSelect
 * 
 * Displays a list of items with an index and allows the user to select one
 * item by typing in it's index.
 * An item can be preselected as default value.
 */
class SingleSelect extends Select {
	function __construct(SingleSelectModel $model) {
		$this->model = $model;
		$this->stdio = new StdioIntercept();
		$this->stdio->passthru();
	}

	function getLine($mappedKey, $realKey, $value): string {
		$line = "";
		if($this->model->getDefault()=="") {
			$line .= $mappedKey;
		}
		if($this->model->getDefault()!=="" && $this->model->getDefault()!=$realKey) {
			$line .= " ".$mappedKey." ";
		}
		if($this->model->getDefault()!=="" && $this->model->getDefault()==$realKey) {
			$line .= "[".$mappedKey."]";
		}
		$line .= " ";
		$line .= $value;
	return $line;
	}
	
	/**
	 * getSelected
	 * 
	 * Returns the index of the selected value, or an empty string if the user
	 * did not select a value and no default value was defined.
	 * Note that getSelect will always return the 'real' index which may differ
	 * from the index numbers displayed.
	 * @return string
	 */
	function getSelected(): string {
		$this->model->load();
		$map = $this->getMap();
		$input = "";
		while(!isset($map[$input])) {
			$this->stdio->put($this->model->getQuestion()."\n");
			$this->printLines();
			$this->stdio->put("> ");
			$input = trim($this->stdio->get());
			if($input==="") {
				$input = $this->model->getDefault();
			}
		}
	return $map[$input];
	}
}