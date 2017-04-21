<?php

namespace Seqdb\Controller;


class LibraryController extends AdminController {

    public function index(){
        $library = M('library');
        $sample = M('sample');
        $library_list = $library->order('id desc')->select();
        foreach ($library_list as $key => $value) {
            $library_list[$key]['sample_info'] = $sample->where('id=' . intval($value['sample_id']))->find();
        }

        $uid = is_login();
        $this->assign('uid',$uid);
        $this->assign('library',$library_list);
        $this->display();
    }

    public function processLibraryData() {
        return array(
            'id' => $_POST['id'],
            'sample_id' => $_POST['sample_id'],
            'title' => $_POST['title'],
            'kit' => $_POST['kit'],
            'cycle_num' => $_POST['cycle_num'],
            'template_mass' => $_POST['template_mass'],
            'lib_volume' => $_POST['lib_volume'],
            'conc_qubit' => $_POST['conc_qubit'],
            'conc_qpcr' => $_POST['conc_qpcr'],
            'peak' => $_POST['peak'],
            'create_date' => strtotime($_POST['create_date']),
            'operator' => is_login(),
            'create_time' => time(),
            'update_time' => 0,
            'comment' => $_POST['comment']
        );
    }

    public function add_library() {
        $uid = is_login();

        if(!$_POST['sample_id']) {
            $this->error("添加样本失败：样本编号不能为空");
        }
        
        // test sample info

        $sample_info = M('sample')->where('id=' . intval($_POST['sample_id']))->find();
        if(!$sample_info) {
            $this->error("添加样本失败：无效的样本信息");
        }

        $library_data = $this->processLibraryData();

        $library = M('library');
        $library->add($library_data);
        action_log('create_library','library',$uid,$uid);
        $this->redirect('library/index');
    }

    public function add(){
        $uid = is_login();
        $sample_id = $_GET['sample_id'];
        $sample_info = M('sample')->where('id=' . intval($sample_id))->find();
        if(!$sample_info) {
            $this->error("创建文库失败：无效的样本编号");
        }
        $this->assign('sample_info', $sample_info);

        $condition['operator'] = $uid;
        $this->assign('edit_action', 'add_library');
        $this->display(edit);
    }

    public function del(){
        $uid = is_login();
        $library = M('library');
        $condition['id'] = I('sid');
        $library->where($condition)->delete();
        $this->redirect('Library/index');
        action_log('delete_library','library',$uid,$uid);
    }

    public function update_library() {
        $library = M('library');

        $sample_id = $_POST['sample_id'];
        $sample_info = M('sample')->where('id=' . intval($sample_id))->find();
        if(!$sample_info) {
            $this->error("更新文库失败：无效的样本信息");
        }

        $library_data = $this->processLibraryData();
        $library->save($library_data);
        action_log('update_library','library',$uid,$uid);
        $this->redirect('Library/index');
    }

    public function update(){
        $uid = is_login();

        $library = M('library');

        $condition['id'] = I('sid');
        $library_info = $library->where()->find();

        $sample = M('sample');
        $sample_info = M('sample')->where('id=' . intval($library_info['sample_id']))->find();
        if(!$sample_info) {
            $this->error("更新文库失败：无效的样本信息");
        }
        $this->assign('sample_info', $sample_info);

        $this->assign('library_info', $library_info);
        $this->assign('edit_action', 'update_library');
        $this->display(edit);
    }

    public function detail(){
        $library = M('library');
        $sample = M('sample');

        $condition['id'] = I('sid');
        $library_info = $library->where($condition)->find();
        $library_info['sample_info'] = $sample->where('id=' . intval($library_info['sample_id']))->find(); 
        $uid = is_login();
        $this->assign('uid',$uid);
        $this->assign('library',$library_info);
        $this->display(detail);
    }

}
