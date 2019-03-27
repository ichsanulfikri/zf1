<?php 

class GeneralSetup_Form_SearchCourse extends Zend_Form
{
		
		
	public function init()
	{
						
		$this->setMethod('post');
		$this->setAttrib('id','myform');
						       
		//Intake
		$this->addElement('select','IdSemester', array(
			'label'=>$this->getView()->translate('Semester'),
		    'required'=>true,
		    'onchange'=>'checkOpenRegister(this.value)'
		));
		
		$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
		
		$this->IdSemester->addMultiOption(null,"-- Please Select --");		
		foreach($semesterDB->fnGetSemestermasterList() as $semester){
			$this->IdSemester->addMultiOption($semester["key"],$semester["value"]);
		}
		
		//Program
		$this->addElement('select','IdCollege', array(
			'label'=>$this->getView()->translate('College Name'), 
			'required'=>true
		));
		
		$collegeDb = new GeneralSetup_Model_DbTable_Collegemaster();
		
		$this->IdCollege->addMultiOption(null,"-- Please Select --");		
		foreach($collegeDb->getCollege()  as $college){
			$this->IdCollege->addMultiOption($college["IdCollege"],$college["CollegeCode"].' - '.$college["ArabicName"]);
		}		
		

		$this->addElement('text','SubjectName', array(
			'label'=>'Subject Name'
		));
		
		//name
		$this->addElement('text','SubjectCode', array(
			'label'=>'Subject Code'
		));
		
		//button
		$this->addElement('submit', 'Search', array(
          'label'=>$this->getView()->translate('Search'),
          'decorators'=>array('ViewHelper')
        ));
        
        
        $this->addDisplayGroup(array('Search'),'buttons', array(
	      'decorators'=>array(
	        'FormElements',
	        array('HtmlTag', array('tag'=>'div', 'class'=>'buttons')),
	        'DtDdWrapper'
	      )
	    ));
        	    
		
        		
	}
	
	
}
?>