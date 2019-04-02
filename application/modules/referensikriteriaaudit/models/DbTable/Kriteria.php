<?php 
class Referensikriteriaaudit_Model_DbTable_Kriteria extends Zend_Db_Table_Abstract {
     
        protected $_name = 'dt_ref_kriteria_audit';
        protected $_primary = 'kd_ref_kriteria_audit';
        
        public function addData($data){
            $this->insert($data);
        }
        public function updateData($data,$id){
            $this->update($data,$this->_primary.'='.$id);
        }
        public function deleteData($id){
          $this->delete($this->_primary.'='.$id);
        }
//         public function deleteKriteria(){
//             $front = Zend_Controller_Front::getInstance();
//             $request = $front->getRequest();
//             $where = array('id = ?'=> $request->getParam("id"));
//             $this->delete($where);
//         }
        public function getDataAll(){
            $db = Zend_Db_Table::getDefaultAdapter();
            $sql=$db->select()
            ->from($this->_name);
            return $db->fetchAll($sql);
        }
        public function getById($id){
            $db = Zend_Db_Table::getDefaultAdapter();
            $sql=$db->select()
            ->from($this->_name)
            ->where("kd_ref_kriteria_audit =?",$id);
            return $db->fetchRow($sql);
        }

        
        
}
?>