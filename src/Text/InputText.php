<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
namespace plibv4\input;
use Convert;
use Validate;
use ValidateException;

/**
 * InputText
 * 
 * Gets input from STDIN. Allows to set default values, force mandatory input,
 * validate and convert input.
 */
class InputText {
	private $default = "";
	private $prompt = "%s\n> ";
	private $promptDefaulted = "%s (%s)\n> ";
	private $stdio;
	private $mandatory;
	private $validate;
	private $convertInput;
	private $convertDefault;
	/**
	 * 
	 * @param string $question Question to be displayed over prompt.
	 */
	function __construct(string $question) {
		$this->question = $question;
		$this->stdio = new StdioIntercept();
		$this->stdio->passthru();
	}
	
	/**
	 * Set StdioIntercept
	 * 
	 * StdioIntercept can be used to do unit tests by predefining input and expected
	 * output. If output doesn't match, stdio will throw an exception.
	 * If setStdioIntercept is not called, a default instance of StdioIntercept will be used
	 * that passes through input from STDIN and output to STDOUT.
	 * @param StdioIntercept $stdio
	 */
	function setStdioIntercept(StdioIntercept $stdio) {
		$this->stdio = $stdio;
	}
	
	/**
	 * Set Convert for input
	 * 
	 * Set convert class which is called on user input. This allows to handle
	 * user input as early as possible, while getting proper values for further
	 * processing (example: converting 03.10.1990 to 1990-10-03).
	 * Note that convert is called after validate.
	 * @param Convert $convert
	 */
	function setInputConvert(Convert $convert) {
		$this->convertInput = $convert;
	}
	
	/**
	 * Set Validate for input
	 * 
	 * setValidate expects an implementation of Validate which will then be
	 * called on user input. If Validate fails, the user will be displayed 
	 * 
	 * @param Validate $validate
	 */
	function setValidate(Validate $validate) {
		$this->validate = $validate;
	}
	function setInputStream($stream) {
		$this->inputStream = $stream;
	}
	
	function setDefault(string $default) {
		$this->default = $default;
	}
	
	function setDefaultConvert(Convert $convert) {
		$this->convertDefault = $convert;
	}
	
	function mandatory(bool $mandatory) {
		$this->mandatory = $mandatory;
	}
	
	protected function getPrompt(): string {
		$def = $this->default;
		if($this->convertDefault!=NULL && $this->default!=="") {
			$def = $this->convertDefault->convert($this->default);
		}
		if($def!=="") {
			return sprintf($this->promptDefaulted, $this->question, $def);
		} else {
			return sprintf($this->prompt, $this->question, $def);
		}
	}
	
	function getInput(): string {
		$def = $this->default;
		if($this->convertDefault!=NULL && $this->default!=="") {
			$def = $this->convertDefault->convert($this->default);
		}
		while(true) {
			$this->stdio->put($this->getPrompt());
			$input = trim($this->stdio->get());
			if($input==="" && $this->default!=="") {
				$input = $def;
			}
			if($this->validate!=NULL) {
				try {
					$this->validate->validate($input);
				} catch (ValidateException $ex) {
					$this->stdio->put($ex->getMessage().PHP_EOL);
					continue;
				}
			}
			if($input==="" && $this->default==="" && $this->mandatory) {
				$this->stdio->put("Input is mandatory.\n");
			}
			if($this->convertInput!=NULL && $input!=="") {
				return $this->convertInput->convert($input);
			}
		return $input;
		}
	}
}