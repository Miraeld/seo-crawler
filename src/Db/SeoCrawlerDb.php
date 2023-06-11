<?php
/**
 * Handles database-related operations for the SEO Crawler plugin.
 *
 * This class extends the SeoCrawlerAbstract class and adds functionality
 * for creating and dropping the database table used by the plugin.
 */

namespace SEO_Crawler\Db;

use SEO_Crawler\SeoCrawlerAbstract;
use SEO_Crawler\Url\SeoCrawlerUrl;

class SeoCrawlerDb extends SeoCrawlerAbstract {
	/**
	 * Creates the database table used by the plugin.
	 *
	 * This method checks for the existence of the table, and if it doesn't exist,
	 * it creates it with the appropriate structure.
	 *
	 * @return void
	 */
	public function create_table() {
		$charset_collate = $this->wpdb->get_charset_collate();
		$sql             = "CREATE TABLE {$this->table} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            url varchar(255) NOT NULL,
			crawl_date datetime DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY id (id)
        ) {$charset_collate};";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Drops the database table used by the plugin.
	 *
	 * This method checks for the existence of the table, and if it exists,
	 * it drops it from the database.
	 *
	 * @return void
	 */
	public function drop_table() {
		$sql = "DROP TABLE IF EXISTS {$this->table};";
		$this->wpdb->query( $this->wpdb->prepare( $sql ) ); // phpcs:ignore WordPress.DB.PreparedSQL -- The SQL is already prepared.
	}

	/**
	 * Retrieves and displays the latest results
	 *
	 * @return array Contains the latest crawl from the database, empty if there are none.
	 */
	public function fetch_all() {
		$results = $this->wpdb->get_results( "SELECT * FROM {$this->table}" ); // phpcs:ignore WordPress.DB.PreparedSQL -- There is no need to prepare for this sql

		$output = [];
		foreach ( $results as $result ) {
			$output[] = new SeoCrawlerUrl(
				$result->url,
				$result->crawl_date
			);
		}
		return $output;
	}

	/**
	 * Deletes the previous results from the database
	 *
	 * @return void
	 */
	public function delete_previous_results() {
		$this->wpdb->query( "TRUNCATE TABLE {$this->table}" ); // phpcs:ignore WordPress.DB.PreparedSQL -- There is no need to prepare for this sql
	}

	/**
	 * Insert a new row into the specified table.
	 *
	 * @param array  $fields The data to insert (in column => value pairs).
	 * @param string $format An array of formats to be mapped to each of the value in $data.
	 *                  If string, that format will be used for all of the values in $data.
	 *                  A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                  If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
	 * @return void
	 */
	public function insert( $fields, $format = null ) {
		$this->wpdb->insert(
			$this->table,
			$fields,
			$format
		);
	}
}
