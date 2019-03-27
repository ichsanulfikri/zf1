<?php 
class GeneralSetup_Form_Batchlandscape extends Zend_Form {
	
	public function init()
	{
        //parent::__construct($options);

        $this->setName('upload');
        $this->setAttrib('enctype', 'multipart/form-data');
		$this->setAttrib('action', $this->getView()->url(array('module'=>'generalsetup', 'controller'=>'landscape','action'=>'batch-landscape'),'default',false) );
          
        $file = new Zend_Form_Element_File('file');
        $file->setLabel('File')
            ->setDestination(APPLICATION_PATH  . '/tmp')
            ->setRequired(true);
		$this->addElement($file);
	}
}
?>