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
		// echo json_encode($array);
		
	}
	public function getItem()
    {
          $array = [];
          $parent_key = '0';
          $row = $this->db->query('SELECT id,name FROM xin_menu');
            
          if($row->num_rows() > 0)
          {
              $array = $this->membersTree($parent_key);
          }else{
              $array=["id"=>"0","name"=>"No Members presnt in list","text"=>"No Members is presnt in list","items"=>[]];
          }
          echo "<pre>";
	print_r($array);
	
   			print_r(array_values($array));echo "</pre>";
          // echo json_encode();
    }
   
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
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
      
}
 ?>