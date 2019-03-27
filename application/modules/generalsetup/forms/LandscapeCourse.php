<?php 

class GeneralSetup_Form_LandscapeCourse extends Zend_Form
{
	protected $programID;
	protected $landscapeId;	
	protected $SemsterCount;
	protected $idMajoring;
	protected $type;
	protected $idBlock;
	
	public function setProgramID($programID){
		$this->programID = $programID; 
	}
	
	public function setLandscapeId($landscapeId){
		$this->landscapeId= $landscapeId; 
	}
		
	public function setSemsterCount($SemsterCount){
		$this->SemsterCount = $SemsterCount; 
	}
	
	public function setIdMajoring($idMajoring){
		$this->idMajoring = $idMajoring; 
	}
	
	public function setType($type){
		$this->type = $type; 
	}
	
	public function setIdBlock($idBlock){
		$this->idBlock = $idBlock; 
	}
	

	
	public function init()
	{
				
		$this->setMethod('post');
		$this->setAttrib('id','courseForm');
		$this->setAttrib('method', 'post');
		
		if($this->idBlock){
			$this->setAttrib('action', '/generalsetup/landscape/add-block-course/idlandscape/'.$this->landscapeId.'/id/'.$this->programID);
		}else{
			$this->setAttrib('action', '/generalsetup/landscape/add-course/idlandscape/'.$this->landscapeId.'/id/'.$this->programID);
		}
						
		$this->addElement('hidden', 'IdProgram',array('value'=>$this->programID));
		$this->addElement('hidden', 'IdLandscape',array('value'=>$this->landscapeId));
		$this->addElement('hidden', 'SubjectType');
		$this->addElement('hidden', 'IDProgramMajoring',array('value'=>$this->idMajoring));
		$this->addElement('hidden', 'blockid',array('value'=>$this->idBlock));
		
		
		//Majoring
		$this->addElement('select','ProgramMajoring', array(
			'label'=>$this->getView()->translate('Majoring')
			
		));
		$this->ProgramMajoring->setAttrib('disabled',true);
		$this->ProgramMajoring->setValue($this->idMajoring);
		
		$progMajDb = new GeneralSetup_Model_DbTable_ProgramMajoring();	
		$this->ProgramMajoring->addMultiOption(null,"-- Common Course --");	
		$majorings = $progMajDb->getData($this->programID);	
		if(count($majorings)>0){
			foreach($majorings as $majoring){
				$this->ProgramMajoring->addMultiOption($majoring["IDProgramMajoring"],$majoring["BahasaDescription"]);
			}
		}
		
		//Semester Count
		$this->addElement('select','idSemester', array(
			'label'=>$this->getView()->translate('Semester').'*',
		    'required'=>true
		));	
       
		$this->idSemester->addMultiOption(null,"-- Please Select --");		
		for($i=1; $i<=$this->SemsterCount; $i++){
			$this->idSemester->addMultiOption($i,$i);
		}
		
		
		//Subject Type
		$this->addElement('select','IdProgramReq', array(
			'label'=>$this->getView()->translate('Subject Type').'*',
		    'required'=>true,
		    'onchange'=>'validateTotalCreditHours()'
		));
			
		
		$progReqDb = new GeneralSetup_Model_DbTable_Programrequirement();		
		
		$this->IdProgramReq->addMultiOption('',"-- Please Select --");		
		foreach($progReqDb->getListLandscapeCourseType($this->programID,$this->landscapeId,$this->type) as $type){
			$this->IdProgramReq->addMultiOption($type["IdProgramReq"],$type["DefinitionDesc"]);
		}
		
		//Subject/Course
		$this->addElement('select','IdSubject', array(
			'label'=>$this->getView()->translate('Subject Name').'*',
		    'required'=>true,
		    'onchange'=>'getCreditHours(this);'
		));
		
		$subjectDb = new GeneralSetup_Model_DbTable_Subjectmaster();
		
		$this->IdSubject->addMultiOption(null,"-- Please Select --");		
		foreach($subjectDb->getMySubjectList($this->programID,$this->landscapeId) as $subject){
			if($subject["BahasaIndonesia"] != ''){
				$subject_name = $subject["BahasaIndonesia"];
			}else{
				$subject_name = $subject["SubjectName"];
			}
			$this->IdSubject->addMultiOption($subject["IdSubject"],$subject["SubCode"].' : '.$subject_name);
		}
		
		//Credit Hours
		$this->addElement('text','CreditHours', array(
			'label'=>$this->getView()->translate('Credit Hours')	,
			'readonly'=>true,
			    
		));
		
		
		
		//button
		$this->addElement('submit', 'save', array(
          'label'=>$this->getView()->translate('Save'),
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