<?php

namespace Seqdb\Controller;
use User\Api\UserApi as UserApi;

class SeqController extends AdminController {

    public function index(){
        $this->assign('uid', UID);
        $this->assign('seq', D('Seq')->seq_list());
        $this->display();
    }

    public function add_seq() {
        $seq_data = $this->process_seq_data();

        if(!$seq_data['lib_ids']) {
            $this->error("创建送测失败：请选择至少一个文库");
        }

        $Seq = D('Seq');
        $Seq->add_seq($seq_data);
        
        action_log('create_seq','seq', UID, UID);
        $this->redirect('seq/index');
    }

    public function process_seq_data() {
        return array(
            'lib_ids' => $_POST['lib_ids'],
            'title' => $_POST['title'],
            'company' => $_POST['company'],
            'send_date' => strtotime($_POST['send_date']),
            'operator' => UID,
            'create_time' => time(),
            'update_time' => time(),
            'comment' => $_POST['comment']
        );
    }

    public function add(){
        $lib_ids = trim($_GET['lib_ids']);
        if(!$lib_ids) {
            $this->error("新建送测失败，无效的文库信息");
        }
        $lib_ids = explode(',', $lib_ids);
        $Library = D('Library');
        $libs = $Library->library_infos($lib_ids);

        $this->assign('libs', $libs);
        $this->assign('edit_action', 'add_seq');
        $this->display('edit');
    }

    public function add_lib() {
        $seq_id = trim($_GET['seq_id']);
        $lib_ids = trim($_GET['lib_ids']);

        if(!$seq_id) {
            $this->error("添加文库失败，无效的测序信息");
        }

        if(!$lib_ids) {
            $this->error("添加文库失败，无效的文库信息");
        }

        $lib_ids = explode(',', $lib_ids);

        $Seq = D('Seq');
        $Seq->add_seq_libs($seq_id, $lib_ids);

        $this->redirect('Seq/detail?sid=' . intval($seq_id));
    }

    public function del(){
        $Seq = D('Seq');
        $Seq->delete_seq(I('sid'));

        action_log('delete_seq','seq', UID, UID);
        $this->redirect('seq/index');
    }

    public function update_seq() {
        $Seq = D('Seq');
        $seq_data = $this->process_seq_data();
        $Seq->update_seq($_POST['id'], $seq_data);

        $this->redirect('seq/index');
    }

    public function update(){
        $seq_id = I('sid');

        $Seq = D('Seq');
        $seq_info = $Seq->seq_info($seq_id);

        $SeqResult = D('SeqResult');
        $seq_results = $SeqResult->seq_results($seq_id);

        $this->assign('seq_info', $seq_info);
        $this->assign('seq_results', $seq_results);
        $this->assign('edit_action', 'update_seq');
        $this->display('edit');
    }

    public function detail(){
        $seq_id = I('sid');

        $Seq = D('Seq');
        $seq_info = $Seq->seq_info($seq_id);
        
        $SeqResult = D('SeqResult');
        $seq_results = $SeqResult->seq_results($seq_id);

        $this->assign('seq_info', $seq_info);
        $this->assign('seq_results',$seq_results);
        $this->assign('uid', UID);
        $this->display('detail');
    }
}