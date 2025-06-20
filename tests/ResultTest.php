<?php

declare(strict_types=1);
/**
 * This file is part of ymiras.
 *
 * @link     https://www.ymiras.com
 * @contact  support@ymiras.com
 * @license  https://github.com/ymiras/think-captcha/licenses
 */

namespace Ymiras\ThinkCaptcha\Tests;

use Ymiras\ThinkCaptcha\Result;

/**
 * @internal
 * @coversNothing
 */
class ResultTest extends TestCase
{
    public function testGetters()
    {
        $result = new Result('image_data', 'key', 'phrase');
        $this->assertEquals('image_data', $result->image());
        $this->assertEquals('key', $result->key());
        $this->assertEquals('phrase', $result->phrase());
    }
}
