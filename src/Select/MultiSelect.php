<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */

namespace plibv4\input;
/**
 * MultiSelect
 * 
 * Displays a list of items with an index and allows the user to select several
 * items by typing their indices
 * items can be preselected as default value.
 */
class MultiSelect extends Select {
	private array $selected = array();
	function __construct(MultiSelectModel $model) {
		$this->model = $model;
		$this->stdio = new StdioIntercept();
		$this->stdio->passthru();
	}
	/**
	 * @psalm-suppress MoreSpecificReturnType
	 * @param SelectModel $model
	 * @return MultiSelectModel
	 */
	private static function toMultiSelectModel(SelectModel $model): MultiSelectModel {
		/** @psalm-suppress LessSpecificReturnStatement */
		return $model;
	}
	
	#[\Override]
	function getLine(string $mappedKey, string $realKey, string $value): string {
		$line = "";
		if($this->selected==array()) {
			$line .= $mappedKey;
		}
		if($this->selected!==array() && !in_array($realKey, $this->selected)) {
			$line .= " ".$mappedKey." ";
		}
		if($this->selected!==array() && in_array($realKey, $this->selected)) {
			$line .= "[".$mappedKey."]";
		}
		$line .= " ";
		$line .= $value;
	return $line;
	}
	
	private function mayContinue(): bool {
		if(!empty($this->selected)) {
			return true;
		}
		if($this->model->isMandatory()===FALSE) {
			return true;
		}
	return false;
	}
	
	/**
	 * getSelected
	 *
	 * Returns the index of the selected value, or an empty string if the user
	 * did not select a value and no default value was defined.
	 * Note that getSelect will always return the 'real' index which may differ
	 * from the index numbers displayed.
	 * @return array get selected values
	 */
	function getSelected(): array {
		$model = self::toMultiSelectModel($this->model);
		$model->load();
		$this->selected = $model->getDefault();
		$map = $this->getMap();
		while(true) {
			$this->stdio->put($model->getQuestion()."\n");
			$this->printLines();
			$this->stdio->put("> ");
			$input = trim($this->stdio->get());
			if($input===$model->getContinue() && $this->mayContinue()) {
				return $this->selected;
			}
			
			//invalid input, just continue.
			if(!isset($map[$input])) {
				continue;
			}
			$real = $map[$input];
			if(!in_array($real, $this->selected)) {
				$this->selected[] = $real;
				continue;
			}
		
			if(in_array($real, $this->selected)) {
				$new = array();
				foreach ($this->selected as $value) {
					if($real==$value) {
						continue;
					}
					$new[] = $value;
				}
				$this->selected = $new;
			}

		}
	}
}