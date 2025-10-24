<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
namespace plibv4\input;
use RuntimeException;
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
	private array $output = array();
	private array $input = array();
	private int $posDefined = 0;
	private int $posCheck = 0;
	private bool $passthru = false;
	public function passthru(): void {
		$this->passthru = true;
	}
	
	function expectOutput(string $output): void {
		$this->output[$this->posDefined] = $output;
		$this->posDefined++;
	}
	
	/**
	 * @return void
	 */
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
	
	function addInput(string $input): void {
		$this->input[$this->posDefined] = $input;
		$this->posDefined++;
	}
	
	function get(): string {
		if($this->passthru === true) {
			$read = fgets(STDIN);
			if($read === false) {
				throw new RuntimeException("unable to read from STDIN");
			}
		return $read;
		}
		if(!isset($this->input[$this->posCheck])) {
			throw new StdioInterceptException("No input defined for step ".$this->posCheck);
		}
		$this->posCheck++;
	return $this->input[$this->posCheck-1];
	}
}