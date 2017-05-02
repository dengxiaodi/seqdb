<?php

namespace Seqdb\Controller;


class LibraryController extends AdminController {
    public function process_library_data() {
        return array(
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
            'operator' => UID,
            'create_time' => time(),
            'update_time' => 0,
            'comment' => $_POST['comment']
        );
    }

    public function index(){
        $Library = D('Library');
        $library_list = $Library->library_list();

        $this->assign('uid', UID);
        $this->assign('library_list', $library_list);
        $this->display();
    }

    public function add_library() {
        if(!$_POST['sample_id']) {
            $this->error("添加样本失败：样本编号不能为空");
        }
        
        // test sample info

        $Sample = D('Sample');
        $sample_info = $Sample->sample_info($_POST['sample_id']);
        if(!$sample_info) {
            $this->error("添加样本失败：无效的样本信息");
        }

        $library_data = $this->process_library_data();

        $Library = D('Library');
        $Library->add_library($library_data);
        action_log('create_library','library', UID, UID);
        $this->redirect('library/index');
    }

    public function add(){
        $sample_id = $_GET['sample_id'];
        $Sample = D('Sample');
        $sample_info = $Sample->sample_info($sample_id);
        if(!$sample_info) {
            $this->error("创建文库失败：无效的样本编号");
        }
        $this->assign('sample_info', $sample_info);

        $this->assign('edit_action', 'add_library');
        $this->display('edit');
    }

    public function update_library() {
        $Library = D('Library');
        $Sample = D('Sample');

        $sample_id = $_POST['sample_id'];
        $sample_info = $Sample->sample_info($sample_id);
        if(!$sample_info) {
            $this->error("更新文库失败：无效的样本信息");
        }

        $library_id = $_POST['id'];
        $library_data = $this->process_library_data();
        $Library->update_library($library_id, $library_data);

        action_log('update_library','library', UID, UID);
        $this->redirect('Library/index');
    }

    public function update(){

        $Library = D('Library');
        $library_info = $Library->library_info(I('sid'));

        $Sample = D('Sample');
        $sample_info = $Sample->sample_info($library_info['sample_id']);
        if(!$sample_info) {
            $this->error("更新文库失败：无效的样本信息");
        }

        $this->assign('sample_info', $sample_info);
        $this->assign('library_info', $library_info);
        $this->assign('edit_action', 'update_library');
        $this->display('edit');
    }

    public function detail(){
        $Library = D('Library');
        $library_info = $Library->library_info(I('sid'));

        $Sample = D('Sample');
        $library_info['sample_info'] = $Sample->sample_info($library_info['sample_id']);

        $this->assign('uid', UID);
        $this->assign('library_info', $library_info);
        $this->display('detail');
    }

    public function del(){
        $Library = D('Library');
        $Library->delete_library(I('sid'));

        action_log('delete_library', 'library', UID, UID);
        $this->redirect('Library/index');
    }
}
