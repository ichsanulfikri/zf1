<?php
class GeneralSetup_Model_DbTable_NavigationMenu extends Zend_Db_Table { 
		
	protected $_name = 'tbl_nav_menu';
	protected $_primary = "id";
		
	public function getData($id=0){
		$id = (int)$id;
		
		if($id!=0){
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from(array('tnm'=>$this->_name))
					->where('tnm.id = '.$id);

			$row = $db->fetchRow($select);
		}else{
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from(array('app'=>$this->_name));
								
			$row = $db->fetchAll($select);
		}
		
		return $row;
	}
	
	public function getRoleMenuList($role_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$select =  $db->select()
					->from(array('tnm'=>$this->_name))
					->where("tnm.role_id = '".$role_id."'")
					->order('tnm.seq_order');
								
		$row = $db->fetchAll($select);
		
		if($row){
			return $row;
		}else{
			return null;
		}
		
	}
	
	public function insert(array $data) {
		
        $db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
				->from(array('tnm'=>'tbl_nav_menu'), array(new Zend_Db_Expr('max(seq_order) as max_seq')))
				->where('tnm.role_id = '.$data['role_id']);

		$row = $db->fetchRow($select);
        
		$data['seq_order'] = $row['max_seq']+1;
		
        return parent::insert($data);
    }
	
	public function addData($postData){
		
		$data = array(
		        'role_id' => $postData['role_id'],
				'label' => $postData['label'],
				'title' => $postData['title'],
				'module' => $postData['module'],
				'controller' => $postData['controller'],
				'action' => $postData['action'],
				'order' => $postData['order'],
		);
				
		$this->insert($data);
	}
	
	public function updateData($postData,$id){
		
		$data = array(
		        'role_id' => $postData['role_id'],
				'label' => $postData['label'],
				'title' => $postData['title'],
				'module' => $postData['module'],
				'controller' => $postData['controller'],
				'action' => $postData['action'],
				'order' => $postData['order'],
		);
			
		$this->update($data, 'id = '. (int)$id);
	}
	
	public function deleteData($id){
		if($id!=0){
			$this->delete('id = '. (int)$id);
		}
	}
}
