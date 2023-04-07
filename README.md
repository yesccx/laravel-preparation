<h1 align="center">Laravel-Preparation</h1>
<p align="center">Laravel Http 前置处理器</p>

# 目录
- [目录](#目录)
- [安装](#安装)
  - [运行环境](#运行环境)
  - [通过Composer引入依赖包](#通过composer引入依赖包)
- [开始使用](#开始使用)
  - [简介](#简介)
  - [编写前置器](#编写前置器)
  - [使用建议](#使用建议)
- [License](#license)

# 安装

## 运行环境

| 运行环境要求           |
| ---------------------- |
| PHP ^8.1.0             |
| Laravel Framework ^9.0 |

## 通过Composer引入依赖包

通过终端进入项目根目录，执行以下命令引入依赖包：

``` shell
> composer require yesccx/laravel-preparation:1.x
```

# 开始使用

## 简介

通常情况下，我们的接口实现是对一系列资源进行 `增删改查` 操作，例如 `文章` 就是资源，我们可能会编写 `文章` 的 `创建` 、`更新` 、`状态变更`、`删除` 等接口。这类接口的实现都有一共同逻辑，就是在做具体操作之前都需要检索出资源，如查询 `文章表` 判断 `文章` 是否存在。前置器的职责就是处理这一部分逻辑，它可以在控制器处理业务逻辑之前做一些初始化的判断或处理。

与 `表单验证` 的区别是，`表单验证` 通常只对数据进行 `类型验证`、`有效性验证` 等，一般不涉及数据库查询操作，并且很难将验证过程中的一些中间数据暂存起来供后续业务逻辑处理时使用，而`前置器`很好的解决了这一问题。

## 编写前置器

创建一个前置器类并继承 `Yesccx\Preparation\BasePre` 基类，同时在 `handle` 方法中实现需要前置处理的逻辑，其中 `handle` 方法支持依赖注入，最终我们可以将处理过程中产生的中间数据或结果数据暂存到成员变量中，供后续业务使用。前置器还可以通过基类中的 `fail` 方法中止前置验证，`fail` 方法默认会抛出一个 `PreparationException` 异常。

``` php
<?php

declare(strict_types = 1);

namespace App\Http\Preparations;

use Yesccx\Preparation\BasePre;
use Yesccx\Preparation\Exceptions\PreparationException;
use App\Models\User;

class UserPre extends BasePre
{
    /**
     * @var User 用户信息
     */
    public User $info;

    /**
     * 处理
     *
     * @throws PreparationException
     */
    public function handle(Request $request, User $user)
    {
        if (empty($id = $request->get('id', 0))) {
            return $this->fail('缺少id参数');
        }

        $info = $user->where('id', $id)->first();
        if (empty($info)) {
            return $this->fail('用户不存在');
        }

        $thi->info = $info;
    }
}
```

下述代码中，将定义好的前置器声明到控制器方法上，`Laravel` 在执行 `update` 方法之前会先进行前置器处理，然后再经过验证器验证，同时我们可以在方法中直接拿到 `UserPre` 前置器中处理过的数据。

``` php
class UserController
{
    public function update(UserPre $Pre, UserRequest $request)
    {
        $user = $pre->info;

        // Do something...
    }
}
```

## 使用建议

- 建议将 `前置器` 文件名统一以 `Pre` 作为后缀名结尾；
- 建议将 `前置器` 统一存放在 `app/Http/Preparations` 目录下。

# License

MIT
