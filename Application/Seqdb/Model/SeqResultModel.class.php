<?php

namespace Seqdb\Model;
use Think\Model;

class SeqResultModel extends Model{
	protected $tableName = 'seq_result';

	public function seq_results($seq_id) {
		$results = $this->where(array(
			'seq_id' => intval($seq_id)
		))->select();

		$Library = D('Library');

		foreach ($results as $key => $value) {
            $results[$key]['library_info'] = $Library->library_info($value['lib_id']);
        }

        return $results;
	}

	public function seq_lib_ids($seq_id) {
		return $this->where(array(
			'seq_id' => intval($seq_id)
		))->getField('lib_id', true);
	}

	public function seq_result_ids($seq_id) {
		return $this->where(array(
			'seq_id' => intval($seq_id)
		))->getField('id', true);
	}

	public function add_seq_result($seq_result_data) {
		return $this->add($seq_result_data);
	}

	public function get_lib_count($seq_id) {
		return $this->where('seq_id=' . intval($seq_id))->count();
	}

	public function delete_seq_result($seq_result_id) {
		// 删除数据记录

		// 删除测序结果记录

		$this->where(array(
        	'id' => intval($seq_result_id)
        ))->delete();
	}

	public function update_seq_result($seq_result_id, $seq_result_data) {
		return $this->where(array(
			'id' => intval($seq_result_id)
		))->save($seq_result_data);
	}
}