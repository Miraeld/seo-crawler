<?php

namespace SEO_Crawler\Url;

use SEO_Crawler\Exceptions\InvalidParameterTypeException;

/**
 * Class SeoCrawlerUrl.
 *
 * Represents a URL crawled by the SEO Crawler.
 */
class SeoCrawlerUrl {
	/**
	 * Url of a result from the crawl.
	 *
	 * @var string The URL.
	 */
	protected $url;

	/**
	 * Creation date and time in the database (= crawl date and time).
	 *
	 * @var \DateTime The creation date.
	 */
	protected $creation_date;

	/**
	 * SeoCrawlerUrl constructor.
	 *
	 * @param string $url The URL.
	 * @param mixed  $date The date the URL was crawled. This can be a string or a DateTime object.
	 * @throws InvalidParameterTypeException If $url is not a string, or $date is not a string or DateTime object.
	 */
	public function __construct( $url, $date ) {
		if ( ! is_string( $url ) ) {
			throw new InvalidParameterTypeException( __FUNCTION__, '$url is expected to be a string, ' . gettype( $url ) . ' given' );
		}
		$this->url = esc_html( $url );

		if ( is_string( $date ) ) {
			$this->creation_date = new \DateTime( $date );
		} elseif ( get_class( $date ) === 'DateTime' ) {
			$this->creation_date = $date;
		} else {
			throw new InvalidParameterTypeException( __FUNCTION__, '$date is expected to be a string or datetime, ' . gettype( $date ) . ' given' );
		}
	}

	/**
	 * Get the URL.
	 *
	 * @return string The URL.
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * Get the creation date.
	 *
	 * @return \DateTime The creation date.
	 */
	public function get_creation_date() {
		return $this->creation_date;
	}
}
