<?php

namespace Seqdb\Model;
use Think\Model;

class SeqDataModel extends Model{
	protected $tableName = 'seq_data';

	public function seq_data_info($seq_data_id) {
		return $this->where(array(
			'id' => intval($seq_data_id)
		))->find();
	}

	public function detailed_seq_data_info($seq_data_id) {
		$SeqData = D('SeqData');
		$SeqResult = D('SeqResult');
		$Seq = D('Seq');
		$Library = D('Library');
		$Sample = D('Sample');

		$seq_data_info = $SeqData->seq_data_info($seq_data_id);
		$seq_result_info = $SeqResult->seq_result_info($seq_data_info['seq_result_id']);
		$seq_info = $Seq->seq_info($seq_result_info['seq_id']);
		$library_info = $Library->library_info($seq_result_info['lib_id']);
		$sample_info = $Sample->sample_info($library_info['sample_id']);
		
		$seq_data_info['sample_info'] = $sample_info;
		$seq_data_info['library_info'] = $library_info;
		$seq_data_info['seq_result_info'] = $seq_result_info;
		$seq_data_info['seq_info'] = $seq_info;

		return $seq_data_info;
	}

	public function list_seq_data($seq_result_id = null) {
		if($seq_result_id) {
			$where = array(
				'seq_result_id' => intval($seq_result_id)
			);

			$data_list = $this->where($where)->select();
		} else {
			$data_list = $this->select();
		}

		// 获取相关信息

		$SeqResult = D('SeqResult');
		$Seq = D('Seq');
		$Library = D('Library');
		$Sample = D('Sample');
		foreach ($data_list as $key => $value) {
			$data_list[$key] = $this->detailed_seq_data_info($value['id']);
		}

		return $data_list;
	}

	public function add_seq_data($seq_data) {
		$seq_data_id = $this->add($seq_data);
		if($seq_data_id) {
			$this->update_data_no($seq_data_id);
		}

		return $seq_data_id;
	}

	public function update_data_no($seq_data_id) {
		$seq_data_info = $this->detailed_seq_data_info($seq_data_id);

		$prefix = 'x';
		if($seq_data_info['data_type'] == 'single') {
			$prefix = 's';
		} else if($seq_data_info['data_type'] == 'pair') {
			$prefix = 'd';
		}

		$data_no = $prefix . $seq_data_info['sample_info']['id'] . $seq_data_info['library_info']['id'] . $seq_data_info['seq_result_info']['id'] . $seq_data_info['id'] . $seq_data_info['create_time'];
		$this->where(array(
			'id' => intval($seq_data_id)
		))->save(array(
			'data_no' => $data_no
		));
	}

	public function update_seq_data($seq_data_id, $seq_data) {
		$this->where(array(
            'id' => intval($seq_data_id)
        ))->save($seq_data);
	}

	public function seq_data_count($seq_result_id) {
		return $this->where(array(
			'seq_result_id' => intval($seq_result_id)
		))->count();
	}

	public function delete_seq_data($seq_data_id) {
        $seq_data_info = $this->seq_data_info($seq_data_id);

        $this->where(array(
        	'id' => intval($seq_data_id)
        ))->delete();

        $SeqResult = D('SeqResult');
        $SeqResult->update_seq_data_count($seq_data_info['seq_result_id']);
	}

	public function delete_seq_data_by_result_id($seq_result_id) {
		$seq_data_list = $this->list_seq_data($seq_result_id);

		foreach ($seq_data_list as $key => $value) {
			$this->delete_seq_data($value['id']);
		}
	}
}