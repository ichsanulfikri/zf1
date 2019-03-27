<?php 

class GeneralSetup_Form_Prerequisite extends Zend_Form
{
	protected $landscapeId;
	protected $idSubject;
	protected $idLandscapeSub;
	protected $idProgramMajoring;
		
	public function setLandscapeId($landscapeId){
		$this->landscapeId = $landscapeId; 
	}
	
	public function setIdSubject($idSubject){
		$this->idSubject = $idSubject; 
	}
	
	public function setIdLandscapeSub($idLandscapeSub){
		$this->idLandscapeSub = $idLandscapeSub; 
	}
	
	
	public function setIdProgramMajoring($idProgramMajoring){
		$this->idProgramMajoring = $idProgramMajoring; 
	}
	
	
	
	public function init()
	{
						
		$this->setMethod('post');
		$this->setAttrib('id','myform');
					
		$this->addElement('hidden', 'IdLandscape',array('value'=>$this->landscapeId));
		$this->addElement('hidden', 'IdSubject',array('value'=>$this->idSubject));
		$this->addElement('hidden', 'IdLandscapeSub',array('value'=>$this->idLandscapeSub));
		
		//type
	 	$this->addElement('select','PrerequisiteType', array(
			'label'=>$this->getView()->translate('Prerequisite Type'),
	 		'onchange'=>'toggleDiv(this);'
		));
		
		$this->PrerequisiteType->addMultiOption(null,$this->getView()->translate('-- Please Select --'));
		$this->PrerequisiteType->addMultiOption(0,$this->getView()->translate('Pass with Grade'));
		$this->PrerequisiteType->addMultiOption(1,$this->getView()->translate('Complete Subject'));
		$this->PrerequisiteType->addMultiOption(2,$this->getView()->translate('Total Credit Hours'));
		$this->PrerequisiteType->addMultiOption(3,$this->getView()->translate('Co-requisite'));

		//Subject/Course
		$this->addElement('select','IdRequiredSubject', array(
			'label'=>$this->getView()->translate('Subject Name'),
		    'required'=>false
		));
		
		$landscapeSubjectDb = new GeneralSetup_Model_DbTable_Landscapesubject();
		
		$this->IdRequiredSubject->addMultiOption(null,"-- Please Select --");		
		foreach($landscapeSubjectDb->getPrerequisiteCourseList($this->landscapeId,$this->idSubject,$this->idLandscapeSub,$this->idProgramMajoring) as $subject){
			$this->IdRequiredSubject->addMultiOption($subject["IdSubject"],$subject["SubCode"].' - '.$subject["BahasaIndonesia"]);
		}		
		/* HIGHSCHOOL DETAILS*/
		/* START DIV */
		$this->addElement(
			'hidden',
			'div_grade',
			array(
				'required' => false,
			    'ignore' => true,
			    'autoInsertNotEmptyValidator' => false,				       
			    'decorators' => array(
			    	array(
			        	'HtmlTag', array(
				            'tag'  => 'div',
				            'id'   => 'div_grade_open',
				            'openOnly' => true,
				            'style'=>'display:none',
			            )
			       	)
			    )
			)
		);
				
		$this->div_grade->clearValidators();
		
		//Grade
		$this->addElement('text','PrerequisiteGrade', array(
			'label'=>$this->getView()->translate('Prerequisite Grade')	
		));
		
		
		/* START END DIV */
		$this->addElement(
			    'hidden',
			    'div_grade2',
			    array(
			        'required' => false,
			        'ignore' => true,
			        'autoInsertNotEmptyValidator' => false,
			        'decorators' => array(
			            array(
			                'HtmlTag', array(
			                    'tag'  => 'div',
			                    'id'   => 'div_grade_open',
			                    'closeOnly' => true
			                )
			            )
			        )
			    )
		);
		$this->div_grade2->clearValidators();
		/* END END DIV */
		
		
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
        
	    
		//button
		$this->addElement('submit', 'save', array(
          'label'=>$this->getView()->translate('Save'),
          'decorators'=>array('ViewHelper'),		
          'onClick'=>"window.location ='" . $this->getView()->url(array('module'=>'generalsetup', 'controller'=>'landscape','action'=>'prerequisite','idlandscape'=>$this->landscapeId),'default',true) . "'; return false;"
        ));
        
        
		
	}
	
	
}
?>