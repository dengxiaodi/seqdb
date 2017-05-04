<?php

namespace Seqdb\Model;
use Think\Model;

class SeqDataModel extends Model{
	protected $tableName = 'seq_data';

	public function add_seq_data($seq_data) {
		$this->add($seq_data);
	}

	public function seq_data_count($seq_result_id) {
		return $this->where(array(
			'seq_result_id' => intval($seq_result_id)
		))->count();
	}
}