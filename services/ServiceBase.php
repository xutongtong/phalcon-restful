<?php

/**
 * 孩宝小镇基础服务类
 * ============================================================================
 * * 版权所有 2008-2017 深圳市凯迪克文化传播有限公司，并保留所有权利。
 * 网站地址: www.baobaobooks.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: ServiceBase.php $
 * $Id: BaseService.php 2016-04-19 xutt $
 */
class ServiceBase
{
    public function __construct()
    {
        //暂时取掉&$app
        //        $di = \Phalcon\DI::getDefault();
        //
        //        $this->cache = $di->get('cache');
        //
        //        $this->redis = $di->get('redis');
        //
        //        $this->session = $di->get('session');
    }

    //初始化DI组件
    public function __get($name)
    {
        $di = \Phalcon\Di::getDefault();
        return $this->{$name} = $di->has($name) ? $di->get($name) : null;
    }

    protected function _update($tableName = '', $set = [], $where = [])
    {
        if (empty($tableName) || empty($set)) {
            return 0;
        }

        $sql = "UPDATE $tableName SET ";
        $setStr = '';
        $whereStr = ' WHERE 1 ';

        foreach ($set as $key => $value) {
            $setStr .= "`$key` = '$value',";
        }

        $setStr = rtrim($setStr, ",");

        foreach ($where as $key => $value) {
            $whereStr .= " AND `$key` = '$value' ";
        }

        $sql = $sql . $setStr . $whereStr;
        $this->db->query($sql);
        return $this->db->affectedRows();
    }

    /**
     * 根据key获取redis中string
     * @param $key
     * @return mixed
     */
    protected function fetchString($key)
    {
        $string = $this->redis->get($key);

        return $string;
    }

    /**
     * 根据key获取redis中的list
     * @param $key
     * @return mixed
     */
    protected function fetchList($key)
    {
        $list = $this->redis->lRange($key, 0, -1);

        return $list;
    }

}
