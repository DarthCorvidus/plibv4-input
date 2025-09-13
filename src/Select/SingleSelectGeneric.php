<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
namespace plibv4\input;
/**
 * SingleSelectGeneric
 * 
 * Generic implementation of SingleSelectModel.
 */
class SingleSelectGeneric implements SingleSelectModel {
	private $question;
	private $default = "";
	private $mandatory = TRUE;
	private $values = array();
	private IndexStyle $style = IndexStyle::SOURCE;
	/**
	 * 
	 * @param string $question Question that will be displayed above the item list
	 */
	public function __construct(string $question) {
		$this->question = $question;
	}
	
	/**
	 * setDefault
	 * 
	 * Set default value which will be used if the user does not select a value.
	 * @param string $default
	 */
	public function setDefault(string $default) {
		$this->default = $default;
	}
	
	public function getDefault(): string {
		return $this->default;
	}

	/**
	 * setIndexStyle
	 * 
	 * @param IndexStyle $style
	 */
	public function setIndexStyle(IndexStyle $style) {
		$this->style = $style;
	}
	
	public function getIndexStyle(): IndexStyle {
		return $this->style;
	}

	/**
	 * addValue
	 * 
	 * Adds a single key value pair to be used in the list of selectable items.
	 * @param string $key
	 * @param string $value
	 */
	public function addValue(string $key, string $value) {
		$this->values[$key] = $value;
	}
	
	/**
	 * setValues
	 * 
	 * Set values via array. Preexisting values will be overwritten.
	 * @param array $values
	 */
	public function setValues(array $values) {
		$this->values = $values;
	}
	
	public function getValues(): array {
		return $this->values;
	}

	/**
	 * setMandatory
	 * 
	 * Set the SingleSelect dialogue to be mandatory or not.
	 * @param bool $mandatory
	 */
	public function setMandatory(bool $mandatory) {
		$this->mandatory = $mandatory;
	}
	
	public function isMandatory(): bool {
		return $this->mandatory;
	}

	public function load() {
	}

	/**
	 * setQuestion
	 * 
	 * Changes the question.
	 * @param string $question
	 */
	public function setQuestion(string $question) {
		$this->question = $question;
	}
	
	public function getQuestion(): string {
		return $this->question;
	}
}