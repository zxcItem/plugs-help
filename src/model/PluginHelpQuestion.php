<?php

declare (strict_types=1);

namespace plugin\help\model;

use plugin\account\model\Abs;
use think\model\relation\HasMany;

/**
 * 工单数据模型
 * @class PluginHelpQuestion
 * @package plugin\help\model
 */
class PluginHelpQuestion extends Abs
{

    // 工单状态
    public const tStatus = [
        '已取消', '新工单', '后台回复', '用户回复', '已完结'
    ];

    /**
     * 格式化图片格式
     * @param mixed $value
     * @return array
     */
    public function getImagesAttr($value): array
    {
        return str2arr($value ?? '', '|');
    }

    /**
     * 关联回复记录
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(PluginHelpQuestionX::class, 'ccid', 'id');
    }
}