<?php 
class Referensikriteriaaudit_ReferensiKriteriaAuditController extends  Base_Base
{
    public function indexAction(){
//     $this->view->hello="hello world";
        $dbNama = new Referensikriteriaaudit_Model_DbTable_Kriteria();
        if ($this->_request->isPost()) {
            $larrformData = $this->_request->getPost();
            if (isset($larrformData['Save'])){
                if ($larrformData['id']==''){
                    $dbNama->addData(array('no_standar_audit'=>$larrformData['nomor'],
                                           'ket_standar_audit'=>$larrformData['ket']));
                    $this->setMessage("Data tersimpan");
                }
                else 
                    $dbNama->updateData(array('no_standar_audit'=>$larrformData['nomor'],
                                              'ket_standar_audit'=>$larrformData['ket']),
                                              $larrformData['id']);
            }
            else if (isset($larrformData['Delete'])){
                $dbNama->deleteData($larrformData['kd_ref_kriteria_audit']);
            }
          else 
              $id=$larrformData['kd_ref_kriteria_audit'];
              $this->view->student= $dbNama->getById($id);
        }
        $this->view->kriterialist=$dbNama->getDataAll();
    }
}
?>