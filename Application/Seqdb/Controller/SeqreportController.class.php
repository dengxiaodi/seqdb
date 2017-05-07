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
		$upload->rootPath = C('SEQ_REPORT_UPLOAD.rootPath');
		$info =  $upload->upload();
		if(!$info) {
			$this->error($upload->getErrorMsg());
		}

		foreach($info as $file){
			$report_data['filename'] = $file['name'];
			$report_data['savepath'] = $file['savepath'];
	        $report_data['savename'] = $file['savename'];
	        
			$SeqReport->add_report($report_data);
	    }

		action_log('upload seq report', 'seq report', UID, UID);
		$this->redirect('seq/update?sid=' . $_POST['seq_id']);
        // $this->redirect(urlsafe_b64decode($_POST['return']));
	}

	public function download(){
	    $uploadpath = C('SEQ_REPORT_UPLOAD.rootPath');//设置文件上传路径
	    $id = I('id');//GET方式传到此方法中的参数id,即文件在数据库里的保存id.根据之查找文件信息。
	    if($id==''){//如果id为空
	        $this->error('下载失败！');
	    }
	    
	    $SeqReport = D('SeqReport');
	    $seq_report = $SeqReport->seq_report_info($id);

	    if(!$seq_report) {
	        $this->error('下载失败！', '', 1);
	    } else {
	        $filename=$uploadpath . $seq_report['savepath'] . $seq_report['savename'];//完整文件名（路径加名字）
	        $http = new \Org\Net\Http();
	        $http->download($filename, $seq_report['filename']);
	    }
	}

	function del() {
		$SeqReport = D('SeqReport');
		$SeqReport->delete_report(I('sid'));

		action_log('delete seq report', 'seq report', UID, UID);
		$this->redirect('seq/update?sid=' . $_GET['seq_id']);
        // $this->redirect(urlsafe_b64decode($_GET['return']));
	}
}