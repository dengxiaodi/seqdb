<?php
namespace Seqdb\Controller;
use User\Api\UserApi as UserApi;

class SampleController extends AdminController {

    public function index(){
        $Sample = D('Sample');

        $this->assign('uid', UID);
        $this->assign('sample_list', $Sample->sample_list());
        $this->display();
    }

    function process_sample_data() {
        return array('type' => $_POST['type'],
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'source' => $_POST['source'],
            'obtain_time' => strtotime($_POST['obtain_time']),
            'remain' => $_POST['remain'],
            'nucleic_acid' => $_POST['nucleic_acid'],
            'method' => $_POST['method'],
            'concentration' => $_POST['concentration'],
            'amount' => $_POST['amount'],
            'mass' => $_POST['mass'],
            'used_volume' => $_POST['used_volume'],
            'used_mass' => $_POST['used_mass'],
            'rest' => $_POST['rest'],
            'extraction_date' => strtotime($_POST['extraction_date']),
            'operator' => UID,
            'comment' => $_POST['comment']
        );
    }

    public function add_sample() {
        $sample_data = $this->process_sample_data();
        $now = time();
        $sample_data['create_time'] = $now;
        $sample_data['update_time'] = $now;
        $Sample = D('Sample');
        $Sample->add_sample($sample_data);
        action_log('create_sample','sample', UID, UID);
        $this->redirect('sample/index');
    }

    public function add(){
        $this->assign('edit_action', 'add_sample');
        $this->display('edit');
    }

    public function del(){
        $Sample = D('Sample');
        $Sample->delete_sample(I('sid'));
        $this->redirect('sample/index');
        action_log('delete_sample','sample', UID, UID);
    }

    public function update_sample() {
        $sample_id = $_POST['sid'];

        $sample_data = $this->process_sample_data();
        $sample_data['update_time'] = time();
        $Sample = D('Sample');
        $Sample->update_sample($sample_id, $sample_data);

        action_log('update_sample','sample', UID, UID);
        $this->redirect('sample/index');
    }

    public function update(){
        $Sample = D('Sample');
        $sample_info = $Sample->sample_info(I('sid'));
        $this->assign('sample_info', $sample_info);
        $this->assign('edit_action', 'update_sample');
        $this->display('edit');
    }

    public function detail(){
        $Sample = D('Sample');
        $sample_info = $Sample->sample_info(I('sid'));
         
        $this->assign('uid', UID);
        $this->assign('sample_info', $sample_info);
        $this->display('detail');
    }
}
