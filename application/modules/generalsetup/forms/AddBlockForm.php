<?php 

class GeneralSetup_Form_AddBlockForm extends Zend_Form
{
	protected $programID;
	protected $landscapeId;
	protected $SemsterCount;
	protected $BlockCount;
	protected $idBlock;
	
	public function setProgramID($programID){
		$this->programID = $programID; 
	}
	
	public function setLandscapeId($landscapeId){
		$this->landscapeId = $landscapeId; 
	}
	
	public function setSemsterCount($SemsterCount){
		$this->SemsterCount = $SemsterCount; 
	}
	
	public function setBlockCount($BlockCount){
		$this->BlockCount = $BlockCount; 
	}
	
	public function setIdBlock($idBlock){
		$this->idBlock = $idBlock; 
	}
	
	
	
	public function init()
	{
						
		$this->setMethod('post');
		$this->setAttrib('id','detailForm');
		
		if($this->idBlock){
			$this->setAction('/generalsetup/landscape/edit-block/');
		}else{
			$this->setAction('/generalsetup/landscape/add-block/');
		}
						
		$this->addElement('hidden', 'id',array('value'=>$this->programID));
		$this->addElement('hidden', 'idlandscape',array('value'=>$this->landscapeId));
		$this->addElement('hidden', 'idblock',array('value'=>$this->idBlock));
		  
		//Semester Count
		$this->addElement('select','semesterid', array(
			'label'=>$this->getView()->translate('Semester')
		));	
		
		if($this->idBlock){
			$this->semesterid->setAttrib('disabled','disabled');
		}else{
			$this->semesterid->setAttrib('required',true);
		}
       
		$this->semesterid->addMultiOption(null,"-- Please Select --");		
		for($i=1; $i<=$this->SemsterCount; $i++){
			$this->semesterid->addMultiOption($i,$i);
		}
		
		
		//Block Count
		$this->addElement('select','block', array(
			'label'=>$this->getView()->translate('Block Level')
		));	
		
		if($this->idBlock){
			$this->block->setAttrib('disabled','disabled');
		}else{
			$this->block->setAttrib('required',true);
		}
		
		$this->block->addMultiOption(null,"-- Please Select --");		
		for($b=1; $b<=$this->BlockCount; $b++){
			$this->block->addMultiOption($b,$b);
		}
		
		
        $this->addElement('text','blockname', array(
			'label'=>$this->getView()->translate('Block Name'),
			'required'=>true));
        
        
        		
		//button
		$this->addElement('submit', 'save', array(
          'label'=>'Save',
          'decorators'=>array('ViewHelper')
        ));       
       
        
        $this->addDisplayGroup(array('save'),'buttons', array(
	      'decorators'=>array(
	        'FormElements',
	        array('HtmlTag', array('tag'=>'div', 'class'=>'buttons')),
	        'DtDdWrapper'
	      )
	    ));
        
       
        
	}
	
	
}
?>