<?php

namespace Seqdb\Controller;
use Think\Controller;

class IndexController extends Controller {

    /**
     * 后台首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function Index(){
        $time = time();
        $this->assign('time',$time);
        $this->display('Index');
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            D('Member')->logout();
            $this->success('退出成功！', U('Index'));
        } else {
            $this->redirect('Index');
        }
    }

}
