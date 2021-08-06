<?php
abstract class Select {
	protected $model;
	protected $stdio;
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
			if($this->model->getIndexStyle() == SelectModel::SOURCE) {
				$map[$key] = $key;
			}
			if($this->model->getIndexStyle() == SelectModel::ZERO) {
				$map[$i] = $key;
				$i++;
			}
			if($this->model->getIndexStyle() == SelectModel::NATURAL) {
				$map[$i+1] = $key;
				$i++;
			}
		}
	return $map;
	}

	abstract function getLine($mappedKey, $realKey, $value);
	
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
}