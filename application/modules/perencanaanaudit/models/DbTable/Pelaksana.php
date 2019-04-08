<?php 
class Perencanaanaudit_Model_DbTable_Pelaksana extends Zend_Db_Table_Abstract
{
    protected $_name = 'dt_tim_pelaksana';
    protected $_primary = 'kd_tim_pelaksana';
    
        public function addData($data)
        {
            $this->insert($data);
        }
        public function addAuditor()
        {
            $db=  Zend_Db_Table::getDefaultAdapter();
            $sql="INSERT INTO dt_tim_pelaksana(kd_penjadwalan_audit, kd_tim_audit)
                  select kd_penjadwalan_audit, kd_tim_audit
                  from dt_penjadwalan_audit, dt_tim_audit";
            return $db->fetchAll($sql);
        }
        public function updateData($data,$id)
        {
            $this->update($data,$this->_primary.'='.$id);
        }
        public function getDataAll()
        {
            $db = Zend_Db_Table::getDefaultAdapter();
            $sql = "SELECT b.tgl_mulai_audit, c.nama_unit, e.nama_lengkap
                    FROM dt_tim_pelaksana AS a 
                    INNER JOIN dt_penjadwalan_audit AS b ON a.kd_penjadwalan_audit = b.kd_penjadwalan_audit
                    INNER JOIN dt_unit_kerja AS c ON b.unit_id = c.unit_id
                    INNER JOIN dt_tim_audit AS d ON a.kd_tim_audit = d.kd_tim_audit
                    INNER JOIN dt_diri_sdm AS e on d.kd_diri_sdm = e.kd_diri_sdm";
             return $db->fetchAll($sql);
        }
        public function getSdm()
        {
            $db = Zend_Db_Table::getDefaultAdapter();
            $sql = "SELECT `kd_diri_sdm`, `nama_lengkap` FROM `dt_diri_sdm` ";
            return $db->fetchAll($sql);
        }
}
?>