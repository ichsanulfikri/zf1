<?php 
class Perencanaanaudit_Model_DbTable_Pelaksana extends Zend_Db_Table_Abstract
{
    protected $_name = 'dt_tim_pelaksana';
    protected $_primary = 'kd_tim_pelaksana';
    
        public function addData($data)
        {
            $this->insert($data);
        }
        public function updateData($data,$id)
        {
            $this->update($data,$this->_primary.'='.$id);
        }
        public function getDataAll()
        {
//             $db = Zend_Db_Table::getDefaultAdapter();
//             $sql="SELECT kd_penjadwalan_audit, tgl_mulai_audit, dt_unit_kerja.nama_unit, dt_unit_kerja.kd_unit
//             FROM dt_penjadwalan_audit
//             JOIN dt_unit_kerja
//             ON dt_unit_kerja.unit_id = dt_penjadwalan_audit.unit_id";
//             return $db->fetchAll($sql);
        }
        public function getUnitKerja()
        {
//             $db = Zend_Db_Table::getDefaultAdapter();
//             $sql = "SELECT `unit_id`, `nama_unit`, `kd_unit` FROM `dt_unit_kerja` ";
//             return $db->fetchAll($sql);
        }
}
?>