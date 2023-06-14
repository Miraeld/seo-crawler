<?php

namespace SEO_Crawler\Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use SEO_Crawler\Utils\Notice;
use SEO_Crawler\Utils\Render;
use Brain\Monkey\Functions;

/**
 * Class NoticeTest
 *
 * Test cases for the Notice class.
 */
class NoticeTest extends TestCase {
    /**
     * Set up the test environment.
     *
     * @return void
     */
    public function setUp(): void {
        Functions\when('plugin_dir_path')->alias(function ($file) {
            return dirname($file);
        });
    }

    /**
     * Test getting the status of the notice.
     *
     * @return void
     */
    public function testGetStatus() {
        $status = 'warning';
        $message = 'This is a warning notice.';
        $notice = new Notice($status, $message);

        $this->assertEquals($status, $notice->get_status());
    }

    /**
     * Test getting the message of the notice.
     *
     * @return void
     */
    public function testGetMessage() {
        $status = 'success';
        $message = 'This is an informational notice.';
        $notice = new Notice($status, $message);

        $this->assertEquals($message, $notice->get_message());
    }

    /**
     * Test constructing a notice with valid status values.
     *
     * @return void
     */
    public function testConstructNotice() {
        $inputs = ['success', 'warning', 'error'];
        foreach ($inputs as $input) {
            $notice = new Notice($input, 'Message');
            $this->assertEquals($input, $notice->get_status());
        }
    }

    /**
     * Test constructing a notice with an unknown status.
     *
     * @return void
     */
    public function testConstructNoticeWithUnknownStatus() {
        $this->expectException(\InvalidArgumentException::class);
        $status = 'info';
        $message = 'This is an informational notice.';
        new Notice($status, $message);
    }
}
