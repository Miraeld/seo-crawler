<?php
/**
 * Handles database-related operations for the SEO Crawler plugin.
 *
 * This class extends the SeoCrawlerAbstract class and adds functionality
 * for creating and dropping the database table used by the plugin.
 */

namespace SEO_Crawler\Db;

use SEO_Crawler\SeoCrawlerAbstract;

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
		$this->wpdb->query( $this->wpdb->prepare( $sql ) );
	}

}
