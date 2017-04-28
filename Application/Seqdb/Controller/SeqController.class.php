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

    public function add_seq() {
        $uid = is_login();
        $seqdata = $this->processSeqData();

        if(!$seqdata['lib_ids']) {
            $this->error("创建送测失败：请选择至少一个文库");
        }

        $seq = M('seq');
        $seq_id = $seq->add($seqdata);

        // create seqresult for each library

        $library = M('library');

        $lib_ids = $seqdata['lib_ids'];
        $seqresult = M('seqresult');
        foreach ($lib_ids as $lib_id) {
            $seqresult_data = array(
                'seq_id' => intval($seq_id),
                'lib_id' => intval($lib_id)
            );

            $seqresult->add($seqresult_data);

            // 更新文库的送测状态

            $library->where('id=' . intval($lib_id))->save(array(
                'seqed' => $seqresult->where('lib_id=' . intval($lib_id))->count()
            ));
        }

        $seq->where('id=' . intval($seq_id))->save(array(
            'lib_count' => $seqresult->where('seq_id=' . intval($seq_id))->count()
        ));

        action_log('create_seq','seq',$uid,$uid);
        $this->redirect('seq/index');
    }

    public function processSeqData() {
        return array(
            'id' => $_POST['id'],
            'lib_ids' => $_POST['lib_ids'],
            'title' => $_POST['title'],
            'company' => $_POST['company'],
            'send_date' => strtotime($_POST['send_date']),
            'operator' => is_login(),
            'create_time' => time(),
            'update_time' => 0,
            'comment' => $_POST['comment']
        );
    }

    public function add(){
        
        // if($_POST){
        //     $seqdata['seq_id'] = time_format(time(),"Ymd").sprintf("%03d", is_login()).sprintf("%04d",$_POST['seq_id']);
        //     $seqdata['company'] = $_POST['company'];
        //     $seqdata['send_date'] = strtotime($_POST['send_date']);
        //     $seqdata['create_time'] = time();
        //     $seqdata['update_time'] = time();
        //     $seqdata['operator'] = is_login();
        //     $seqdata['comment'] = $_POST['seq_comment'];

        //     $platform = $_POST['platform'];
        //     $seqtype = $_POST['seqtype'];
        //     $lib_volume = $_POST['lib_volume'];
        //     $conc_qubit = $_POST['conc_qubit'];
        //     $conc_qpcr = $_POST['conc_qpcr'];
        //     $peak = $_POST['peak'];
        //     $comment = $_POST['comment'];


        //     $lib_id = $_POST['lib_id'];
        //     foreach ($lib_id as $key => $libid) {
        //         $seqlibdata[] = array(  'seq_id' => $seqdata['seq_id'], 
        //                                 'lib_id' => $libid,
        //                                 'platform' => $platform[$key],
        //                                 'seqtype' => $seqtype[$key],
        //                                 'lib_volume' => $lib_volume[$key],
        //                                 'conc_qubit' => $conc_qubit[$key],
        //                                 'conc_qpcr' => $conc_qpcr[$key],
        //                                 'peak' => $peak[$key],
        //                                 'comment' => $comment[$key]
        //                                 );
        //     }

        //     $seq = M('seq');
        //     $seq->create($seqdata);
        //     $seq->add();
        //     $seqlib = M('seqlib');
        //     $seqlib->addAll($seqlibdata);
        //     $this->success('送样信息添加成功',U('seq/index'));

        $lib_ids = trim($_GET['lib_ids']);
        if(!$lib_ids) {
            $this->error("送测失败，无效的文库信息");
        }

        $libs = M('library')->where('id IN (' . trim($lib_ids) . ')')->select();
        $this->assign('libs', $libs);
        $this->assign('edit_action', 'add_seq');
        $this->display(edit);
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
        $uid = is_login();

        $seq = M('seq');
        $condition['id'] = I('sid');
        $seq_info = $seq->where($condition)->find();

        $seqresult = M('seqresult');
        $seq_results = $seqresult->where('seq_id='. intval($seq_info['id']))->select();
        foreach ($seq_results as $key => $val) {
            $seq_results[$key]['lib_info'] = M('library')->where('id=' . intval($val['lib_id']))->find();
        }

        $this->assign('seq_info', $seq_info);
        $this->assign('seq_results',$seq_results);
        $this->assign('uid',$uid);
        $this->display(detail);
    }
}