# ThinkPHP 8 验证码组件（CAPTCHA）

一个轻量级、可定制的验证码生成与验证组件，专为 [ThinkPHP 8](https://github.com/topthink/think) 框架设计。支持生成图像验证码，并通过缓存进行用户输入验证，适用于登录、注册、评论等场景。

---

## 📦 功能特性

- ✅ 支持生成 PNG 格式的 Base64 编码验证码图片
- ✅ 可自定义长度、尺寸样式参数
- ✅ 使用缓存存储验证码内容（兼容 ThinkPHP 的 Cache 实现）
- ✅ 提供简单易用的验证接口
- ✅ 支持 PSR-11 容器规范
- ✅ 支持 PHPUnit 单元测试

---

## 🧰 环境要求

| 项目       | 版本                          |
|----------|-----------------------------|
| PHP      | >= 8.0                      |
| ThinkPHP | ^8.0                        |

---

## 🚀 安装方式

使用 Composer 安装：

```bash
composer require ymiras/think-captcha
```

## 🔧 使用方法

##### 示例：控制器中使用
```php
class CaptchaController
{
    protected $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    // 生成验证码图片
    public function show()
    {
        $key = 'login_captcha';
        // captcha_generate($key); 等同.
        $result = $this->captcha->generate($key);
        
        // 返回图片二进制流
        return response($result->image, 200, ['Content-Type' => 'image/png']);
        
        // 返回 base64
        return response()->json([
            'captcha' => $result->base64,
        ])
    }
    // 验证用户输入
    public function verify(string $input)
    {
        $key = 'login_captcha';
        // 可以替换为
        // captcha_verify($key, $input); 等同.
        // 也可以使用 validate 中 captcha 验证规则。
        // 示例 CaptchaValidate.
        if ($this->captcha->verify($key, $input)) {
            return json(['code' => 200, 'message' => '验证成功']);
        } else {
            return json(['code' => 400, 'message' => '验证失败']);
        }
    }
}

class CaptchaValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'uniq_key' => ['require'],
        'code' => ['require', 'captcha:uniq_key']
    ];
    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'uniq_key.require' => '请输入验证码标识',
        'code.require' => '请输入验证码',
        'code.captcha' => '验证码错误',
    ];
}
```

## 📁 许可协议
MIT License - 详情请查看 LICENSE 文件。