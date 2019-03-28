<?php 
    class Latih_Form_SearchMhs extends Zend_form{
    
        protected $idNama;
        protected $nama;
        protected $alamat;
        protected $tgllhr;
        protected $nim;
        /**
         * @return the $idNama
         */
        public function getIdNama()
        {
            return $this->idNama;
        }
    
        /**
         * @return the $nama
         */
        public function getNama()
        {
            return $this->nama;
        }
    
        /**
         * @return the $alamat
         */
        public function getAlamat()
        {
            return $this->alamat;
        }
    
        /**
         * @return the $tgllhr
         */
        public function getTgllhr()
        {
            return $this->tgllhr;
        }
    
        /**
         * @return the $nim
         */
        public function getNim()
        {
            return $this->nim;
        }
    
        /**
         * @param field_type $idNama
         */
        public function setIdNama($idNama)
        {
            $this->idNama = $idNama;
        }
    
        /**
         * @param field_type $nama
         */
        public function setNama($nama)
        {
            $this->nama = $nama;
        }
    
        /**
         * @param field_type $alamat
         */
        public function setAlamat($alamat)
        {
            $this->alamat = $alamat;
        }
    
        /**
         * @param field_type $tgllhr
         */
        public function setTgllhr($tgllhr)
        {
            $this->tgllhr = $tgllhr;
        }
    
        /**
         * @param field_type $nim
         */
        public function setNim($nim)
        {
            $this->nim = $nim;
        }
    
        public function init(){
            $this->setMethod('post');
            $this->setAttrib('id', 'searchMhs');
            $this->setAttrib('method','post');
            
            $this->setAttrib('action','latih/hello-world/index');
            
            //add hidden element
            $this->addElement('hidden','idName', array('value'=>$this->idNama));
            $this->addElement('text','Nama',array(
                    'label'=>$this->getView()->translate('Students Name'),'*',
                    'requared'=>true,
                    'value'=>$this->nama
            ));
            $this->addElement('text','Nim',array(
                'label'=>$this->getView()->translate('Nim'),'*',
                'requared'=>true,
                'value'=>$this->nim
            ));
            $this->addElement('text','Alamat',array(
                'label'=>$this->getView()->translate('Address'),'*',
                'requared'=>true,
                'value'=>$this->alamat
            ));
            $this->addElement('text','Tgllhr',array(
                'label'=>$this->getView()->translate('Birth of Date'),'*',
                'requared'=>true,
                'value'=>$this->tgllhr
            ));
            //button
            $this->addElement('submit','save',array(
                          'label'=>$this->getView()->translate('Save'),
                          'decorators'=>array('ViewHelper')
            ));
            //button
            $this->addElement('submit','delete',array(
                'label'=>$this->getView()->translate('Delete'),
                'decorators'=>array('ViewHelper')
            ));
            //button
            $this->addElement('submit','cancel',array(
                'label'=>$this->getView()->translate('Cancel'),
                'decorators'=>array('ViewHelper')
            ));
            //button
            $this->addElement('submit','search',array(
                'label'=>$this->getView()->translate('Search'),
                'decorators'=>array('ViewHelper')
            ));
            
            
            $this->addDisplayGroup(array('save','delete','cancel','search'), 'buttons',array(
                'decorators'=>array(
                    'FormElements',
                    array('HtmlTag',array('tag'=>'div', 'class'=>'buttons')),
                    'DtDdWrapper')
                ));
        }
        
}

?>
  
