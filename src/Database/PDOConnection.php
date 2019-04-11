<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/21
 * Time: 16:28
 */

namespace Cool\Database;

use Cool\Database\Base\AbstractPDOConnection as BasePDOConnection;
class PDOConnection extends BasePDOConnection
{
    // 后置处理事件
    public function onAfterInitialize()
    {
        parent::onAfterInitialize();
        // 关闭连接
        $this->disconnect();
    }

    // 析构事件
    public function onDestruct()
    {
        parent::onDestruct();
        // 关闭连接
        $this->disconnect();
    }
}