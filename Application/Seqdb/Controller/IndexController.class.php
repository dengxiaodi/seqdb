<?php

namespace Seqdb\Controller;
use Think\Controller;

class IndexController extends Controller {

    /**
     * 后台首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $time = time();
        $this->assign('time',$time);

        $Sample = D('Sample');
        $Library = D('Library');
        $Seq = D('Seq');
        $SeqData = D('SeqData');

        $this->assign('sample_count', $Sample->count());
        $this->assign('library_count', $Library->count());
        $this->assign('seq_count', $Seq->count());
        $this->assign('seqdata_count', $SeqData->count());

        $this->display('index');
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            D('Member')->logout();
            $this->success('退出成功！', U('Index'));
        } else {
            $this->redirect('index');
        }
    }

}
