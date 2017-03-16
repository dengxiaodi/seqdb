<?php

namespace Seqdb\Controller;


class LibraryController extends AdminController {

    public function index(){
        $library = M('library');
        $data = $library->order('id desc')->select();
        $uid = is_login();
        $this->assign('uid',$uid);
        $this->assign('library',$data);
        $this->display();
    }

    public function add(){
        $uid = is_login();
        if($_POST){
         
            $lib_id = $_POST['lib_id'];
            $sample_id = $_POST['sample_id'];
            $description = $_POST['description'];
            $kit = $_POST['kit'];
            $cycle_num = $_POST['cycle_num'];
            $template_mass = $_POST['template_mass'];
            $lib_volume = $_POST['lib_volume'];
            $conc_qubit = $_POST['conc_qubit'];
            $conc_qpcr = $_POST['conc_qpcr'];
            $peak = $_POST['peak'];
            $create_date = $_POST['create_date'];
            $comment = $_POST['comment'];

            foreach ($lib_id as $key => $lib_id) {
                    $data[]=array(
                                    'lib_id' => time_format(time(),"Ymd").sprintf("%03d", is_login()).sprintf("%04d",$lib_id),
                                    'sample_id' => $sample_id[$key],
                                    'description' => $description[$key],
                                    'kit' => $kit[$key],
                                    'cycle_num' => $cycle_num[$key],
                                    'template_mass' => $template_mass[$key],
                                    'lib_volume' => $lib_volume[$key],
                                    'conc_qubit' => $conc_qubit[$key],
                                    'conc_qpcr' => $conc_qpcr[$key],
                                    'peak' => $peak[$key],
                                    'create_date' => strtotime($create_date[$key]),
                                    'operator' => is_login(),
                                    'create_time' => time(),
                                    'update_time' => 0,
                                    'comment' => $comment[$key]
                                    );
                }

            $library = M('library');
            $library->addAll($data);

            action_log('create_library','library',$uid,$uid);
            $this->success('成功添加文库',U('library/index'));


        }else{
            $condition['operator'] = $uid;
            $sampleinfo = M('sample')->order('id desc')->getField('sampleid,type,name,source,nucleic_acid',true);
            $this->assign('sampleinfo',$sampleinfo);
            $this->display(add);
        }

        
    }

    public function del(){
        $uid = is_login();
        $library = M('library');
        $condition['id'] = I('sid');
        $library->where($condition)->delete();
        $this->success('删除成功');
        action_log('delete_library','library',$uid,$uid);
    }

    public function update(){
        $uid = is_login();
        $condition['id'] = I('sid');
        if($_POST){

            $data['id'] = $_POST['id'];
            $data['lib_id'] = $_POST['lib_id'];
            $data['sample_id'] = $_POST['sample_id'];
            $data['description'] = $_POST['description'];
            $data['kit'] = $_POST['kit'];
            $data['cycle_num'] = $_POST['cycle_num'];
            $data['template_mass'] = $_POST['template_mass'];
            $data['lib_volume'] = $_POST['lib_volume'];
            $data['conc_qubit'] = $_POST['conc_qubit'];
            $data['conc_qpcr'] = $_POST['conc_qpcr'];
            $data['peak'] = $_POST['peak'];
            $data['create_date'] = strtotime($_POST['create_date']);
            $data['update_time'] = time();
            $data['comment'] = $_POST['comment'];

            $library = M('library');
            $library->save($data);
            action_log('update_library','library',$uid,$uid);
            $this->success('修改成功',U('library/index'));

        }else{
            $library = M('library');
            $result = $library->where($condition)->select();
            $samplecon['operator']=$uid;
            $sample = M('sample')->where($samplecon)->order('id desc')->getField('sampleid,type,name,source,nucleic_acid',true);
            $this->assign('sampleid',$sample);
            $this->assign('library',$result[0]);
            $this->display(update);

        }
    }

    public function detail(){
        $library = M('library');
        $condition['id'] = I('sid');
        $result = $library->where($condition)->select();
        $uid = is_login();
        $this->assign('uid',$uid);
        $this->assign('library',$result[0]);
        $this->display(detail);
    }

}
