<?php

declare (strict_types=1);

namespace plugin\help\controller;

use plugin\account\model\AccountUser;
use plugin\help\model\HelpQuestion;
use plugin\help\model\HelpQuestionX;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\admin\service\AdminService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 工单提问管理
 * class Question
 * @package plugin\help\controller
 */
class Question extends Controller
{
    /**
     * 工单提问管理
     * @auth true
     * @menu true
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index()
    {
        HelpQuestion::mQuery()->layTable(function () {
            $this->title = '工单提问管理';
            $this->types = HelpQuestion::tStatus;
        }, function (QueryHelper $helper) {
            $helper->with(['bindUser'])->where(['deleted' => 0]);
            $helper->like('name,content')->equal('status')->dateBetween('create_time');
            // 提交用户搜索
            $db = AccountUser::mQuery()->like('username')->field('id')->db();
            if ($db->getOptions('where')) $helper->whereRaw("unid in {$db->buildSql()}");
        });
    }

    /**
     * 编辑工单内容
     * @auth true
     */
    public function edit()
    {
        $this->title = '编辑工单内容';
        HelpQuestion::mQuery()->with(['bindUser', 'comments'])->mForm('form');
    }

    /**
     * 表单数据处理
     * @param array $data
     * @return void
     */
    protected function _form_filter(array &$data)
    {
        if ($this->request->isPost()) {
            if (empty($data['content'])) {
                $this->error('回复内容不能为空！');
            }
            $data['status'] = 2;
            HelpQuestionX::mk()->save([
                'ccid'     => $data['id'],
                'content'  => $data['content'],
                'reply_by' => AdminService::getUserId()
            ]);
            unset($data['content']);
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
        HelpQuestion::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除工单数据
     * @auth true
     */
    public function remove()
    {
        HelpQuestion::mDelete();
    }
}