<?php 
/**
 * 
 */
class Menu extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	function cek(){

		$a = $this->db->query("SELECT * FROM xin_menu WHERE idParent=0")->result_array();
		$max = $this->db->query("SELECT MAX(level) FROM xin_menu")->row_array();
		$array = array();
		foreach ($a as $a) {
			$menu = array();
			$b = $this->db->query("SELECT * FROM xin_menu WHERE idParent=".$a['id']);
			if ($b->num_rows() > 0 ) {
				foreach ($b->result_array() as $b) {
					$menu[] = array(
						'id' => "",
						'class' => "role-checkbox custom-control-input custom-control-input",
						'text' => $b['name'],
						'add_info' => "",
						'value' => $b['value']
					);
						$submenu = array();
						$c = $this->db->query("SELECT * FROM xin_menu WHERE idParent=".$b['id']);
						if ($c->num_rows() > 0 ) {
							foreach ($c->result_array() as $c) {
								$submenu[] = array(
									'id' => "",
									'class' => "role-checkbox custom-control-input custom-control-input",
									'text' => $c['name'],
									'add_info' => "",
									'value' => $c['value']
								);
								
							}
							$menu[] = array(
								'id' => "",
								'class' => "role-checkbox custom-control-input custom-control-input",
								'text' => $b['name'],
								'add_info' => "",
								'value' => $b['value'],
								'items' => $submenu
							);
						}else{
							$menu[] = array(
								'id' => "",
								'class' => "role-checkbox custom-control-input custom-control-input",
								'text' => $b['name'],
								'add_info' => "",
								'value' => $b['value']
							);
						}
				}
				$array[] = array(
					'id' => "",
					'class' => "role-checkbox custom-control-input custom-control-input",
					'text' => $a['name'],
					'add_info' => "",
					'value' => $a['value'],
					'items' => $menu
				);
			}else{
				$array[] = array(
					'id' => "",
					'class' => "role-checkbox custom-control-input custom-control-input",
					'text' => $a['name'],
					'add_info' => "",
					'value' => $a['value']
				);
			}

			
		}
	echo "<pre>";
	print_r($array);
	echo "</pre>";
		
	}
}
 ?>