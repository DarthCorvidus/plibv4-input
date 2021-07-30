<?php
interface SingleSelectModel {
	const SOURCE = 1;
	const ZERO = 2;
	const NATURAL = 3;
	/**
	 * load
	 * 
	 * load is called before a list of available items will be displayed, and
	 * again, if the user enters an invalid value (empty when mandatory or
	 * invalid index), to reload values that may have changed since last
	 * displaying the available items.
	 * Note - if load is very expensive, you may implement mechanisms to only
	 * load the items once.
	 */
	function load();
	/**
	 * getValues
	 * 
	 * Get selectable items as associative or numeric array.
	 */
	function getValues(): array;
	/**
	 * getDefault
	 * 
	 * Get a default value, which will be marked in the item list and will be
	 * used if the user skips the dialogue by just pressing enter.
	 */
	function getDefault(): string;
	/**
	 * getIndexStyle
	 * 
	 * By default, SingleSelect will directly display the keys & values,
	 * which is SingleSelectModel::SOURCE. This may yield ugly results if using
	 * primary keys from a database, as the sort order of values & keys will
	 * most likely not fit together.
	 * SingleSelectModel::ZERO replaces the source indices with 0-based indices.
	 * SingleSelectModel::NATURAL starts with 1 instead of 0 to accomodate
	 * organics.
	 */
	function getIndexStyle(): int;
	/**
	 * isMandatory
	 * 
	 * Determines if the user is allowed to skip the dialogue.
	 */
	function isMandatory(): bool;
	
	/**
	 * getQuestion
	 * 
	 * A short line to be displayed above the item list, to give the user more
	 * context.
	 */
	function getQuestion(): string;
}