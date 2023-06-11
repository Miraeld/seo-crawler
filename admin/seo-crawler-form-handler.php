<?php

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Handles the form submission for the SEO Crawler.
 *
 * This function checks for the presence and validity of the nonce. If the nonce is valid,
 * it checks which button was pressed and calls the appropriate function.
 *
 * @return void|WP_Error Returns a WP_Error object if an error occurs, or void on success.
 */
function seo_crawler_form_handler() {
	/**
	 * Check if our nonce is set. If not, return an error.
	 */
	if ( ! isset( $_POST['seo_crawler_nonce'] ) ) {
		return new WP_Error( 'missing_nonce', 'The nonce field is missing.', [ 'status' => 400 ] );
	}

	/**
	 * Verify that the nonce is valid. If not, return an error.
	 */
	$nonce = isset( $_POST['seo_crawler_nonce'] ) ? wp_unslash( sanitize_text_field( $_POST['seo_crawler_nonce'] ) ) : ''; // phpcs:ignore WordPress.Security -- The nonce is being wp_unslash && verified after.
	if ( ! wp_verify_nonce( $nonce, 'seo_crawler_nonce' ) ) {
		return new WP_Error( 'invalid_nonce', 'The nonce verification failed.', [ 'status' => 400 ] );
	}

	/**
	 * Check if the current user has the necessary permissions. If not, return an error.
	 */
	if ( ! current_user_can( 'manage_options' ) ) {
		return new WP_Error( 'insufficient_permissions', 'You do not have sufficient permissions to access this page.', [ 'status' => 403 ] );
	}

	/**
	 * Check if our buttons are set. If neither button is set, exit the function.
	 */
	if ( ! isset( $_POST['crawl_button'] ) && ! isset( $_POST['results_button'] ) ) {
		return;
	}

	/**
	 * Handle the form data. Call the appropriate function based on which button was pressed.
	 */
	if ( isset( $_POST['crawl_button'] ) ) {
		seo_crawler_crawl_task( true );
		seo_crawler_schedule_crawl_event();
	} elseif ( isset( $_POST['results_button'] ) ) {
		seo_crawler_display_latest_results();
	}
}


/**
 * Hook the form handler function to the admin_init action.
 */
add_action( 'admin_init', 'seo_crawler_form_handler' );
