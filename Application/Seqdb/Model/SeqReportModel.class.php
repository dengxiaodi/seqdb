<?php

namespace Seqdb\Model;
use Think\Model;

class SeqReportModel extends Model{
	public function upload($report_data) {
		$this->add($report_data);

		$Seq = D('Seq');
		$Seq->update_report_count();
	}

	public function report_count($seq_id) {
		return $this->where(array(
			'seq_id' => intval($seq_id)
		))->count();
	}
}