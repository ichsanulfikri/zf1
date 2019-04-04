<?php 
class Perencanaanaudit_PenjadwalanAuditController extends  Base_Base
{
    public function indexAction()
    {
        $db = new Perencanaanaudit_Model_DbTable_Penjadwalan();
            if ($this->_request->isPost()) 
            {
                $larrformData = $this->_request->getPost();
                if (isset($larrformData['Save']))
                {
                    if ($larrformData['id']=='')
                    {
                        $db->addData(array('tgl_mulai_audit'=>$larrformData['mulai_audit'],
                                               'unit_id'=>$larrformData['unit'],
                                               'tgl_kirim_checklist'=>$larrformData['kirim_cheklist'],
                                               'tgl_kumpul_checklist'=>$larrformData['kumpul_cheklist'],
                                               'tgl_visitasi'=>$larrformData['visitasi'],
                                               'tgl_val_temuan_audit'=>$larrformData['val_temuan_audit'],
                                               'tgl_val_rekomds_tpp'=>$larrformData['val_rekomendasi'],
                                               'tgl_val_verifikasi_tpp'=>$larrformData['val_verivikasi']
                        ));
                     } 
                }
            }
            
            $this->view->penjadwal=$db->getDataAll();
//            echo var_dump($this->view->penjadwal);exit;
      }
}
?>