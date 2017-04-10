<?php
namespace Seqdb\Controller;
use User\Api\UserApi as UserApi;

class SampleController extends AdminController {

    public function index(){
        $sample = M('sample');
        $data = $sample->order('id desc')->select();
        $uid = is_login();
        $this->assign('uid',$uid);
        $this->assign('sample',$data);
        $this->display();
    }

    function processSampleData() {
        return array('type' => $_POST['type'],
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'source' => $_POST['source'],
            'obtain_time' => strtotime($_POST['obtain_time']),
            'remain' => $_POST['remain'],
            'nucleic_acid' => $_POST['nucleic_acid'],
            'method' => $_POST['method'],
            'concentration' => $_POST['concentration'],
            'amount' => $_POST['amount'],
            'mass' => $_POST['mass'],
            'used_volume' => $_POST['used_volume'],
            'used_mass' => $_POST['used_mass'],
            'rest' => $_POST['rest'],
            'extraction_data' => strtotime($_POST['extraction_data']),
            'create_time' => time(),
            'operator' => is_login(),
            'comment' => $_POST['comment']
        );
    }

    public function add(){
        $uid = is_login();
        if($_POST){
                $data = $this->processSampleData();
                $sample = M('sample');
                $sample->data($data)->add();
                action_log('create_sample','sample',$uid,$uid);
                $this->redirect('sample/index');
        } else {
            $this->assign('edit_action', 'add');
            $this->display(edit);
        }
    }

    public function del(){
        $uid = is_login();
        $sample = M('sample');
        $condition['id'] = I('sid');
        $sample->where($condition)->delete();
        $this->redirect('sample/index');
        action_log('delete_sample','sample',$uid,$uid);
    }

    public function update(){
        $uid = is_login();
        $condition['id'] = I('sid');
        if($_POST){
            $data = $this->processSampleData();
            $sample = M('sample');
            $sample->where('id=' . intval($_POST['sid']))->save($data);
            action_log('update_sample','sample',$uid,$uid);
            $this->redirect('sample/index');
        }else{
            $sample = M('sample');
            $result = $sample->where($condition)->select();
            $this->assign('sample',$result[0]);
            $this->assign('edit_action', 'update/sid/' . I('sid'));
            $this->display(edit);
        }
    }

    public function detail(){
        $sample = M('sample');
        $condition['id'] = I('sid');
        $result = $sample->where($condition)->select();
        $uid = is_login();
        $this->assign('uid',$uid);
        $this->assign('sample',$result[0]);
        $this->display(detail);
    }

}
