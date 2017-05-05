<?php

namespace Seqdb\Model;
use Think\Model;

class SampleModel extends Model{
	protected $tableName = 'sample';

	public function sample_list() {
		return $this->order('id desc')->select();
	}

	public function sample_info($sample_id) {
		return $this->where(array(
			'id' => intval($sample_id)
		))->find();
	}

	public function add_sample($sample_data) {
		return $this->add($sample_data);
	}

	public function update_sample($sample_id, $sample_data) {
		return $this->where(array(
			'id' => intval($sample_id)
		))->save($sample_data);
	}

	public function update_library_count($sample_id) {
		$Library = D('Library');
		$lib_count = $Library->library_count($sample_id);

		$this->where(array(
			'id' => intval($sample_id)
		))->save(array(
			'lib_count' => $lib_count
		));
	}

	public function delete_sample($sample_id) {
        $this->where(array(
        	'id' => intval($sample_id)
        ))->delete();
	}
}