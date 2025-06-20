<?php

declare(strict_types=1);
/**
 * This file is part of ymiras.
 *
 * @link     https://www.ymiras.com
 * @contact  support@ymiras.com
 * @license  https://github.com/ymiras/think-captcha/licenses
 */

namespace Ymiras\ThinkCaptcha;

use think\Cache;
use think\Config;
use think\facade\Validate;
use think\Service;

class ServiceProvider extends Service
{
    public function register(): void
    {
        $this->app->bind('captcha', function () {
            return new Captcha(
                $this->app->get(Cache::class),
                $this->app->get(Config::class)
            );
        });
    }

    public function boot(): void
    {
        Validate::maker(function ($validate) {
            $validate->extend('captcha', function ($value, $rule, $data = []) {
                $rule = $rule ?? 'uniq_key';
                $key = $data[$rule] ?? '';

                return app('captcha')->verify($key, $value);
            });
        });
    }
}
