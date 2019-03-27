<?php

class GeneralSetup_Form_AddGroup extends Zend_Form
{
	protected $idSubject;
	protected $IdSemester;
	protected $idFaculty;
	
	
	public function setIdSubject($idSubject){
		$this->idSubject = $idSubject;
	}
	public function setIdSemester($idSemester){
		$this->IdSemester = $idSemester;
	}
	public function setIdFaculty($idFaculty){
		$this->idFaculty = $idFaculty;
	}
	
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id','GroupForm');
		$this->setAction('/generalsetup/course-group/add-group');
		
		$registry = Zend_Registry::getInstance();
		$locale = $registry->get('Zend_Locale');
		
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
		    'onChange'=>"getLecturer(this,('#idLecturer'),('#idVerifyBy'))",
		    'required'=>false
		));
		
		$collegeDB = new App_Model_General_DbTable_Collegemaster();
		$college_data = $collegeDB->getFaculty();		
    			
		$this->idCollege->addMultiOption(null,"-- Select Faculty --");
		foreach ($college_data as $list){
			if($locale=="id_ID"){
				$college_name = $list["ArabicName"];
			}elseif($locale=="en_US"){
				$college_name = $list["CollegeName"];
			}
			$this->idCollege->addMultiOption($list['IdCollege'],strtoupper($college_name));
		}
		
				
		$this->addElement('select','idLecturer', array(
			'label'=>'Lecturer / Coordinator Name',
		    'registerInArrayValidator' => false
		));
		
		
		$this->addElement('select','idVerifyBy', array(
			'label'=>'Mark Verify by',
		    'registerInArrayValidator' => false
		));
		
		$this->addElement('text','MaxStud', 
			array(
				'label'=>'Maximum Student',
				'required'=>'true'
			)
		);		
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
		}		*/
		
		
	}
}
?>