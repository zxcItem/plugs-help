<?php


declare (strict_types=1);

namespace plugin\help\model;

use think\admin\model\SystemUser;
use think\model\relation\HasOne;

/**
 * 意见反馈数据模型
 * @class HelpFeedback
 * @package plugin\help\model
 */
class HelpFeedback extends AbsUser
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

    /**
     * 获取回复时间
     * @param mixed $value
     * @return string
     */
    public function getReplyTimeAttr($value): string
    {
        return parent::getCreateTimeAttr($value);
    }

    /**
     * 设置回复时间
     * @param mixed $value
     * @return string
     */
    public function setReplyTimeAttr($value): string
    {
        return parent::setCreateTimeAttr($value);
    }
}