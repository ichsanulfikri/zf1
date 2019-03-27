<?php

class GeneralSetup_Form_EditGroup extends Zend_Form
{
	protected $idSubject;
	protected $IdSemester;
	protected $idFaculty;
	protected $IdGroup;
	
	public function setIdSubject($idSubject){
		$this->idSubject = $idSubject;
	}
	public function setIdSemester($idSemester){
		$this->IdSemester = $idSemester;
	}
	public function setIdFaculty($idFaculty){
		$this->idFaculty = $idFaculty;
	}
	public function setIdGroup($idGroup){
		$this->IdGroup = $idGroup;
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id','EditGroupForm');
		$this->setAction('/generalsetup/course-group/edit-group');
		
		$registry = Zend_Registry::getInstance();
		$locale = $registry->get('Zend_Locale');
		
		$this->addElement('hidden', 'idGroup' );
		$this->idGroup->setValue($this->IdGroup);
		
		$this->addElement('hidden','IdSubject' );
		$this->IdSubject->setValue($this->idSubject);
		
		$this->addElement('hidden','idSemester');
		$this->idSemester->setValue($this->IdSemester);
		
		$this->addElement('text','GroupName', 
			array(
				'label'=>'Group Name',
				'required'=>'true'
			)
		);
		
		$this->addElement('text','GroupCode',
				array(
						'label'=>'Group Code',
						'required'=>'true'
				)
		);
		
		
	    //faculty
		$this->addElement('select','idCollege', array(
			'label'=>'Faculty',
		    'onChange'=>"getLecturer(this,('#IdLecturer'),('#VerifyBy'))",
		    'required'=>false
		));
		
		$collegeDB = new App_Model_General_DbTable_Collegemaster();
		$college_data = $collegeDB->getAllFaculty();		
    			
		$this->idCollege->addMultiOption(null,"-- Please Select --");
		foreach ($college_data as $list){
			if($locale=="id_ID"){
				$college_name = $list["ArabicName"];
			}elseif($locale=="en_US"){
				$college_name = $list["CollegeName"];
			}
			$this->idCollege->addMultiOption($list['IdCollege'],strtoupper($college_name));
		}
				
		$this->addElement('select','IdLecturer', array(
			'label'=>'Lecturer / Coordinator Name',
		    'registerInArrayValidator' => false
		));
		
		
		$this->addElement('select','VerifyBy', array(
			'label'=>'Mark Verify by',
		    'registerInArrayValidator' => false
		));
		
		$staffDb = new GeneralSetup_Model_DbTable_Staffmaster();
		$lecturer = $staffDb->getAcademicStaff();
		$this->IdLecturer->addMultiOption(null,'Please select');
		$this->VerifyBy->addMultiOption(null,'Please select');
		foreach ($lecturer as $list){
		  $this->IdLecturer->addMultiOption($list['IdStaff'],$list['FullName']);
		  $this->VerifyBy->addMultiOption($list['IdStaff'],$list['FullName']);
		}
		
		$this->addElement('text','maxstud',
		    array(
		        'label'=>'Maximum Student',
		        'required'=>'true'
		    )
		);
		
		$this->maxstud->addFilter('StripTags')
		->addValidator('Digits');
		
		//get list academic staff
		/*$staffDB = new GeneralSetup_Model_DbTable_Staffmaster();
		$list_staff = $staffDB->getAcademicStaff($this->idFaculty);
		
		$i=0;
		foreach($list_staff as $s){
			
			$definationsDB = new App_Model_General_DbTable_Definationms();
			$front_salutation = $definationsDB->getData($s["FrontSalutation"]);
			$back_salutation  = $definationsDB->getData($s["BackSalutation"]);
			
			$s["front_salutation"] = $front_salutation["DefinitionCode"];
			$s["back_salutation"] = $back_salutation["DefinitionCode"];
			$list_staff[$i]=$s;
		$i++;}
			
    			
		$this->idLecturer->addMultiOption(null,"-- Select Lecturer --");
		foreach ($list_staff as $list){
			$this->idLecturer->addMultiOption($list['IdStaff'],$list['FullName']);
		}
		*/
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


