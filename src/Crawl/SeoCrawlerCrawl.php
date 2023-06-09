<?php

namespace SEO_Crawler\Crawl;

use SEO_Crawler\SeoCrawlerAbstract;
use SEO_Crawler\Db\SeoCrawlerDb;
use SEO_Crawler\Exceptions\CrawlException;
use SEO_Crawler\Exceptions\UnexpectedStatusCodeException;
use SEO_Crawler\Exceptions\InvalidParameterTypeException;
use WP_Error;

/**
 * Class SeoCrawlerCrawl
 * Handles the crawling operations for SEO
 */
class SeoCrawlerCrawl extends SeoCrawlerAbstract {

	/**
	 * Instance of SeoCrawlerDb to communicate with the database
	 *
	 * @var SeoCrawlerDb
	 */
	private $crawler_db;

	/**
	 * Represents the homepage URL of the website
	 *
	 * @var string
	 */
	protected $home_url;

	/**
	 * CrawlSeoCrawler constructor.
	 * Initializes the home_url property
	 */
	public function __construct() {
		parent::__construct();
		$this->home_url   = get_home_url();
		$this->crawler_db = new SeoCrawlerDb();
	}

	/**
	 * Executes the crawling process
	 *
	 * @return void
	 */
	public function executeCrawl() {
		$this->deletePreviousResults();
		$this->deleteSitemapFile();
		$internal_links = array_unique( $this->crawlInternalLinks( $this->home_url ) );
		$this->storeResults( $internal_links );
		$this->saveHomePageAsHtml();
		$this->createSitemapFile( $internal_links );
	}

	/**
	 * Deletes the previous results from the database
	 *
	 * @return void
	 */
	protected function deletePreviousResults() {
		$this->crawler_db->delete_previous_results();
	}

	/**
	 * Deletes the existing sitemap file if it exists
	 *
	 * @return void
	 */
	protected function deleteSitemapFile() {
		$sitemap_file = ABSPATH . 'sitemap.html';
		if ( file_exists( $sitemap_file ) ) {
			unlink( $sitemap_file );
		}
	}

	/**
	 * Crawls the internal links of a given URL
	 *
	 * @param string $url The URL to crawl.
	 *
	 * @return array The internal links found
	 * @throws CrawlException When an error occurs during crawling.
	 * @throws UnexpectedStatusCodeException When the status code received while crawling is not 200.
	 */
	protected function crawlInternalLinks( $url ) {
		$internal_links = [];
		try {
			// Fetch the web page content.
			$response = wp_remote_get( $url );

			if ( $response instanceof WP_Error ) {
				throw new CrawlException( $response->get_error_message() );
			}

			$status_code = wp_remote_retrieve_response_code( $response );
			if ( 200 !== $status_code ) {
				throw new UnexpectedStatusCodeException( $status_code, 'Expecting a 200 response code.' );
			}

			$body = wp_remote_retrieve_body( $response );

			// Use DOMDocument to parse the HTML and extract the links.
			$dom = new \DOMDocument();
			libxml_use_internal_errors( true );
			$dom->loadHTML( $body );
			libxml_clear_errors();

			$anchors = $dom->getElementsByTagName( 'a' );

			foreach ( $anchors as $anchor ) {
				$href = $anchor->getAttribute( 'href' );

				// Check if the link is internal.
				if ( $this->isInternalLink( $href ) ) {
					$internal_links[] = $href;
				}
			}
		} catch ( \Exception $e ) {
			throw new CrawlException( $e->getMessage() );
		}

		return $internal_links;
	}

	/**
	 * Checks if a given URL is an internal link.
	 *
	 * @param string $url The URL to check.
	 *
	 * @return bool Whether the URL is an internal link or not
	 * @throws InvalidParameterTypeException When the $url parameter is not a string.
	 */
	protected function isInternalLink( $url ) {
		if ( ! is_string( $url ) ) {
			throw new InvalidParameterTypeException( __FUNCTION__, '$url is expected to be a string, ' . gettype( $url ) . ' given' );
		}

		return strpos( $url, $this->home_url ) === 0;
	}

	/**
	 * Stores the crawled links into the database
	 *
	 * @param array $links The links to store.
	 *
	 * @return void
	 * @throws InvalidParameterTypeException When $links is not an array.
	 */
	protected function storeResults( $links ) {
		if ( ! is_array( $links ) ) {
			throw new InvalidParameterTypeException( __FUNCTION__, '$links is expected to be an array, ' . gettype( $links ) . ' given' );
		}

		foreach ( $links as $link ) {
			$this->wpdb->insert(
				$this->table,
				[ 'url' => $link ],
				[ '%s' ]
			);
		}
	}

	/**
	 * Saves the content of the home page as an HTML file
	 *
	 * @return void
	 */
	protected function saveHomePageAsHtml() {
		$response = wp_remote_get( $this->home_url );
		if ( ! is_wp_error( $response ) ) {
			$homepage_content = wp_remote_retrieve_body( $response );
			$html_file_path   = ABSPATH . 'homepage.html';

			WP_Filesystem();
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem();
			}

			if ( $wp_filesystem ) {
				$wp_filesystem->put_contents( $html_file_path, $homepage_content, FS_CHMOD_FILE );
			}
		}
	}


	/**
	 * Creates a sitemap file with the crawled links
	 *
	 * @param array $links The links to include in the sitemap.
	 *
	 * @return void
	 */
	protected function createSitemapFile( $links ) {
		$sitemap_file = ABSPATH . 'sitemap.html';
		$sitemap_html = '<ul>';
		foreach ( $links as $link ) {
			$sitemap_html .= '<li>' . esc_html( $link ) . '</li>';
		}
		$sitemap_html .= '</ul>';

		WP_Filesystem();
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		if ( $wp_filesystem ) {
			$wp_filesystem->put_contents( $sitemap_file, $sitemap_html, FS_CHMOD_FILE );
		}
	}

	/**
	 * Retrieves and displays the latest results
	 *
	 * @return array Contains the latest crawl from the database, empty if there are none.
	 */
	public function getLatestResults() {
		return $this->crawler_db->fetch_all();
	}
}
