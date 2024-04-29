<?php

use think\migration\Migrator;
use think\migration\db\Column;

class InstallHelp extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->_create_help_feedback();
        $this->_create_help_problem();
        $this->_create_help_question();
        $this->_create_help_question_x();
    }

    /**
     * 创建数据对象
     * @class HelpFeedback
     * @table help_feedback
     * @return void
     */
    private function _create_help_feedback()
    {

        // 当前数据表
        $table = 'help_feedback';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 创建数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '数据-意见-反馈',
        ])
            ->addColumn('unid', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '反馈用户'])
            ->addColumn('phone', 'string', ['limit' => 20, 'default' => '', 'null' => true, 'comment' => '联系电话'])
            ->addColumn('images', 'text', ['default' => NULL, 'null' => true, 'comment' => '反馈图片'])
            ->addColumn('content', 'text', ['default' => NULL, 'null' => true, 'comment' => '反馈内容'])
            ->addColumn('reply', 'text', ['default' => NULL, 'null' => true, 'comment' => '回复内容'])
            ->addColumn('reply_st', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '回复状态'])
            ->addColumn('reply_by', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '回复用户'])
            ->addColumn('reply_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '回复时间'])
            ->addColumn('sync', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '同步至常见问题状态(1已同步,0未同步)'])
            ->addColumn('sort', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '排序权重'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '展示状态(1使用,0禁用)'])
            ->addColumn('deleted', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '删除状态(0未删,1已删)'])
            ->addColumn('create_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '更新时间'])
            ->addIndex('sort', ['name' => 'i7fa1b82bf_sort'])
            ->addIndex('unid', ['name' => 'i7fa1b82bf_unid'])
            ->addIndex('status', ['name' => 'i7fa1b82bf_status'])
            ->addIndex('deleted', ['name' => 'i7fa1b82bf_deleted'])
            ->addIndex('reply_st', ['name' => 'i7fa1b82bf_reply_st'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 创建数据对象
     * @class HelpProblem
     * @table help_problem
     * @return void
     */
    private function _create_help_problem()
    {

        // 当前数据表
        $table = 'help_problem';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 创建数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '数据-常见-问题',
        ])
            ->addColumn('fid', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => false, 'comment' => '来自反馈'])
            ->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'null' => true, 'comment' => '问题标题'])
            ->addColumn('content', 'text', ['default' => NULL, 'null' => true, 'comment' => '问题内容'])
            ->addColumn('num_er', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '未解决数'])
            ->addColumn('num_ok', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '已解决数'])
            ->addColumn('num_read', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '阅读次数'])
            ->addColumn('sort', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '排序权重'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '展示状态(1使用,0禁用)'])
            ->addColumn('deleted', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '删除状态(0未删,1已删)'])
            ->addColumn('create_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '更新时间'])
            ->addIndex('sort', ['name' => 'i2a4212540_sort'])
            ->addIndex('status', ['name' => 'i2a4212540_status'])
            ->addIndex('deleted', ['name' => 'i2a4212540_deleted'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 创建数据对象
     * @class HelpQuestion
     * @table help_question
     * @return void
     */
    private function _create_help_question()
    {

        // 当前数据表
        $table = 'help_question';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 创建数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '数据-问答-内容',
        ])
            ->addColumn('unid', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '提问用户'])
            ->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'null' => true, 'comment' => '工单标题'])
            ->addColumn('phone', 'string', ['limit' => 100, 'default' => '', 'null' => true, 'comment' => '联系电话'])
            ->addColumn('images', 'text', ['default' => NULL, 'null' => true, 'comment' => '工单图片'])
            ->addColumn('content', 'text', ['default' => NULL, 'null' => true, 'comment' => '工单描述'])
            ->addColumn('sort', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '排序权重'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '工单状态(0取消,1新工单,2后台回复,3前台回复,4已完结)'])
            ->addColumn('deleted', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '删除状态(0未删,1已删)'])
            ->addColumn('create_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '更新时间'])
            ->addIndex('sort', ['name' => 'i9383b2cca_sort'])
            ->addIndex('name', ['name' => 'i9383b2cca_name'])
            ->addIndex('unid', ['name' => 'i9383b2cca_unid'])
            ->addIndex('phone', ['name' => 'i9383b2cca_phone'])
            ->addIndex('status', ['name' => 'i9383b2cca_status'])
            ->addIndex('deleted', ['name' => 'i9383b2cca_deleted'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }

    /**
     * 创建数据对象
     * @class HelpQuestionX
     * @table help_question_x
     * @return void
     */
    private function _create_help_question_x()
    {

        // 当前数据表
        $table = 'help_question_x';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 创建数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '数据-问答-评论',
        ])
            ->addColumn('ccid', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '目标编号'])
            ->addColumn('unid', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '用户编号'])
            ->addColumn('content', 'text', ['default' => NULL, 'null' => true, 'comment' => '文本内容'])
            ->addColumn('images', 'text', ['default' => NULL, 'null' => true, 'comment' => '图片内容'])
            ->addColumn('reply_by', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '后台用户'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '记录状态(0无效,1待审核,2已审核)'])
            ->addColumn('deleted', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '删除状态(0未删,1已删)'])
            ->addColumn('create_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '更新时间'])
            ->addIndex('ccid', ['name' => 'i9180fa26f_ccid'])
            ->addIndex('unid', ['name' => 'i9180fa26f_unid'])
            ->addIndex('status', ['name' => 'i9180fa26f_status'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }
}
