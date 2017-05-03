<?php

namespace Seqdb\Model;
use Think\Model;

class SeqModel extends Model{
	protected $tableName = 'seq';

	public function seq_list() {
		return $this->order('id desc')->select();
	}

	public function seq_info($seq_id) {
		return $this->where(array(
			'id' => intval($seq_id)
		))->find();
	}

	public function add_seq_libs($seq_id, $lib_ids) {
		$Library = D('Library');
		$SeqResult = D('SeqResult');
		foreach ($lib_ids as $lib_id) {
			$seq_result_data = array(
				'seq_id' => intval($seq_id),
                'lib_id' => intval($lib_id),
                'create_time' => time()
			);

			$SeqResult->add_seq_result($seq_result_data);

			// 更新文库的送测状态

			$Library->update_seqed($lib_id);
		}

		// 更新文库数

		$this->update_lib_count($seq_id);
	}

	public function add_seq($seq_data) {
		$seq_id = $this->add($seq_data);
		$this->add_seq_libs($seq_id, $seq_data['lib_ids']);

		return $seq_id;
	}

	public function update_seq($seq_id, $seq_data) {
		return $this->where(array(
			'id' => intval($seq_id)
		))->save($seq_data);
	}

	public function update_lib_count($seq_id) {
		$SeqResult = D('SeqResult');

		$this->where(array(
			'id' => intval($seq_id)
		))->save(array(
            'lib_count' => $SeqResult->get_lib_count($seq_id)
        ));
	}

	public function delete_seq($seq_id) {
		$SeqResult = D('SeqResult');
		$seq_lib_ids = $SeqResult->seq_lib_ids($seq_id);
		$seq_result_ids = $SeqResult->seq_result_ids($seq_id);

		$Library = D('Library');

		// 删除测序文库记录

		foreach ($seq_result_ids as $seq_result_id) {
			$SeqResult->delete_seq_result($seq_result_id);
		}

		// 删除测序记录

        $this->where(array(
        	'id' => intval($seq_id)
        ))->delete();

        // 更新文库送测状态

		foreach ($seq_lib_ids as $lib_id) {
			$Library->update_seqed($lib_id);
		}
	}
}