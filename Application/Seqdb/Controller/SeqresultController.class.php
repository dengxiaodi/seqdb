<?php

namespace Seqdb\Controller;
use User\Api\UserApi as UserApi;

class SeqresultController extends AdminController {
	public function del(){
        $SeqResult = D('SeqResult');
        $SeqResult->delete_seq_result(I('sid'));

        action_log('delete_seq_result','seq', UID, UID);
        $this->redirect(urlsafe_b64decode($_GET['return']));
    }

    public function process_seq_result_data() {
    	return array(
    		'platform' => $_POST['platform'],
            'seq_type' => $_POST['seq_type'],
            'lib_volume' => $_POST['lib_volume'],
            'conc_qubit' => $_POST['conc_qubit'],
            'conc_qpcr' => $_POST['conc_qpcr'],
            'peak' => $_POST['peak'],
            'operator' => UID,
            'update_time' => time(),
            'comment' => $_POST['comment']
    	);
    }

    public function detail() {
    	$SeqResult = D('SeqResult');
    	$seq_result_info = $SeqResult->seq_result_info(I('sid'));

    	$Seq = D('Seq');
    	$Library = D('Library');

    	$seq_info = $Seq->seq_info($seq_result_info['seq_id']);
    	$lib_info = $Library->library_info($seq_result_info['lib_id']);

    	$this->assign('seq_result_info', $seq_result_info);
    	$this->assign('seq_info', $seq_info);
    	$this->assign('lib_info', $lib_info);
    	$this->display('detail');
    }

    public function update_seq_result() {
    	$SeqResult = D('SeqResult');
    	
    	$seq_result_id = $_POST['id'];
    	$seq_result_data = $this->process_seq_result_data();

    	$SeqResult->update_seq_result($seq_result_id, $seq_result_data);
    	$this->redirect(urlsafe_b64decode($_POST['return']));
    }

    public function update() {
    	$SeqResult = D('SeqResult');
    	$seq_result_info = $SeqResult->seq_result_info(I('sid'));

    	$Seq = D('Seq');
    	$Library = D('Library');

    	$seq_info = $Seq->seq_info($seq_result_info['seq_id']);
    	$lib_info = $Library->library_info($seq_result_info['lib_id']);

    	$this->assign('seq_result_info', $seq_result_info);
    	$this->assign('seq_info', $seq_info);
    	$this->assign('lib_info', $lib_info);
    	$this->assign('edit_action', 'update_seq_result');
    	$this->display('edit');
    }
}