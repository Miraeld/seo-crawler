<?php

namespace SEO_Crawler\Exceptions;

/**
 * Class UnexpectedStatusCodeException
 *
 * Exception thrown when an unexpected HTTP status code is received.
 */
class UnexpectedStatusCodeException extends \Exception {

	/**
	 * UnexpectedStatusCodeException constructor.
	 *
	 * @param int             $status_code The HTTP status code.
	 * @param string          $message The Exception message to throw.
	 * @param int             $code The Exception code.
	 * @param \Exception|null $previous The previous throwable used for the exception chaining.
	 */
	public function __construct( $status_code, $message = '', $code = 0, \Exception $previous = null ) {
		$message = 'Received unexpected status code: ' . $status_code . '. ' . $message;
		parent::__construct( $message, $code, $previous );
	}
}
