<?php
namespace Seqdb\Model;
use Think\Model;
class SampleModel extends Model{
   protected $_validate = array(
     array('sampleid','require','Sampleid is requied!'), 
     array('operator','require','Operator is requied!'),
     array('sampleid','','Sampleid is already exist！',0,'unique',1),
   );
   protected $_auto = array ( 
     array('update_time','time',2,'function'), 
     );
}