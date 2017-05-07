<?php

namespace Seqdb\Model;
use Think\Model;

class LibraryModel extends Model{
	protected $tableName = 'library';

	public function library_list($sample_id) {
		if($sample_id) {
			$library_list = $this->where(array(
				'sample_id' => intval($sample_id)
			))->order('id desc')->select();
		} else {
			$library_list = $this->order('id desc')->select();
		}
		
		$Sample = D('Sample');
		foreach ($library_list as $key => $value) {
			$library_list[$key]['sample_info'] = $Sample->sample_info($value['sample_id']);
		}

		return $library_list;
	}

	public function library_info($lib_id) {
		return $this->where(array(
			'id' => intval($lib_id)
		))->find();
	}

	public function library_infos($lib_ids) {
		if(is_array($lib_ids) && count($lib_ids) > 0) {
			return $this->where('id IN (' . implode(',', $lib_ids) . ')')->select();
		}

		return false;
	}

	public function libs_by_sample_id($sample_id) {
		return $this->where(array(
			'sample_id' => intval($sample_id)
		))->select();
	}

	public function add_library($library_data) {
		$lib_id = $this->add($library_data);
		$Sample = D('Sample');
		$Sample->update_library_count($library_data['sample_id']);

		return $lib_id;
	}

	public function update_library($library_id, $library_data) {
		return $this->where(array(
			'id' => intval($library_id)
		))->save($library_data);
	}

	public function update_seqed($lib_id) {
		$SeqResult = D('SeqResult');
		
		$this->where(array(
			"id" => intval($lib_id)
		))->save(array(
			"seqed" => $SeqResult->where(array(
				'lib_id' => intval($lib_id)
			))->count()
		));
	}

	public function delete_library($lib_id) {

		$library_info = $this->library_info($lib_id);

		// 删除测序结果中的文库记录

		$SeqResult = D('SeqResult');
		$seq_results = $SeqResult->seq_results_by_lib_id($lib_id);
		foreach ($seq_results as $seq_result) {
			$SeqResult->delete_seq_result($seq_result['id']);
		}

        $this->where(array(
        	'id' => intval($lib_id)
        ))->delete();

        $Sample = D('Sample');
        $Sample->update_library_count($library_info['sample_id']);
	}

	public function library_count($sample_id) {
		return $this->where(array(
			'sample_id' => intval($sample_id)
		))->count();
	}
}