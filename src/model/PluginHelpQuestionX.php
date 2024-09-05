<?php

declare (strict_types=1);

namespace plugin\help\model;

use plugin\account\model\Abs;
use think\admin\model\SystemUser;
use think\model\relation\HasOne;

/**
 * 工单交互数据模型
 * @class PluginHelpQuestionX
 * @package plugin\help\model
 */
class PluginHelpQuestionX extends Abs
{
    /**
     * 绑定回复用户数据
     * @return HasOne
     */
    public function bindAdmin(): HasOne
    {
        return $this->hasOne(SystemUser::class, 'id', 'reply_by')->bind([
            'reply_headimg'  => 'headimg',
            'reply_username' => 'username',
            'reply_nickname' => 'nickname',
        ]);
    }

    /**
     * 格式化图片格式
     * @param mixed $value
     * @return array
     */
    public function getImagesAttr($value): array
    {
        return str2arr($value ?? '', '|');
    }
}