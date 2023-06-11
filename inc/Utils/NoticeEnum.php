<?php
namespace SEO_Crawler\Utils;

/**
 * Abstract Class NoticeEnum
 *
 * This abstract class defines the possible status values for a `Notice`. The status values are represented as class constants.
 * This class also provides a method to validate if a given status value is a valid member of the `NoticeEnum`.
 *
 * @package SEO_Crawler\Utils
 */
abstract class NoticeEnum {

	/**
	 * Represents a 'success' status for a notice.
	 */
	const SUCCESS = 'success';

	/**
	 * Represents an 'error' status for a notice.
	 */
	const ERROR = 'error';

	/**
	 * Represents a 'warning' status for a notice.
	 */
	const WARNING = 'warning';

	/**
	 * Checks if a given status value is a valid member of the NoticeEnum.
	 *
	 * @param string $status The status value to check.
	 * @return bool True if the status is valid, false otherwise.
	 */
	public static function is_valid_status( $status ) {
		$reflection_class = new \ReflectionClass( static::class );
		$valid_statuses   = $reflection_class->getConstants();
		return in_array( $status, $valid_statuses, true );
	}
}

