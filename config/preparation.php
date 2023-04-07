<?php

declare(strict_types = 1);

return [
    /**
     * 默认命名空间
     *
     * PS: 指定make:preparation时生成的文件位置及命名空间
     */
    'default_namespace' => env('PREPARATION_DEFAULT_NAMESPACE', 'App\Http\Preparations')
];
