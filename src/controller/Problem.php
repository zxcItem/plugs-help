<?php

declare (strict_types=1);

namespace plugin\help\controller;

use plugin\help\model\PluginHelpProblem;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 常见问题管理
 * class Problem
 * @package plugin\help\controller
 */
class Problem extends Controller
{
    /**
     * 常见问题管理
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
        PluginHelpProblem::mQuery($this->get)->layTable(function () {
            $this->title = '常见问题管理';
        }, function (QueryHelper $query) {
            $query->like('name,content')->dateBetween('create_time');
            $query->where(['status' => intval($this->type === 'index'), 'deleted' => 0]);
        });
    }

    /**
     * 添加常见问题
     * @auth true
     */
    public function add()
    {
        $this->title = '添加常见问题';
        PluginHelpProblem::mForm('form');
    }

    /**
     * 编辑常见问题
     * @auth true
     */
    public function edit()
    {
        $this->title = '编辑常见问题';
        PluginHelpProblem::mForm('form');
    }

    /**
     * 表单结果处理
     * @param bool $state
     * @return void
     */
    protected function _form_result(bool $state)
    {
        if ($state) $this->success('修改成功!', 'javascript:history.back();');
    }

    /**
     * 修改问题状态
     * @auth true
     */
    public function state()
    {
        PluginHelpProblem::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除常见问题
     * @auth true
     */
    public function remove()
    {
        PluginHelpProblem::mDelete();
    }

    /**
     * 选择常见问题
     * @login true
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function select()
    {
        $this->get['status'] = 1;
        $this->index();
    }
}