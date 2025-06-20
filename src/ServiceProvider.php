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
use think\Service;

class ServiceProvider extends Service
{
    public function register()
    {
        $this->app->bind('captcha', function ($app) {
            return new Captcha(
                $this->app->get(Cache::class),
                $this->app->get(Config::class)
            );
        });
    }
}
