<?php

namespace SEO_Crawler\Exceptions;

/**
 * Class CrawlException
 *
 * Exception class for crawl-related errors.
 */
class CrawlException extends \Exception {
	/**
	 * CrawlException constructor.
	 *
	 * @param string          $message  The exception message.
	 * @param int             $code     The exception code.
	 * @param \Exception|null $previous The previous exception, if any.
	 */
	public function __construct( $message, $code = 0, \Exception $previous = null ) {
		parent::__construct( 'An error has occurred while trying to crawl your page: ' . $message, $code, $previous );
	}
}
