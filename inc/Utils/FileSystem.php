<?php

namespace SEO_Crawler\Utils;

/**
 * Class FileSystem
 * Handles file system operations for the SEO Crawler
 */
class FileSystem {

	/**
	 * Initializes the necessary components for file system operations
	 *
	 * @return void
	 */
	public function __construct() {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;
	}

	/**
	 * Deletes a file if it exists
	 *
	 * @param string $file_path The path of the file to delete.
	 * @return void
	 */
	public function delete_file( $file_path ) {
		global $wp_filesystem;

		if ( $wp_filesystem->exists( $file_path ) ) {
			$wp_filesystem->delete( $file_path );
		}
	}

	/**
	 * Creates a file with the specified content and path
	 *
	 * @param string $content The content to be written to the file.
	 * @param string $path The path where the file should be created.
	 * @return bool true on success, false on error
	 */
	public function create_file( $content, $path ) {
		global $wp_filesystem;

		if ( $wp_filesystem ) {
			return $wp_filesystem->put_contents( $path, $content, FS_CHMOD_FILE );
		} else {
			return false;
		}
	}
}
