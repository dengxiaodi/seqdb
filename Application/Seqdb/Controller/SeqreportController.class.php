<?php

namespace Seqdb\Controller;
use User\Api\UserApi as UserApi;

class SeqreportController extends AdminController {
	function process_upload_report() {
		return array(
			'seq_id' => intval($_POST['seq_id']),
			'create_time' => time(),
			'operator' => UID
		);
	}

	function upload() {
		$SeqReport = D('SeqReport');
		$report_data = $this->process_upload_report();

		// 上传
		$upload = new \Think\Upload();
		// $upload->maxSize  = 3145728 ; // 设置附件上传大小
		// $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
		$info =  $upload->upload();
		if(!$info) {
			$this->error($upload->getErrorMsg());
		}

		print_r($info['savename']);
		exit;
		$report_data['file_name'] = $info[0]['savename'];
		$SeqReport->upload($report_data);

		action_log('upload seq report', 'seq report', UID, UID);
        $this->redirect(urlsafe_b64decode($_POST['return']));
	}
}