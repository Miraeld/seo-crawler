<?php

namespace SEO_Crawler\Utils;

use \SEO_Crawler\Exceptions\InvalidParameterTypeException;

class SeoCrawlerCommon {

	/**
	 * Private constructor to prevent instantiation.
	 */
	private function __construct() {
	}

	/**
	 * Checks if a given URL is an internal link.
	 *
	 * @param string $url The URL to check.
	 *
	 * @return bool Whether the URL is an internal link or not
	 * @throws InvalidParameterTypeException When the $url parameter is not a string.
	 */
	public static function is_internal_link( $url ) {
		if ( ! is_string( $url ) ) {
			throw new InvalidParameterTypeException( __FUNCTION__, '$url is expected to be a string, ' . gettype( $url ) . ' given' );
		}

		return strpos( $url, get_home_url() ) === 0;
	}
}
