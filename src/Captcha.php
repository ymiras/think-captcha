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

use Gregwar\Captcha\CaptchaBuilder;
use think\Cache;
use think\Config;

class Captcha
{
    protected Cache $cache;

    protected array $config;

    /**
     * Initializes a new instance of the Captcha class.
     *
     * @param Cache $cache the cache implementation used to store and retrieve CAPTCHA values
     * @param Config $config optional configuration for CAPTCHA generation
     */
    public function __construct(Cache $cache, Config $config)
    {
        $this->cache = $cache;
        $this->config = $config->get('captcha');
    }

    /**
     * Generates a CAPTCHA image and stores the result in the cache.
     *
     * @param null|string $key The unique identifier for the CAPTCHA (e.g., user ID or session token).
     * @param array $options Optional configuration options (e.g., width, height, length, font size).
     *                       Defaults to: ['width' => 120, 'height' => 40].
     * @return Result An object containing the CAPTCHA result (phrase, base64 image, etc.).
     */
    public function generate(?string $key = null, array $options = []): Result
    {
        $defaultConfig = $this->config['default'];
        $width = $options['width'] ?? $defaultConfig['width'];
        $height = $options['height'] ?? $defaultConfig['height'];

        $builder = new CaptchaBuilder();
        $builder->build($width, $height);

        $phrase = $builder->getPhrase();
        $image = $builder->get();

        $key = $key ?? $this->generateDefaultCacheKey($phrase);
        $this->cache->set($key, strtolower($phrase), $this->config['cache']['expire']);

        return new Result($image, $key, $phrase);
    }

    /**
     * Verifies whether the provided input matches the stored CAPTCHA value.
     *
     * @param string $key the cache key used to retrieve the stored CAPTCHA value
     * @param string $input the user-provided input to verify against the CAPTCHA
     * @return bool returns true if the input matches the stored value, false otherwise
     */
    public function verify(string $key, string $input): bool
    {
        $stored = $this->cache->get($key);
        if (! $stored) {
            return false;
        }

        return strtolower($input) === $stored;
    }

    /**
     * Generates a default cache key for storing CAPTCHA data.
     *
     * @param string $key The unique identifier for the CAPTCHA (e.g., user ID or session token).
     * @return string the generated cache key in the format: "captcha_{$key}"
     */
    private function generateDefaultCacheKey(string $key): string
    {
        return 'captcha:' . md5($key);
    }
}
