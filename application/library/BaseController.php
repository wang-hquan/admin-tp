<?php


namespace app\library;


use think\Controller;
use think\Request;
use think\Session;

class BaseController extends Controller
{
    protected $title = "未定义";
    protected $ret = ['code' => -1, 'msg' => '', 'data' => null];

    public function __construct( Request $request = null )
    {
        parent::__construct($request);
        $this->authHandle();
        $this->assign("title", $this->title);
    }

    protected function authHandle()
    {
        $path = '/' . $this->request->path() . '/';
        $noLogin = config('auth_white')['noLogin'];
        if (empty(session('_logined.user'))) {
            $direct = true;
            foreach ($noLogin as $s) {
                if (substr($path, 0, strlen($s)) == $s) {
                    $direct = false;
                }
            }

            if ($direct) {
                $this->ret['code'] = -999;
                $this->ret['msg'] = '登录状态失效，请重新登录';
                $this->outPutJson();
            }
        }
    }

    public function receiveParam($mustFields, $extFields, $doTrim = true)
    {
        $doTrim = $doTrim? 'trim':null;

        if (!is_array($mustFields)) {
            $mustFields = [$mustFields];
        }

        if (!is_array($extFields)) {
            $extFields = [$extFields];
        }

        foreach ($mustFields as $field) {
            $mv = $this->request->param($field, '', $doTrim);
            if ($mv === '') {
                throw new \Exception($field . ':该参数不能为空', 500);
            }
            $param[$field] = $mv;
        }

        foreach ($extFields as $field) {
            $ev = $this->request->param($field, '', $doTrim);
            $param[$field] = $ev;
        }

        return $param;
    }

    protected function outPutJson( $un = false, $exit = true )
    {
        echo $un? json_encode($this->ret, JSON_UNESCAPED_UNICODE):json_encode($this->ret);
        if ( $exit ) {
            exit();
        }
    }
}
