<?php

namespace Seqdb\Model;
use Think\Model;

class LibraryModel extends Model{
	protected $tableName = 'library';

	public function library_list() {
		$library_list = $this->order('id desc')->select();

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
		return $this->where('id IN (' . trim($lib_ids) . ')')->select();
	}

	public function add_library($library_data) {
		return $this->add($library_data);
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
		// 删除测序数据表中的相关数据

		// 删除数据表中的相关数据

        $this->where(array(
        	'id' => intval($lib_id)
        ))->delete();
	}
}