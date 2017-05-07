<?php

namespace Seqdb\Model;
use Think\Model;

class SeqReportModel extends Model{
	public function seq_report_info($seq_report_id) {
		return $this->where(array(
			'id' => intval($seq_report_id)
		))->find();
	}

	public function add_report($report_data) {
		$this->add($report_data);

		$Seq = D('Seq');
		$Seq->update_report_count($report_data['seq_id']);
	}

	public function seq_reports($seq_id) {
		return $this->where(array(
			'seq_id' => intval($seq_id)
		))->select();
	}

	public function report_count($seq_id) {
		return $this->where(array(
			'seq_id' => intval($seq_id)
		))->count();
	}

	public function delete_report($seq_report_id) {
		$seq_report_info = $this->seq_report_info($seq_report_id);

		$this->where(array(
			'id' => intval($seq_report_id)
		))->delete();

		$Seq = D('Seq');
		$Seq->update_report_count($seq_report_info['seq_id']);
	}

	public function delete_seq_reports_by_seq_id($seq_id) {
		$seq_reports = $this->seq_reports($seq_id);

		foreach ($seq_reports as $seq_report) {
			$this->delete_report($seq_report['id']);
		}
	}
}