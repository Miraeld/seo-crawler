<?php

namespace SEO_Crawler\Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use SEO_Crawler\Utils\NoticeEnum;

/**
 * Class NoticeEnumTest
 *
 * Test cases for the NoticeEnum class.
 */
class NoticeEnumTest extends TestCase {
    /**
     * Test is_valid_status method with valid status.
     *
     * @return void
     */
    public function testIsValidStatusWithValidStatus() {
        $isValid = NoticeEnum::is_valid_status(NoticeEnum::SUCCESS);
        $this->assertTrue($isValid);
    }

    /**
     * Test is_valid_status method with invalid status.
     *
     * @return void
     */
    public function testIsValidStatusWithInvalidStatus() {
        $isValid = NoticeEnum::is_valid_status('invalid_status');
        $this->assertFalse($isValid);
    }
}
