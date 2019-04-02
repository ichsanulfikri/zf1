<?php 
class Referensikriteriaaudit_ReferensiKriteriaAuditController extends  Base_Base
{
    public function indexAction()
    {
//     $this->view->hello="hello world";
        $dbNama = new Referensikriteriaaudit_Model_DbTable_Kriteria();
        if ($this->_request->isPost()) {
            $larrformData = $this->_request->getPost();
            if (isset($larrformData['Save'])){
                if ($larrformData['id']==''){
                    $dbNama->addData(array('no_standar_audit'=>$larrformData['nomor'],
                                           'ket_standar_audit'=>$larrformData['ket']));
                }
                else {
                    $dbNama->updateData(array('no_standar_audit'=>$larrformData['nomor'],
                                              'ket_standar_audit'=>$larrformData['ket']),
                                              $larrformData['id']);
                    }
                }
                 else if (isset($larrformData['Delete'])){
                     $dbNama->deleteData($larrformData['kd_ref_kriteria_audit']);
                }
                      else {
                          $id=$larrformData['kd_ref_kriteria_audit'];
                          $this->view->kriteria= $dbNama->getById($id);
                      }
        }
        
        $this->view->kriterialist=$dbNama->getDataAll();
    }
    
    public function editAction()
    {
        $db = new Referensikriteriaaudit_Model_DbTable_Kriteria();
        if ($this->_request->isPost())
        {
            $id = (int)$this->getRequest()->getParams('id', 0);
            $fromData = $this->_request->getPost();
            if (isset($fromData['Save']))
            {
                if ($id)
                {
                    $db->updateData(array('no_standar_audit'=>$fromData['nomor'],
                                          'ket_standar_audit'=>$fromData['ket']),$fromData['id']);
                    $this->_redirect('/referensikriteriaaudit/referensi-kriteria-audit');
                }
            }
        } else 
        {
            $db = new Referensikriteriaaudit_Model_DbTable_Kriteria();
            
            $id = (int)$this->getRequest()->getParam('id', 0);
            $this->view->result = $db->find($id);
//             echo var_dump($this->view->result);exit;
            
        }
    }
    
    public function deleteAction()
    {
//         $db = new Referensikriteriaaudit_Model_DbTable_Kriteria();
//         if ($this->_request->isPost())
//         {
//             $id = (int)$this->getRequest()->getParams('id', 0);
//             $fromData = $this->_request->getPost();
//             if (isset($fromData['del']))
//             {
//                 if ($id)
//                 {
//                     $db = new Referensikriteriaaudit_Model_DbTable_Kriteria();
//                     $db->deleteData($id);
//                     $this->_redirect('/referensikriteriaaudit/referensi-kriteria-audit');
//                 }
//             }
//         }else
//         {
//             $db = new Referensikriteriaaudit_Model_DbTable_Kriteria();
            
//             $id = (int)$this->getRequest()->getParam('id', 0);
//             $this->view->result = $db->find($id);
//             //             echo var_dump($this->view->result);exit;
            
//         }
//     }
//         $db = new Referensikriteriaaudit_Model_DbTable_Kriteria();
        if ($this->_request->isPost())    
        {
            $id = (int)$this->getRequest()->getParams('id', 0);
            $fromData = $this->_request->getPost();
            
            //echo var_dump($fromData); exit;
            
            if ($fromData['del'] == 'Yes')
            {
                //$id = (int)$this->getRequest()->getParams('id', 0);
                $id= $this->_request->getPost('id');
                $db = new Referensikriteriaaudit_Model_DbTable_Kriteria();
                
                $db->deleteData($id);
                //echo var_dump($db->deleteData($id));exit;
                
                $this->_redirect('/referensikriteriaaudit/referensi-kriteria-audit');
            }
        } else
        {
            $db = new Referensikriteriaaudit_Model_DbTable_Kriteria();
            
            $id = (int)$this->getRequest()->getParam('id', 0);
            $this->view->result1 = $db->find($id);
            
            
            
        }
    
}
}
?>