<?php

/**
 * Model层共用基类
 *
 * Class ModelBase
 */
class ModelBase extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     * 更新前
     */
    public function beforeUpdate()
    {
        //强制更新updated_at，防止find后save保存原值的问题
        $this->updated_at = date("Y-m-d H:i:s");
    }

    public function initialize()
    {
        $this->setConnectionService('db');
        $this->setWriteConnectionService('db');
        $this->setReadConnectionService('rdb');
    }
}