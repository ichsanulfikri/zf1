<?php 

class GeneralSetup_Form_ProgramRequirement extends Zend_Form
{
	protected $programID;
	protected $IdProgramReq;
	
	public function setProgramID($programID){
		$this->programID = $programID; 
	}
	
	public function setIdProgramReq($IdProgramReq){
		$this->IdProgramReq = $IdProgramReq; 
	}
	
	public function init()
	{
		$programDB = new App_Model_Record_DbTable_Program();
		$programList = $programDB->getData();
				
		$this->setMethod('post');
		$this->setAttrib('id','detailForm');
						
		$id = $this->createElement('hidden', 'program_id');
		$id->setValue($this->programID);
		$id->removeDecorator('DtDdWrapper');
		$id->removeDecorator('HtmlTag');
		$id->removeDecorator('Label');
		$this->addElement($id);
		

		/*** Course Type ***/
		$definationDB = new App_Model_General_DbTable_Definationms();
		$element = new Zend_Form_Element_Select('SubjectType');
		$element	->setLabel('Course Type')					
					->addMultiOption(null,"--Select Course Type--");
					
		if(isset($this->IdProgramReq)){
			$element->setAttrib('disabled',true);
		}else{
			$element->setRequired('true');
		}			
		
		foreach ($definationDB->getDataByType(8) as $list){
			$element->addMultiOption($list['idDefinition'],$list['DefinitionDesc']);
		}				
		$this->addElement($element);
		
		/*** Min Credit ***/
		$this->addElement('text','CreditHours', array(
		'label'=>'Minimum Credit',
		'required'=>true));
		
		
		//Compulsory		
		$this->addElement('radio','Compulsory', array(
			'label'=>'Compulsory',
			'required'=>true
		));
		$this->Compulsory->setMultiOptions(array('1'=>'Yes', '2'=>'No'))
		->setValue(1)->setSeparator('<br>');
		
		
		/*$this->addElement('checkbox','Compulsory', array(
			'label'=>$this->getView()->translate('Compulsory'),
		    "checked" => "checked"
		));*/
		
		//button
		$this->addElement('submit', 'save', array(
          'label'=>'Submit',
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