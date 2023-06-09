<?php

defined( 'ABSPATH' ) || exit;

// Composer autoload.
if ( file_exists( dirname( plugin_dir_path( __FILE__ ) ) . '/vendor/autoload.php' ) ) {
	require dirname( plugin_dir_path( __FILE__ ) ) . '/vendor/autoload.php';
}

/**
 * Renders a view with the provided parameters.
 *
 * @param string $view   The name of the view file to render.
 * @param array  $params An optional array of parameters to pass to the view.
 *
 * @return void
 */
function seo_crawler_render_view( $view, $params = [] ) {
	$view_path = dirname( plugin_dir_path( __FILE__ ) ) . "/views/{$view}.php";

	// Create a closure that includes the view file and imports the variables.
	$render = function() use ( $view_path, $params ) {
		// Import the parameters as variables.
		foreach ( $params as $key => $value ) {
			$$key = $value;
		}

		// Include the view file.
		include $view_path;
	};

	// Capture the output of the closure.
	ob_start();
	$render();
	$content = ob_get_clean();

	$allowed_html = [
		'form'   => [
			'method' => [],
			'action' => [],
		],
		'button' => [
			'type'  => [],
			'name'  => [],
			'class' => [],
		],
		'p'      => [],
		'h1'     => [],
		'div'    => [
			'class' => [],
			'id'    => [],
		],
		'input'  => [
			'type'  => [],
			'name'  => [],
			'class' => [],
			'id'    => [],
			'value' => [],
		],
		'h2'     => [],
		'ul'     => [],
		'li'     => [],
		'span'   => [],
	];

	echo wp_kses( $content, $allowed_html );
}
