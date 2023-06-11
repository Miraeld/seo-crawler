<?php

namespace SEO_Crawler;

/**
 * SeoCrawlerAbstract
 *
 * This class provides the basic structure and functionalities required for SEO Crawling operations.
 *
 * @package  SEO_Crawler
 */
abstract class SeoCrawlerAbstract {
	/**
	 * A WordPress Database object, which is used to interact with the database.
	 *
	 * @var wpdb
	 */
	protected $wpdb;

	/**
	 * The name of the database table where the results are stored.
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * The name of the table in the database, without the prefix.
	 *
	 * @var string
	 */
	const TABLE_NAME = 'seo_crawler_results';

	/**
	 * SeoCrawlerAbstract constructor.
	 *
	 * Initializes the database object, and sets the table name.
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb  = $wpdb;
		$this->table = $this->wpdb->prefix . self::TABLE_NAME;
	}
}
