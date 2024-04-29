<?php


declare (strict_types=1);

namespace plugin\help\controller\api\help;

use plugin\help\model\HelpProblem;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

/**
 * 常见问题数据接口
 * @class Problem
 * @package plugin\help\controller\api\help
 */
class Problem extends Controller
{

    /**
     * 获取反馈意见
     * @return void
     */
    public function get()
    {
        HelpProblem::mQuery(null, function (QueryHelper $query) {
            $query->like('name')->equal('id');
            $this->success('获取反馈意见', $query->order('sort desc,id desc')->page(true, false, false, 10));
        });
    }
}