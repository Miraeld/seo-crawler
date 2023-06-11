<?php

namespace SEO_Crawler\Utils;

class Render {

	/**
	 * Private constructor to prevent instantiation.
	 */
	private function __construct() {
	}

	/**
	 * Renders a view file with the provided parameters.
	 *
	 * @param string $view    The name of the view file to render (without the .php extension).
	 * @param array  $params  Optional. An associative array of parameters to pass to the view. The keys will be used as variable names in the view.
	 * @return void
	 */
	public static function render_view( $view, $params = [] ) {
		$view_path = dirname( dirname( plugin_dir_path( __FILE__ ) ) ) . "/views/{$view}.php";

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

		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- Dynamic content is properly escaped in the view
	}
}
