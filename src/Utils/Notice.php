<?php

namespace SEO_Crawler\Utils;

use SEO_Crawler\Utils\Render;

/**
 * Class Notice
 *
 * This class is used to create and render notification messages to the user in the WordPress admin area.
 * It extends the `NoticeEnum` class and uses the `Render` class to display the message.
 *
 * @package SEO_Crawler\Utils
 */
class Notice extends NoticeEnum {
	/**
	 * The status of the notice.
	 *
	 * The status should be a member of NoticeEnum.
	 *
	 * @var string
	 */
	protected $status;

	/**
	 * The message to be displayed to the user.
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * Notice constructor.
	 *
	 * Validates the status and sets the status and message properties.
	 *
	 * @param string $status The status of the notice.
	 * @param string $message The message to be displayed.
	 * @throws \InvalidArgumentException If the status is not a valid member of NoticeEnum.
	 */
	public function __construct( $status, $message ) {
		if ( ! self::is_valid_status( $status ) ) {
			throw new \InvalidArgumentException( 'Invalid status value' );
		}
		$this->status  = $status;
		$this->message = $message;
	}

	/**
	 * Renders the notice using the Render class.
	 */
	public function render() {
		Render::render_view( 'admin/_partials/notice', [ 'notice' => $this ] );
	}

	/**
	 * Returns the status of the notice.
	 *
	 * @return string The status of the notice.
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Returns the message of the notice.
	 *
	 * @return string The message of the notice.
	 */
	public function get_message() {
		return $this->message;
	}
}
