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

	public function seq_result_info($seq_result_id) {
		return  $this->where(array(
			'id' => intval($seq_result_id)
		))->find();
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

	public function seq_results_by_lib_id($lib_id) {
		return $this->where(array(
			'lib_id' => intval($lib_id)
		))->select();
	}

	public function delete_seq_result($seq_result_id) {
		$seq_result_info = $this->seq_result_info($seq_result_id);

		// 删除数据记录

		$SeqData = D('SeqData');
		$SeqData->delete_seq_data_by_result_id($seq_result_info['id']);
		
		// 删除测序结果记录

		$this->where(array(
        	'id' => intval($seq_result_id)
        ))->delete();

        // 更新文库记录及结果

        $Library = D('Library');
        $Seq = D('Seq');

        $Seq->update_lib_count($seq_result_info['seq_id']);
        $Library->update_seqed($seq_result_info['lib_id']);
	}

	public function update_seq_result($seq_result_id, $seq_result_data) {
		return $this->where(array(
			'id' => intval($seq_result_id)
		))->save($seq_result_data);
	}

	public function update_seq_data_count($seq_result_id) {
		$SeqData = D('SeqData');
		$seq_data_count = $SeqData->seq_data_count($seq_result_id);

		return $this->where(array(
			'id' => intval($seq_result_id)
		))->save(array(
			'data_count' => intval($seq_data_count)
		));
	}
}