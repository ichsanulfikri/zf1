<?php 

class GeneralSetup_Form_LandscapeForm extends Zend_Form
{
	protected $programID;
	protected $landscapeId;
	protected $landscapeType;
	
	public function setProgramID($programID){
		$this->programID = $programID; 
	}
	
	public function setLandscapeId($landscapeId){
		$this->landscapeId = $landscapeId; 
	}
	
	public function setLandscapeType($landscapeType){
		$this->landscapeType = $landscapeType; 
	}
		
	
	public function init()
	{
						
		$this->setMethod('post');
		$this->setAttrib('id','detailForm');
		//$this->setAction('/generalsetup/landscape/add-landscape/');
						
		$this->addElement('hidden', 'IdProgram',array('value'=>$this->programID));
		$this->addElement('hidden', 'IdLandscape',array('value'=>$this->landscapeId));
		       
	    $this->addElement('select','LandscapeType', array(
			'label'=>$this->getView()->translate('Landscape Type'),
	        'onchange'=>'showAddDrop();'
			
		));
		
		if($this->landscapeId){
			$this->LandscapeType->setAttrib('disabled',true);
		}
		
		//get landscape type
		$definationDB = new App_Model_General_DbTable_Definationms();
		
		$this->LandscapeType->addMultiOption(null,$this->getView()->translate('-- Please Select --'));
		foreach ($definationDB->getDataByType(6) as $list){
			$this->LandscapeType->addMultiOption($list['idDefinition'],$list['DefinitionDesc']);
		}
		
		
		
		//get list intake Efective Intake
		
		$this->addElement('select','IdStartSemester', array(
			'label'=>$this->getView()->translate('Efective Intake')			
		));
		
		$intakeDB = new App_Model_Record_DbTable_Intake();
		
		$this->IdStartSemester->addMultiOption(null,$this->getView()->translate('-- Please Select --'));
		foreach ($intakeDB->getData() as $list){
			$this->IdStartSemester->addMultiOption($list['IdIntake'],$list['IntakeDesc']);
		}		
		
        $this->addElement('text','TotalCreditHours', array(
			'label'=>$this->getView()->translate('Total Credit Hours'),
			'required'=>true));
        
        $this->addElement('text','SemsterCount', array(
			'label'=>$this->getView()->translate('Total Semester'),
            'value'=>'8',
			'required'=>true));
        $this->SemsterCount->addValidator('int');
        
        
        $this->addElement('text','Blockcount', array(
			'label'=>$this->getView()->translate('Total Block'),
            'value'=>'8'));
        $this->Blockcount->addValidator('int');
        
       
         $this->addElement('textarea','ProgramDescription', array(
			'label'=>$this->getView()->translate('Description')
			));
       
		//Add/Drop
		$this->addElement('checkbox','AddDrop', array(
			'label'=>$this->getView()->translate('Add/Drop')
		));
		
		if($this->landscapeType==44){
			$this->AddDrop->setAttrib('disabled', 'disabled');			
		}	
		
		if($this->landscapeType==43){			
			$this->Blockcount->setAttrib('disabled', 'disabled');
		}
		
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