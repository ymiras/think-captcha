<?php

declare(strict_types=1);
/**
 * This file is part of ymiras.
 *
 * @link     https://www.ymiras.com
 * @contact  support@ymiras.com
 * @license  https://github.com/ymiras/think-captcha/licenses
 */
use Ymiras\ThinkCaptcha\Result;

if (! function_exists('captcha_generate')) {
    function captcha_generate(?string $key = null, array $options = []): Result
    {
        return app('captcha')->generate($key, $options);
    }
}

if (! function_exists('captcha_verify')) {
    function captcha_verify(string $key, string $input): bool
    {
        return app('captcha')->verify($key, $input);
    }
}
