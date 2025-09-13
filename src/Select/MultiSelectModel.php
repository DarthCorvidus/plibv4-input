<?php
namespace plibv4\input;
interface MultiSelectModel extends SelectModel {
	function getDefault(): array;
	/**
	 * get continue
	 * 
	 * Ideally, one character that is not part of the selectable values, like
	 * c for continue or x, to allow the user to continue once he is done
	 * selecting values.
	 * If left empty - string("") - the user may quit the item list without
	 * selecting a value.
	 */
	function getContinue(): string;
}