<?php 
/**
 * 
 */
class Product extends MY_Controller
{
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	public function __construct()
     {
          parent::__construct();
          //load the login model
          $this->load->model('Company_model');
		  $this->load->model('Xin_model');
    }
    public function index() {
	
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$user = $this->Xin_model->read_user_info($session['user_id']);
		$data['title'] = $this->lang->line('xin_hr_update_application');
		$data['breadcrumbs'] = $this->lang->line('xin_hr_update_application');
		$data['path_url'] = 'product';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if($user[0]->user_role_id==1) {
			$data['subview'] = $this->load->view("admin/product/list_product", $data, TRUE);
			$this->load->view('admin/layout/layout_main', $data); //page load
		} else {
			redirect('admin/dashboard');
		}
	}
}
 ?>