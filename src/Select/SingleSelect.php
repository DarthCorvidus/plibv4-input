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
class SingleSelect {
	private $model;
	private $stdio;
	function __construct(SingleSelectModel $model) {
		$this->model = $model;
		$this->stdio = new StdioIntercept();
		$this->stdio->passthru();
	}
	
	/**
	 * setStdioIntercept
	 * 
	 * Set StdioIntercept, to facilitate testing.
	 * @param StdioIntercept $stdio
	 */
	public function setStdioIntercept(StdioIntercept $stdio) {
		$this->stdio = $stdio;
	}
	
	
	public function getSelectable(): array {
		return array_keys($this->getMap());
	}
	
	public function getMap() {
		$map = array();
		$i = 0;
		foreach($this->model->getValues() as $key => $value) {
			if($this->model->getIndexStyle() == SingleSelectModel::SOURCE) {
				$map[$key] = $key;
			}
			if($this->model->getIndexStyle() == SingleSelectModel::ZERO) {
				$map[$i] = $key;
				$i++;
			}
			if($this->model->getIndexStyle() == SingleSelectModel::NATURAL) {
				$map[$i+1] = $key;
				$i++;
			}
		}
	return $map;
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
	
	function getLines(): array {
		$result = array();
		$values = $this->model->getValues();
		$i = 0;
		foreach($this->getMap() as $mappedKey => $realKey) {
			$result[] = $this->getLine($mappedKey, $realKey, $values[$realKey]);
		}
	return $result;
	}
	
	function printLines() {
		foreach($this->getLines() as $value) {
			$this->stdio->put($value."\n");
		}
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