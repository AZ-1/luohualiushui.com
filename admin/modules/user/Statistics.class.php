<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\User;
use Gate\Package\User\Userinfo;

class Statistics extends \Gate\Libs\Controller {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;
	private $start;
	private $end;
	public function run() {
		$this->_init();	
		$this->getStatisticsResult();
	}
	private function _init()
	{
		if(isset($_REQUEST['start_time']) && !empty($_REQUEST['start_time']))
		{
			$this->start = $_REQUEST['start_time'];
		}else{
			$this->start = date("Y-m-d");
		}
		if(isset($_REQUEST['end_time']) && !empty($_REQUEST['end_time']))
		{
			$this->end = $_REQUEST['end_time'];
		}else{
			$this->end = date("Y-m-d H:m:s"); 
		}
	    
	}
	function getStatisticsResult()
	{
		$res_data=array(
			'status'=>0,
		    'data'=>array(),
            'description'=>'你可能输入了一个错误的日期，请核对',
		);
		 		
		 $total_res = Userinfo::getInstance()->getUserSearchedByDur('0',$this->end);
		 if(isset($total_res) && !empty($total_res))
		 {
			 $res_data['status'] ='1';
			 $res_data['data']['total_res']=$total_res->count_user;
		 }
		 $res_data['data']['duration'] = $this->start.'~'.$this->end; 
		 $filter_res = Userinfo::getInstance()->getUserSearchedByDur($this->start,$this->end);	        
		 if(isset($filter_res) && !empty($filter_res))
		 {
		     $res_data['data']['filter_res']=$filter_res->count_user;
		 }
		 $this->view->res_data=$res_data;
	}
}
