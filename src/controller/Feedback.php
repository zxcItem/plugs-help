<?php


declare (strict_types=1);

namespace plugin\help\controller;

use plugin\account\model\PluginAccountUser;
use plugin\help\model\PluginHelpFeedback;
use plugin\help\model\PluginHelpProblem;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\admin\service\AdminService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\exception\HttpResponseException;

/**
 * 意见反馈管理
 * class Feedback
 * @package plugin\help\controller
 */
class Feedback extends Controller
{
    /**
     * 意见反馈管理
     * @auth true
     * @menu true
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index()
    {
        $this->type = $this->get['type'] ?? 'index';
        PluginHelpFeedback::mQuery()->layTable(function () {
            $this->title = '意见反馈管理';
        }, function (QueryHelper $query) {
            $query->with(['bindUser']);
            $query->like('name,phone,content')->dateBetween('create_time');
            $query->where(['status' => intval($this->type === 'index'), 'deleted' => 0]);
            // 提交用户搜索
            $db = PluginAccountUser::mQuery()->like('username')->field('id')->db();
            if ($db->getOptions('where')) $query->whereRaw("unid in {$db->buildSql()}");
        });
    }

    /**
     * 编辑意见反馈
     * @auth true
     */
    public function edit()
    {
        $this->title = '编辑意见反馈';
        PluginHelpFeedback::mForm('form');
    }

    /**
     * 表单数据处理
     * @param array $data
     * @return void
     */
    protected function _form_filter(array &$data)
    {
        if ($this->request->isPost()) {
            if (!empty($data['reply']) && empty($data['reply_time'])) {
                $data['reply_st'] = 1;
                $data['reply_time'] = date('Y-m-d H:i:s');
                $data['reply_by'] = AdminService::instance()->getUserId();
            }
        }
    }

    /**
     * 表单结果处理
     * @param boolean $state
     */
    protected function _form_result(bool $state)
    {
        if ($state) {
            $this->success('内容保存成功！', 'javascript:history.back()');
        }
    }

    /**
     * 修改工单状态
     * @auth true
     */
    public function state()
    {
        PluginHelpFeedback::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 同步到常见问题
     * @auth true
     * @return void
     */
    public function sync()
    {
        $input = $this->_vali([
            'id.require'   => '反馈不能为空！',
            'sync.in:0,1'  => '状态值范围异常！',
            'sync.require' => '状态值不能为空！',
        ]);
        if (($feedback = PluginHelpFeedback::mk()->findOrEmpty($input['id']))->isExists()) try {
            $problem = PluginHelpProblem::mk()->where(['fid' => $input['id']])->findOrEmpty();
            $this->app->db->transaction(function () use ($feedback, $problem, $input) {
                $feedback->save($input);
                empty($input['sync']) ? $problem->delete() : $problem->save([
                    'fid'     => $feedback->getAttr('id'),
                    'name'    => $feedback->getAttr('content'),
                    'content' => $feedback->getAttr('reply')
                ]);
            });
            $this->success(empty($input['sync']) ? '取消成功' : '设置成功');
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        } else {
            $this->error('无效反馈记录！');
        }
    }

    /**
     * 删除工单数据
     * @auth true
     */
    public function remove()
    {
        PluginHelpFeedback::mDelete();
    }
}