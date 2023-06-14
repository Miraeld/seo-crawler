<?php

namespace SEO_Crawler\Exceptions;

/**
 * Class InvalidParameterTypeException
 *
 * Exception thrown when a function receives a parameter of an invalid type.
 */
class InvalidParameterTypeException extends \InvalidArgumentException {

	/**
	 * The name of the function that received the invalid parameter.
	 *
	 * @var string
	 */
	private $function_name;

	/**
	 * InvalidParameterTypeException constructor.
	 *
	 * @param string          $function_name The name of the function that received the invalid parameter.
	 * @param string          $message The Exception message to throw.
	 * @param int             $code The Exception code.
	 * @param \Exception|null $previous The previous exception used for the exception chaining.
	 */
	public function __construct( $function_name, $message = '', $code = 0, \Exception $previous = null ) {
		$this->function_name = $function_name;
		$message             = 'Invalid parameter type in function: ' . $function_name . '. ' . $message;
		parent::__construct( $message, $code, $previous );
	}
}
