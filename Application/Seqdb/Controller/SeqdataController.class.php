<?php

namespace Seqdb\Controller;
use User\Api\UserApi as UserApi;

class SeqdataController extends AdminController {
    public function index() {
        $SeqData = D('SeqData');
        $seq_data_list = $SeqData->list_seq_data($_GET['rid']);

        $this->assign('seq_data_list', $seq_data_list);
        $this->display('list');
    }

    public function process_seq_data() {
        return array(
            'data_no' => 'x' . time(),
            'seq_result_id' => intval($_POST['seq_result_id']),
            'data_type' => $_POST['data_type'],
            'raw_data_r1' => $_POST['raw_data_r1'],
            'raw_data_r2' => $_POST['raw_data_r2'],
            'clean_data_r1' => $_POST['clean_data_r1'],
            'clean_data_r2' => $_POST['clean_data_r2'],
            'comment' => $_POST['comment'],
            'create_time' => time(),
            'update_time' => time(),
            'operator' => UID
        );
    }

    public function add_seq_data() {
        $seq_data = $this->process_seq_data();

        $SeqData = D('SeqData');
        $SeqData->add_seq_data($seq_data);

        $SeqResult = D('SeqResult');
        $SeqResult->update_seq_data_count($seq_data['seq_result_id']);

        action_log('add_seq_data', 'seq_data', UID, UID);
        $this->redirect(urlsafe_b64decode($_POST['return']));
    }

    public function add(){
        $SeqResult = D('SeqResult');
        $seq_result_info = $SeqResult->seq_result_info(I('sid'));

        $Seq = D('Seq');
        $Library = D('Library');

        $seq_info = $Seq->seq_info($seq_result_info['seq_id']);
        $lib_info = $Library->library_info($seq_result_info['lib_id']);

        $seq_data_info['seq_result_info'] = $seq_result_info;
        $seq_data_info['seq_info'] = $seq_info;
        $seq_data_info['library_info'] = $lib_info;

        $this->assign('seq_data_info', $seq_data_info);
        $this->assign('edit_action', 'add_seq_data');
        $this->display('edit');
    }

    public function detail() {
        $SeqData = D('SeqData');
        $seq_data_info = $SeqData->detailed_seq_data_info(I('sid'));

        $this->assign('seq_data_info', $seq_data_info);
        $this->display('detail');
    }

    public function update_seq_data() {
        $SeqData = D('SeqData');
        $seq_data = $this->process_seq_data();

        unset($seq_data['data_no']);
        unset($seq_data['seq_result_id']);
        $SeqData->update_seq_data($_POST['id'], $seq_data);
        $this->redirect(urlsafe_b64decode($_POST['return']));
    }

    public function update() {
        $SeqData = D('SeqData');
        $seq_data_info = $SeqData->detailed_seq_data_info(I('sid'));

        $this->assign('seq_data_info', $seq_data_info);
        $this->assign('edit_action', 'update_seq_data');
        $this->display('edit');
    }

    public function del() {
        $SeqData = D('SeqData');
        $SeqData->delete_seq_data(I('sid'));

        action_log('delete_seq_data','seq', UID, UID);
        $this->redirect(urlsafe_b64decode($_GET['return']));
    }
}