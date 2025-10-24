<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
namespace plibv4\input;
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
	
	/**
	 * @psalm-suppress MoreSpecificReturnType
	 * @param SelectModel $model
	 * @return SingleSelectModel
	 */
	private static function toSingleSelectModel(SelectModel $model): SingleSelectModel {
		/** @psalm-suppress LessSpecificReturnStatement */
		return $model;
	}

	#[\Override]
	function getLine(string $mappedKey, string $realKey, string $value): string {
		$line = "";
		$model = self::toSingleSelectModel($this->model);
		if($model->getDefault()=="") {
			$line .= $mappedKey;
		}
		if($model->getDefault()!=="" && $model->getDefault()!=$realKey) {
			$line .= " ".$mappedKey." ";
		}
		if($model->getDefault()!=="" && $model->getDefault()==$realKey) {
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
				$input = self::toSingleSelectModel($this->model)->getDefault();
			}
		}
	return $map[$input];
	}
}