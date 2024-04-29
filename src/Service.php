<?php

declare (strict_types=1);

namespace plugin\help;

use think\admin\Plugin;

/**
 * 组件注册服务
 * @class Service
 * @package plugin\help
 */
class Service extends Plugin
{
    /**
     * 定义插件名称
     * @var string
     */
    protected $appName = '工单管理';

    /**
     * 定义安装包名
     * @var string
     */
    protected $package = 'xiaochao/plugs-help';

    /**
     * 菜单配置
     * @return array[]
     */
    public static function menu(): array
    {
        // 设置插件菜单
        $code = self::getAppCode();
        return [
            [
                'name' => '工单管理',
                'subs' => [
                    ['name' => '常见问题管理', 'icon' => 'layui-icon layui-icon-star', 'node' => "{$code}/help.problem/index"],
                    ['name' => '意见反馈管理', 'icon' => 'layui-icon layui-icon-template', 'node' => "{$code}/help.feedback/index"],
                    ['name' => '工单提问管理', 'icon' => 'layui-icon layui-icon-transfer', 'node' => "{$code}/help.question/index"],
                ],
            ],
        ];
    }
}