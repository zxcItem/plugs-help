<?php


declare (strict_types=1);

namespace plugin\help\controller\api\help;

use plugin\account\controller\api\Auth;
use plugin\help\model\PluginHelpFeedback;
use think\admin\Exception;
use think\admin\helper\QueryHelper;
use think\admin\Storage;

/**
 * 意见反馈管理
 * @class Feedback
 * @package plugin\help\controller\api\help
 */
class Feedback extends Auth
{
    /**
     * 获取反馈意见
     * @return void
     */
    public function get()
    {
        PluginHelpFeedback::mQuery(null, function (QueryHelper $query) {
            $query->where(['unid' => $this->unid,'deleted' => 0])->like('content#keys')->equal('id');
            $this->success('获取反馈意见', $query->order('sort desc,id desc')->page(true, false, false, 10));
        });
    }

    /**
     * 提交反馈意见
     * @return void
     * @throws Exception
     */
    public function set()
    {
        $data = $this->_vali([
            'unid.value'      => $this->unid,
            'content.require' => '内容不能为空!',
            'phone.default'   => '',
            'images.default'  => '',
        ]);
        if (!empty($data['images'])) {
            $images = explode('|', $data['images']);
            foreach ($images as &$image) {
                $image = Storage::saveImage($image, 'feedback')['url'];
            }
            $data['images'] = implode('|', $images);
        }
        if (($model = PluginHelpFeedback::mk())->save($data)) {
            $this->success('提交成功！', $model->toArray());
        } else {
            $this->error('提交失败！');
        }
    }
}