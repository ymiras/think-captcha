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

use PHPUnit\Framework\MockObject\Exception;
use think\Cache;
use think\Config;
use Ymiras\ThinkCaptcha\Captcha;
use Ymiras\ThinkCaptcha\Result;

/**
 * @internal
 *
 * @coversNothing
 */
class CaptchaTest extends TestCase
{
    protected Captcha $captcha;

    protected Cache $cache;

    protected Config $config;

    protected array $storage = [];

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->storage = [];

        $this->cache = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->cache->method('set')->willReturnCallback(function ($key, $value) {
            $this->storage[$key] = $value;
            return true;
        });
        $this->cache->method('get')->willReturnCallback(function ($key) {
            return $this->storage[$key] ?? null;
        });

        $this->config = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->config->method('get')->willReturnCallback(function ($name) {
            return [
                'default' => ['width' => 120, 'height' => 40, 'length' => 4],
                'cache' => ['expire' => 300],
            ];
        });

        $this->captcha = new Captcha($this->cache, $this->config);
    }

    public function testSetAndGetCache()
    {
        // 写入缓存
        $key = 'test_key';
        $value = 'abcd';
        $this->cache->set($key, $value);

        // 读取缓存
        $result = $this->cache->get($key);
        $this->assertEquals($value, $result);
    }

    public function testCaptchaGenerateAndVerify()
    {
        $key = 'test_key';
        $result = $this->captcha->generate($key);
        $this->assertInstanceOf(Result::class, $result);

        $cachedValue = $this->cache->get($key);
        $this->assertEquals(strtolower($result->phrase()), $cachedValue);

        $this->assertStringStartsWith('data:image/jpeg;base64,', $result->base64());
        $this->assertTrue($this->captcha->verify($key, $result->phrase()));
        $this->assertFalse($this->captcha->verify($key, 'wrong'));
    }
}
