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

use think\contract\Arrayable;

class Result implements Arrayable
{
    protected string $image;

    protected string $key;

    protected string $phrase;

    public function __construct(string $image, string $key, string $phrase)
    {
        $this->image = $image;
        $this->key = $key;
        $this->phrase = $phrase;
    }

    public function toArray(): array
    {
        return [
            'image' => $this->image,
            'key' => $this->key,
            'phrase' => $this->phrase,
            'base64' => $this->base64(),
        ];
    }

    public function image(): string
    {
        return $this->image;
    }

    public function base64(): string
    {
        return 'data:image/jpeg;base64,' . base64_encode($this->image);
    }

    public function key(): string
    {
        return $this->key;
    }

    public function phrase(): string
    {
        return $this->phrase;
    }
}
