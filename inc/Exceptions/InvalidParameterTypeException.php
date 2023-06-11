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
    private $functionName;

    /**
     * InvalidParameterTypeException constructor.
     *
     * @param string $functionName The name of the function that received the invalid parameter.
     * @param string $message The Exception message to throw.
     * @param int $code The Exception code.
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct($functionName, $message = "", $code = 0, \Throwable $previous = null) {
        $this->functionName = $functionName;
        $message = 'Invalid parameter type in function: ' . $functionName . '. ' . $message;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the name of the function that received the invalid parameter.
     *
     * @return string The name of the function.
     */
    public function getFunctionName() {
        return $this->functionName;
    }
}