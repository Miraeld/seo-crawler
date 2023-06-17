<?php

namespace SEO_Crawler\Crawl;

use SEO_Crawler\SeoCrawlerAbstract;
use SEO_Crawler\Db\DbTable;
use SEO_Crawler\Exceptions\CrawlException;
use SEO_Crawler\Exceptions\UnexpectedStatusCodeException;
use SEO_Crawler\Exceptions\InvalidParameterTypeException;
use SEO_Crawler\Utils\Common;
use SEO_Crawler\Utils\FileSystem;
use WP_Error;

/**
 * Class Crawler
 * Handles the crawling operations for SEO
 */
class Crawler extends SeoCrawlerAbstract {

	/**
	 * Instance of DbTable to communicate with the database
	 *
	 * @var DbTable
	 */
	private $crawler_db;

	/**
	 * Represents the homepage URL of the website
	 *
	 * @var string
	 */
	protected $home_url;

	/**
	 * Instance of FileSystem to be able to manipulate files.
	 *
	 * @var FileSystem
	 */
	protected $file_system;
	/**
	 * CrawlSeoCrawler constructor.
	 * Initializes the home_url property
	 *
	 * @param FileSystem|null $file_system Dependency injection.
	 * @throws InvalidParameterTypeException If $file_system does not have the right type.
	 */
	public function __construct( $file_system = null ) {
		parent::__construct();
		$this->home_url   = get_home_url();
		$this->crawler_db = new DbTable();
		if ( null === $file_system ) {
			$this->file_system = new FileSystem();
		} elseif ( $file_system instanceof FileSystem ) {
			$this->file_system = $file_system;
		} else {
			throw new InvalidParameterTypeException( __FUNCTION__, '$file_system should be a FileSystem type or null.' );
		}
	}

	/**
	 * Executes the crawling process
	 *
	 * @return void
	 */
	public function executeCrawl() {
		$this->deletePreviousResults();
		$internal_links = array_unique( $this->crawlInternalLinks( $this->home_url ) );
		$this->storeResults( $internal_links );
		$this->saveHomePageAsHtml();
		$this->deleteSitemapFile();
		$this->createSitemapFile( $internal_links );
	}

	/**
	 * Deletes the previous results from the database.
	 *
	 * @return bool true if it worked, false if there has been an error.
	 */
	protected function deletePreviousResults() {
		return $this->crawler_db->delete_previous_results();
	}

	/**
	 * Deletes the existing sitemap file if it exists
	 *
	 * @return void
	 */
	protected function deleteSitemapFile() {
		$sitemap_file = ABSPATH . 'sitemap.html';
		$this->file_system->delete_file( $sitemap_file );
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
				if ( Common::is_internal_link( $href ) ) {
					$internal_links[] = $href;
				}
			}
		} catch ( \Exception $e ) {
			throw new CrawlException( $e->getMessage() );
		}

		return $internal_links;
	}

	/**
	 * Stores the crawled links into the database
	 *
	 * @param array $links The links to store.
	 *
	 * @return bool
	 * @throws InvalidParameterTypeException When $links is not an array.
	 */
	protected function storeResults( $links ) {
		if ( ! is_array( $links ) ) {
			throw new InvalidParameterTypeException( __FUNCTION__, '$links is expected to be an array, ' . gettype( $links ) . ' given' );
		}

		foreach ( $links as $link ) {
			$this->crawler_db->insert(
				[ 'url' => $link ],
				[ '%s' ]
			);
		}

		return true;
	}

	/**
	 * Saves the content of the home page as an HTML file
	 *
	 * @return bool true on success, false on error
	 */
	protected function saveHomePageAsHtml() {
		$response = wp_remote_get( $this->home_url );
		if ( ! is_wp_error( $response ) ) {
			$homepage_content = wp_remote_retrieve_body( $response );
			$html_file_path   = ABSPATH . 'homepage.html';

			return $this->file_system->create_file( $homepage_content, $html_file_path );
		}

		return false;
	}


	/**
	 * Creates a sitemap file with the crawled links
	 *
	 * @param array $links The links to include in the sitemap.
	 *
	 * @return bool true on success, false on error
	 */
	protected function createSitemapFile( $links ) {
		$sitemap_file = ABSPATH . 'sitemap.html';
		$sitemap_html = '<ul>';
		foreach ( $links as $link ) {
			$sitemap_html .= '<li>' . esc_html( $link ) . '</li>';
		}
		$sitemap_html .= '</ul>';

		return $this->file_system->create_file( $sitemap_html, $sitemap_file );
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
