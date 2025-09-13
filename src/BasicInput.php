<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <floss@vm01.telton.de>
 * @license LGPL
 */
namespace plibv4\input;
/**
 * BasicInput
 * 
 * There are a lot of use cases where InputText seems overblown, like pressing
 * enter on "Next..." or y/n on a yes and no question. In these cases,
 * BasicInput is an easier to use alternative.
 */
class BasicInput {
	static $stdio;
	private static function getStdioIntercept(): StdioIntercept {
		if(self::$stdio === NULL) {
			self::$stdio = new StdioIntercept();
			self::$stdio->passthru();
		}
	return self::$stdio;
	}
	
	/**
	 * setStdioIntercept
	 * 
	 * Allows to set StdioIntercept globally. Usually, I'd consider this
	 * approach very bad design, but I think it's acceptable here, since this
	 * is actually a feature for testing and not productive use.
	 * @param StdioIntercept $stdio
	 */
	static function setStdioIntercept(StdioIntercept $stdio) {
		self::$stdio = $stdio;
	}
	
	/**
	 * getInput
	 * 
	 * Get input from prompt, with question.
	 * @param type $question
	 * @return type
	 */
	static function getInput(string $question): string {
		self::getStdioIntercept()->put($question.PHP_EOL);
		self::getStdioIntercept()->put("> ");
	return trim(self::getStdioIntercept()->get());
	}
	
	/**
	 * getInputMandatory
	 * 
	 * Get input from prompt, but doesn't allow for empty input.
	 * @param type $question
	 * @return type
	 */
	static function getInputMandatory(string $question): string {
		$input = "";
		self::getStdioIntercept()->put($question.PHP_EOL);
		while($input==="") {
			self::getStdioIntercept()->put("> ");
			$input = trim(self::getStdioIntercept()->get());
		}
	return $input;
	}
	
	/**
	 * getInputDefaulted
	 * 
	 * Get input from prompt and returns default on empty input
	 * @param type $question
	 * @param type $default
	 * @return type
	 */
	static function getInputDefaulted(string $question, string $default): string {
		self::getStdioIntercept()->put($question." (".$default.")".PHP_EOL);
		self::getStdioIntercept()->put("> ");
		$input = trim(self::getStdioIntercept()->get());
		if($input==="") {
			return $default;
		}
	return $input;
	}
}