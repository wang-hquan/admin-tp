<?php


namespace app\model\admin_data;


class AdminUserModel extends Base
{
    public $table = 'admin_user';

    public function login($uname, $pwd)
    {
        $rec = $this->where(['user'=>$uname,'state'=>1])->find();
        if (empty($rec)) {
            return false;
        }
        if ($pwd !==$rec['pwd']) {
            return false;
        }
        unset($rec['pwd'],$rec['salt']);
        return $rec;
    }
}
