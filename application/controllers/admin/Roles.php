<?php
 /**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the HRSALE License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.hrsale.com/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to hrsalesoft@gmail.com so we can send you a copy immediately.
 *
 * @author   HRSALE
 * @author-email  hrsalesoft@gmail.com
 * @copyright  Copyright © hrsale.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {
	
	 public function __construct() {
        parent::__construct();
		//load the model
		$this->load->model("Roles_model");
		$this->load->model("Xin_model");
	}
	
	/*Function to set JSON output*/
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	
	 public function index()
     {
     	$array = [];
          $parent_key = '0';
          $row = $this->db->query('SELECT id,name FROM xin_menu');
            
          if($row->num_rows() > 0)
          {
              $array = $this->membersTree($parent_key);
          }
   

		$data['menu'] =  json_encode(array_values($array));
		$data['title'] = $this->lang->line('xin_role_urole').' | '.$this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['breadcrumbs'] = $this->lang->line('xin_role_urole');
		$data['path_url'] = 'roles';
		$user = $this->Xin_model->read_employee_info($session['user_id']);
		if($user[0]->user_role_id==1) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/roles/role_list", $data, TRUE);
				$this->load->view('admin/layout/layout_main', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
     }
    public function membersTree($parent_key)
    {
        $row1 = [];
        $row = $this->db->query('SELECT id, name,value from xin_menu WHERE idParent="'.$parent_key.'"')->result_array();
    
        foreach($row as $key => $value)
        {
           $id = $value['id'];
           $row1[$key]['id'] = $value['id'];
           $row1[$key]['class'] = 'role-checkbox custom-control-input custom-control-input';
           $row1[$key]['text'] = $value['name'];
           $row1[$key]['add_info'] = $value['name'];
           $row1[$key]['value'] = $value['value'];
           
           $row1[$key]['items'] = array_values($this->membersTree($value['id']));
        }
  
        return $row1;
    }
 	function cek(){
 		$a = $this->db->query("SELECT * FROM xin_menu")->result_array();
 		$array = array();
		foreach ($a as $row) {
			$sub_data["id"] = $row["id"];
			$sub_data["class"] = 'role-checkbox custom-control-input custom-control-input';
			$sub_data["text"] = $row["name"];
			$sub_data["add_info"] = $row['name'];
			$sub_data["value"] = $row["value"];
			$sub_data["idParent"] = $row["idParent"];
			$array[] = $sub_data;
		}
		foreach($array as $key => &$value){
			 $output[$value["id"]] = &$value;
		}

		foreach($array as $key => &$value){
			if($value["idParent"] && isset($output[$value["idParent"]])){
				$output[$value["idParent"]]["items"][] = &$value;
			}

		}

		foreach($array as $key => &$value){
			 if($value["idParent"] && isset($output[$value["idParent"]])){
			  unset($array[$key]);
			 }
		}
		echo "<pre>";
		print_r($array);
	echo "</pre>";
		// echo json_encode($array);
 	}
    public function role_list()
     {

		$data['title'] = 	$this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view("admin/roles/role_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		
		
		$role = $this->Roles_model->get_user_roles();
		
		$data = array();

          foreach($role->result() as $r) {
			  
			  /* get status*/
			if($r->role_access==1): $r_access = $this->lang->line('xin_role_all_menu'); 
			elseif($r->role_access==2): $r_access = $this->lang->line('xin_role_cmenu'); endif;
			// 
			$created_at = $this->Xin_model->set_date_format($r->created_at);
			//edit
			if($r->role_id==1){
				$roleAccess = '<span data-toggle="tooltip" data-state="primary" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-sm btn-outline-secondary waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-role_id="'. $r->role_id . '"><span class="fas fa-pencil-alt"></span></button></span>';
			} else {
				$roleAccess = '<span data-toggle="tooltip" data-state="primary" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-sm btn-outline-secondary waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-role_id="'. $r->role_id . '"><span class="fas fa-pencil-alt"></span></button></span><span data-toggle="tooltip" data-state="danger" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-sm btn-outline-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->role_id . '"><span class="fas fa-trash-restore"></span></button></span>';
			}

		   $data[] = array(
				$roleAccess,
				$r->role_id,
				$r->role_name,
				$r_access,
				$created_at
		   );
          }

          $output = array(
               "draw" => $draw,
                 "recordsTotal" => $role->num_rows(),
                 "recordsFiltered" => $role->num_rows(),
                 "data" => $data
            );
          echo json_encode($output);
          exit();
     }
	function test(){
			$a = $this->db->query("SELECT * FROM xin_menu")->result_array();
			foreach ($a as $row) {
			$sub_data["id"] = $row["id"];

			$sub_data["class"] = 'role-checkbox custom-control-input custom-control-input';
			$sub_data["text"] = $row["name"];
			$sub_data["add_info"] = $row['name'];
			
			$sub_data["value"] = $row["value"];

			$sub_data["idParent"] = $row["idParent"];

			$data[] = $sub_data;
			}
			foreach($data as $key => &$value){

			 $output[$value["id"]] = &$value;

			}

			foreach($data as $key => &$value){

			 if($value["idParent"] && isset($output[$value["idParent"]])){

			  $output[$value["idParent"]]["items"][] = &$value;

			 }

			}

			foreach($data as $key => &$value){

			 if($value["idParent"] && isset($output[$value["idParent"]])){

			  unset($data[$key]);

			 }

			}
			echo "<pre>";
			print_r($data);
			echo "</pre>";

	}
	 public function read()
	
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('role_id');
		$result = $this->Roles_model->read_role_information($id);
		$data = array(
				'role_id' => $result[0]->role_id,
				'role_name' => $result[0]->role_name,
				'role_access' => $result[0]->role_access,
				'role_resources' => $result[0]->role_resources,
				'get_all_companies' => $this->Xin_model->get_companies(),
				);
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('admin/roles/dialog_role', $data);
		} else {
			redirect('admin/');
		}
	}
	
	// Validate and add info in database
	public function add_role() {
	
		if($this->input->post('add_type')=='role') {		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		/* Server side PHP input validation */
		if($this->input->post('role_name')==='') {
        	$Return['error'] = $this->lang->line('xin_role_error_role_name');
		} else if($this->input->post('role_access')==='') {
			$Return['error'] = $this->lang->line('xin_role_error_access');
		}
		
		$role_resources = implode(',',$this->input->post('role_resources'));
						
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'role_name' => $this->input->post('role_name'),
		'role_access' => $this->input->post('role_access'),
		'role_resources' => $role_resources,
		'created_at' => date('d-m-Y'),
		);
		
		$result = $this->Roles_model->add($data);
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_role_success_added');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	// Validate and update info in database
	public function update() {
	
		if($this->input->post('edit_type')=='role') {
			
		$id = $this->uri->segment(4);
		
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
			
		/* Server side PHP input validation */
		if($this->input->post('role_name')==='') {
        	$Return['error'] = $this->lang->line('xin_role_error_role_name');
		} else if($this->input->post('role_access')==='') {
			$Return['error'] = $this->lang->line('xin_role_error_access');
		}
		
		$role_resources = implode(',',$this->input->post('role_resources'));
						
		if($Return['error']!=''){
       		$this->output($Return);
    	}
	
		$data = array(
		'role_name' => $this->input->post('role_name'),
		'role_access' => $this->input->post('role_access'),
		'role_resources' => $role_resources,
		);	
		
		$result = $this->Roles_model->update_record($data,$id);		
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_role_success_updated');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
		}
	}
	
	public function delete() {
		if($this->input->post('is_ajax')==2) {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$id = $this->uri->segment(4);
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$result = $this->Roles_model->delete_record($id);
			if(isset($id)) {
				$Return['result'] = $this->lang->line('xin_role_success_deleted');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
		}
	}
}
