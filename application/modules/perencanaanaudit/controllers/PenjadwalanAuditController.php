<?php 
class Perencanaanaudit_PenjadwalanAuditController extends  Base_Base
{
    public function indexAction()
    {
        $db = new Perencanaanaudit_Model_DbTable_Penjadwalan();
            if ($this->_request->isPost()) 
            {
                $larrfromData = $this->_request->getPost();
                if (isset($larrfromData['Save']))
                {
                    if ($larrfromData['id']=='')
                    {
                        $db->addData(array('tgl_mulai_audit'=>$larrfromData['mulai_audit'],
                                           'unit_id'=>$larrfromData['unit'],
                                           'tgl_notifikasi'=>$larrfromData['notif'],
                                           'tgl_kirim_checklist'=>$larrfromData['kirim_cheklist'],
                                           'tgl_kumpul_checklist'=>$larrfromData['kumpul_cheklist'],
                                           'tgl_visitasi'=>$larrfromData['visitasi'],
                                           'tgl_val_temuan_audit'=>$larrfromData['val_temuan_audit'],
                                           'tgl_val_rekomds_tpp'=>$larrfromData['val_rekomendasi'],
                                           'tgl_val_verifikasi_tpp'=>$larrfromData['val_verivikasi']
                        ));
                     }
//                      echo var_dump($larrformData);exit;
                }
            }
            $this->view->unitkerja=$db->getUnitKerja();
            
            $this->view->penjadwal=$db->getDataAll();
//             echo var_dump($this->view->penjadwal=$db->getDataAll());exit;
      }
      public function editAction()
      {
          $db = new Perencanaanaudit_Model_DbTable_Penjadwalan();
          if ($this->_request->isPost())
          {
              $id = (int)$this->getRequest()->getParams('id', 0);
              $larrfromData = $this->_request->getPost();
              if (isset($larrfromData['Save']))
              {
                  if ($id)
                  {
                      $db->updateData(array('tgl_mulai_audit'=>$larrfromData['mulai_audit'],
                                            'unit_id'=>(int)$larrfromData['unit'],
                                            'tgl_notifikasi'=>$larrfromData['notif'],
                                            'tgl_kirim_checklist'=>$larrfromData['kirim_cheklist'],
                                            'tgl_kumpul_checklist'=>$larrfromData['kumpul_cheklist'],
                                            'tgl_visitasi'=>$larrfromData['visitasi'],
                                            'tgl_val_temuan_audit'=>$larrfromData['val_temuan_audit'],
                                            'tgl_val_rekomds_tpp'=>$larrfromData['val_rekomendasi'],
                                            'tgl_val_verifikasi_tpp'=>$larrfromData['val_verivikasi']),$larrfromData['id']);
                      $this->_redirect('/perencanaanaudit/penjadwalan-audit');
                  }
                  echo var_dump($larrformData);exit;
              }
              
          } else
          {
              $db = new Perencanaanaudit_Model_DbTable_Penjadwalan();
              
              $id = (int)$this->getRequest()->getParam('id', 0);
              
              
              
              $this->view->result = $db->find($id);
              //             echo var_dump($this->view->result);exit;
              
          }
          
          $this->view->unitkerja=$db->getUnitKerja();
          //$this->view->penjadwal=$db->getDataAll();
      }
      public function timpelaksanaAction()
      {
          $db = new Perencanaanaudit_Model_DbTable_Pelaksana();
          if ($this->_request->isPost())
          {
              $larrformData = $this->_request->getPost();
              if (isset($larrfromData['Save']))
              {
                  if ($larrfromData['id']=='')
                  {
                      $db->addData(array('kd_penjadwalan_audit'=>$larrfromData['nama_unit'],
                                            'kd_tim_audit'=>$larrfromData['auditor']
                      ));
                  }
              }
//               echo var_dump($this->_request->getPost());exit;
          }
          $this->view->sdm=$db->getSdm();
          
          $this->view->pelaksana=$db->getDataAll();
      }
}
?>