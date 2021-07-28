<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */

/**
 * StdioIntercept
 * 
 * This class allows to write test cases for interactive input and resulting
 * output. This is necessary to write proper test cases for Input classes as
 * such, but could be used to write larger scale tests for interactive
 * applications - it has to be noted that this would be tedious at the moment,
 * given the state of StdioIntercept.
 */
class StdioIntercept {
	private $output = array();
	private $input = array();
	private $posDefined = 0;
	private $posCheck = 0;
	private $passthru = false;
	public function passthru() {
		$this->passthru = true;
	}
	
	function expectOutput(string $output) {
		$this->output[$this->posDefined] = $output;
		$this->posDefined++;
	}
	
	function put(string $output) {
		if($this->passthru === true) {
			echo $output;
		return;
		}
		if(!isset($this->output[$this->posCheck])) {
			throw new StdioInterceptException("Undefined output for step ".$this->posCheck.", ".$output." expected.");
		}
		if($this->output[$this->posCheck]!==$output) {
			throw new StdioInterceptException("Expected output ".$this->output[$this->posCheck]." doesn't match ".$output." in step ".$this->posCheck);
		}
	$this->posCheck++;
	}
	
	function addInput(string $input) {
		$this->input[$this->posDefined] = $input;
		$this->posDefined++;
	}
	
	function get() {
		if($this->passthru === true) {
			return fgets(STDIN);
		}
		if(!isset($this->input[$this->posCheck])) {
			throw new StdioInterceptException("No input defined for step ".$this->posCheck);
		}
		$this->posCheck++;
	return $this->input[$this->posCheck-1];
	}
}