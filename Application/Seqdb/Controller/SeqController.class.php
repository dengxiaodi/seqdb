<?php

namespace Seqdb\Controller;
use User\Api\UserApi as UserApi;

class SeqController extends AdminController {

    public function index(){
        $seq = M('seq');
        $data = $seq->order('id desc')->select();
        $uid = is_login();
        $this->assign('uid',$uid);
        $this->assign('seq',$data);
        $this->display();
    }

    public function add(){
        $uid = is_login();
        if($_POST){
            $seqdata['seq_id'] = time_format(time(),"Ymd").sprintf("%03d", is_login()).sprintf("%04d",$_POST['seq_id']);
            $seqdata['company'] = $_POST['company'];
            $seqdata['send_date'] = strtotime($_POST['send_date']);
            $seqdata['create_time'] = time();
            $seqdata['update_time'] = time();
            $seqdata['operator'] = is_login();
            $seqdata['comment'] = $_POST['seq_comment'];

            $platform = $_POST['platform'];
            $seqtype = $_POST['seqtype'];
            $lib_volume = $_POST['lib_volume'];
            $conc_qubit = $_POST['conc_qubit'];
            $conc_qpcr = $_POST['conc_qpcr'];
            $peak = $_POST['peak'];
            $comment = $_POST['comment'];


            $lib_id = $_POST['lib_id'];
            foreach ($lib_id as $key => $libid) {
                $seqlibdata[] = array(  'seq_id' => $seqdata['seq_id'], 
                                        'lib_id' => $libid,
                                        'platform' => $platform[$key],
                                        'seqtype' => $seqtype[$key],
                                        'lib_volume' => $lib_volume[$key],
                                        'conc_qubit' => $conc_qubit[$key],
                                        'conc_qpcr' => $conc_qpcr[$key],
                                        'peak' => $peak[$key],
                                        'comment' => $comment[$key]
                                        );
            }

            $seq = M('seq');
            $seq->create($seqdata);
            $seq->add();
            $seqlib = M('seqlib');
            $seqlib->addAll($seqlibdata);
            $this->success('送样信息添加成功',U('seq/index'));

        }else{
            $lib = M('library')->order('id desc')->select();
            $this->assign('lib', $lib);
            $this->display(add);
        }

        
    }

    public function del(){
        $seq = M('seq');
        $condition['id'] = I('sid');
        $seq_id = $seq->where($condition)->getField('seq_id');
        $seqlib = M('seqlib');
        $con['seq_id'] = $seq_id;
        $seqlib->where($con)->delete();
        $seq->where($condition)->delete();
        $this->success('删除成功');
        action_log('delete_seq','seq',$uid,$uid);
    }

    public function update(){
        $uid = is_login();
        $condition['id'] = I('sid');
        if($_POST){
            $seqdata['id'] = $_POST['sid'];
            $seqdata['seq_id'] = $_POST['seq_id'];
            $seqdata['company'] = $_POST['company'];
            $seqdata['send_date'] = strtotime($_POST['send_date']);
            $seqdata['update_time'] = time();
            $seqdata['comment'] = $_POST['seq_comment'];

            $seqlib_id = $_POST['seqlib_id'];
            $lib_id = $_POST['lib_id'];
            $platform = $_POST['platform'];
            $seqtype = $_POST['seqtype'];
            $lib_volume = $_POST['lib_volume'];
            $conc_qubit = $_POST['conc_qubit'];
            $conc_qpcr = $_POST['conc_qpcr'];
            $peak = $_POST['peak'];
            $comment = $_POST['comment'];

            $seq = M('seq');
            $seq -> save($seqdata);

            $seqlib = M('seqlib');

            foreach ($seqlib_id as $key => $seqlibid) {
                $seqlibdata['id'] = $seqlibid;
                $seqlibdata['seq_id'] = $seqdata['seq_id'];
                $seqlibdata['lib_id'] = $lib_id[$key];
                $seqlibdata['platform'] = $platform[$key];
                $seqlibdata['seqtype'] = $seqtype[$key];
                $seqlibdata['lib_volume'] = $lib_volume[$key];
                $seqlibdata['conc_qubit'] = $conc_qubit[$key];
                $seqlibdata['conc_qpcr'] = $conc_qpcr[$key];
                $seqlibdata['peak'] = $peak[$key];
                $seqlibdata['comment'] = $comment[$key];

                $seqlib -> save($seqlibdata);
            }
           $this->success('修改成功',U('seq/index'));
        }else{
            $seq = M('seq');
            $seqinfo = $seq->where($condition)->select();
            $seq_id = $seq->where($condition)->getField('seq_id');
           
            $seqlib = M('seqlib');
            $con['seq_id'] = $seq_id;
            $seqlib = $seqlib->where($con)->select();

            $library = M('library');
            $library = $library -> order('create_time desc')->select();

            $this->assign('library', $library);

            $this->assign('seqlib',$seqlib);
            $this->assign('seqinfo',$seqinfo[0]);
            $this->display(update);
        }
    }

    public function detail(){
        $seq = M('seq');
        $condition['id'] = I('sid');
        $result = $seq->where($condition)->select();
        $seqid = $seq->where($condition)->getField('seq_id');
        $uid = is_login();

        $seqlib = M('seqlib');
        $seqlibinfo = $seqlib->where('seq_id='.$seqid)->select();

        $this->assign('seq',$result[0]);
        $this->assign('seqlibinfo',$seqlibinfo);
        $this->assign('uid',$uid);
        $this->display(detail);
    }
}
