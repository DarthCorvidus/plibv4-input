<?php
namespace plibv4\input;
interface SelectModel {
	/**
	 * getIndexStyle
	 * 
	 * Used to determine whether key values should be used as is or replaced by
	 * zero or 1 indexed numbers.
	 * 
	 * @return IndexStyle
	 */
	function getIndexStyle(): IndexStyle;
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
	
	
}