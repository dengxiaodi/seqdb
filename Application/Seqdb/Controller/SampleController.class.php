<?php
namespace Seqdb\Controller;
use User\Api\UserApi as UserApi;

class SampleController extends AdminController {

    public function index(){
        $sample = M('sample');
        $data = $sample->order('id desc')->select();
        $uid = is_login();
        $this->assign('uid',$uid);
        $this->assign('sample',$data);
        $this->display();
    }

    public function add(){
        $uid = is_login();
        if($_POST){
                $id = $_POST['sampleid'];
                $type = $_POST['type'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $source = $_POST['source'];
                $obtain_time = $_POST['obtain_time'];
                $remain = $_POST['remain'];
                $nucleic_acid = $_POST['nucleic_acid'];
                $method = $_POST['method'];
                $concentration = $_POST['concentration'];
                $amount = $_POST['amount'];
                $mass = $_POST['mass'];
                $used_volume = $_POST['used_volume'];
                $used_mass = $_POST['used_mass'];
                $rest = $_POST['rest'];
                $extraction_data = $_POST['extraction_data'];
                $comment = $_POST['comment'];

                foreach ($id as $key => $sampleid) {
                    $data[]=array('sampleid'=> time_format(time(),"Ymd").sprintf("%03d", is_login()).sprintf("%04d",$sampleid),
                                'type' => $type[$key],
                                'name' => $name[$key],
                                'description' => $name[$key],
                                'source' => $source[$key],
                                'obtain_time' => strtotime($obtain_time[$key]),
                                'remain' => $remain[$key],
                                'nucleic_acid' => $nucleic_acid[$key],
                                'method' => $method[$key],
                                'concentration' => $concentration[$key],
                                'amount' => $amount[$key],
                                'mass' => $mass[$key],
                                'used_volume' => $used_volume[$key],
                                'used_mass' => $used_mass[$key],
                                'rest' => $rest[$key],
                                'extraction_data' => strtotime($extraction_data[$key]),
                                'create_time' => time(),
                                'operator' => is_login(),
                                'comment' => $comment[$key]
                                 );
                }

                $sample = M('sample');
                $sample->addAll($data);
                action_log('create_sample','sample',$uid,$uid);
                $this->success('成功添加样品',U('sample/index'));
        }else{
            $this->display(add);
        }

        
    }

    public function del(){
        $uid = is_login();
        $sample = M('sample');
        $condition['id'] = I('sid');
        $sample->where($condition)->delete();
        $this->success('删除成功');
        action_log('delete_sample','sample',$uid,$uid);
    }

    public function update(){
        $uid = is_login();
        $condition['id'] = I('sid');
        if($_POST){
            $data['id'] = $_POST['id'];
            $data['sampleid'] = $_POST['sampleid'];
            $data['type'] = $_POST['type'];
            $data['name'] = $_POST['name'];
            $data['description'] = $_POST['description'];
            $data['source'] = $_POST['source'];
            $data['obtain_time'] = strtotime($_POST['obtain_time']);
            $data['remain'] = $_POST['remain'];
            $data['nucleic_acid'] = $_POST['nucleic_acid'];
            $data['method'] = $_POST['method'];
            $data['concentration'] = $_POST['concentration'];
            $data['amount'] = $_POST['amount'];
            $data['mass'] = $_POST['mass'];
            $data['used_volume'] = $_POST['used_volume'];
            $data['used_mass'] = $_POST['used_mass'];
            $data['rest'] = $_POST['rest'];
            $data['extraction_data'] = strtotime($_POST['extraction_data']);
            $data['update_time'] = time();
            $data['comment'] = $_POST['comment'];

            $sample = M('sample');
            $sample->save($data);
            action_log('update_sample','sample',$uid,$uid);
            $this->success('修改成功',U('sample/index'));
        }else{
            $sample = M('sample');
            $result = $sample->where($condition)->select();
            $this->assign('sample',$result[0]);
            $this->display(update);
        }
        

    }

    public function detail(){
        $sample = M('sample');
        $condition['id'] = I('sid');
        $result = $sample->where($condition)->select();
        $uid = is_login();
        $this->assign('uid',$uid);
        $this->assign('sample',$result[0]);
        $this->display(detail);
    }

}
