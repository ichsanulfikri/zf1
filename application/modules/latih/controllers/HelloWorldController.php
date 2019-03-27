<?php 
class Latih_HelloWorldController extends  Base_Base
{
    public function indexAction(){
        $dbNama = new Latih_Model_DbTable_Nama();
        //$this->view->hello="hello world";
        $form = new Latih_Form_SearchMhs();
        $this->view->form = $form;
        //daerah 1
        if ($this->_request->isPost()){
            //daerah ke 2
            $larrformData = $this->_request->getPost();
            //echo var_dump($larrformData);
            if (isset($larrformData['Save'])){
                //$this->view->tombol=$larrformData['Save'];
                if ($larrformData['id']=='')
                    $dbNama->addData(array('nama'=>$larrformData['nama'],
                                           'nim'=>$larrformData['nim'] ,
                                           'kelas'=>$larrformData['kelas'],
                                           'jurusan'=>$larrformData['jurusan']));
                else 
                    $dbNama->updateData(array('nama'=>$larrformData['nama'],
                                              'nim'=>$larrformData['nim'] ,
                                              'kelas'=>$larrformData['kelas'],
                                              'jurusan'=>$larrformData['jurusan']), $larrformData['id']);
            }
            else if (isset($larrformData['Delete'])){
                //$this->view->tombol=$larrformData['Delete'];
                $dbNama->deleteData($larrformData['idNama']);
            }
            else if (isset($larrformData['Cancel'])){
                //$this->view->tombol=$larrformData['Cancel'];
                $this->view->data=array('id'=>'','nama'=>'',
                                        'nim'=>'','nim'=>'',
                                        'kelas'=>'','kelas'=>'',
                                        'jurusan'=>'','jurusan'=>'');
            }else if (isset($larrformData['Search'])) {
                $nama=$larrformData['nama'];
                $this->view->listnama= $dbNama->getData($nama);
            } else {
                $id=$larrformData['idNama'];
                $this->view->student= $dbNama->getById($id);
                //echo var_dump($this->view->student);exit;
            }
        }
        $this->view->namalist=$dbNama->getDataAll();
    }
}

?>