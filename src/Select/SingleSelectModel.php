<?php
interface SingleSelectModel {
	/**
	 * getDefault
	 * 
	 * Get a default value, which will be marked in the item list and will be
	 * used if the user skips the dialogue by just pressing enter.
	 */
	function getDefault(): string;
}