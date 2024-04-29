<?php

declare (strict_types=1);

namespace plugin\help\model;

use think\model\relation\HasMany;

/**
 * 工单数据模型
 * @class HelpQuestion
 * @package plugin\help\model
 */
class HelpQuestion extends AbsUser
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
        return $this->hasMany(HelpQuestionX::class, 'ccid', 'id');
    }
}