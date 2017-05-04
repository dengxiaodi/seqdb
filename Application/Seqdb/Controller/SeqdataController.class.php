<?php

namespace Seqdb\Controller;
use User\Api\UserApi as UserApi;

class SeqdataController extends AdminController {
    public function process_seq_data() {
        return array(
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

        $this->assign('seq_result_info', $seq_result_info);
        $this->assign('seq_info', $seq_info);
        $this->assign('lib_info', $lib_info);
        $this->assign('edit_action', 'add_seq_data');
        $this->display('edit');
    }
}