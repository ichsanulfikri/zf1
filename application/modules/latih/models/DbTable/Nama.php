<?php 
class Latih_Model_DbTable_Nama extends Zend_Db_Table_Abstract {
     
        protected $_name = 'tbl_nama';
        protected $_primary = 'idNama';
        
        public function addData($data){
            $this->insert($data);
        }
        public function deleteData($id){
           $this->delete($this->_primary.'='.$id);

        }
        public function updateData($data,$id){
            $this->update($data,$this->_primary.'='.$id);
        }
        public function getById($id){
            $db = Zend_Db_Table::getDefaultAdapter();
            $sql=$db->select()
            ->from($this->_name)
            ->where("idNama =?",$id);
            return $db->fetchRow($sql);
        }
        public function getData($nama){
            $db = Zend_Db_Table::getDefaultAdapter();
            $sql=$db->select()
            ->from($this->_name)
            ->where("nama like '%" .$nama. "%'");
            //echo $sql;exit;
            return $db->fetchAll($sql);
        }
        public function getDataAll(){
            $db = Zend_Db_Table::getDefaultAdapter();
            $sql=$db->select()
            ->from($this->_name);
            return $db->fetchAll($sql);
        }
}
?>