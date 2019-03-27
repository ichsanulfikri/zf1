<?php 

class GeneralSetup_Form_ScheduleForm extends Zend_Form
{
	protected $idSubject;
	protected $IdSemester;
	protected $IdGroup;
	protected $IdSchedule;
	
	public function setIdSubject($idSubject){
		$this->idSubject = $idSubject;
	}
	public function setIdSemester($idSemester){
		$this->IdSemester = $idSemester;
	}
	public function setIdGroup($IdGroup){
		$this->IdGroup = $IdGroup;
	}
	public function setIdSchedule($IdSchedule){
		$this->IdSchedule = $IdSchedule;
	}
		
	public function init()
	{

		setlocale(LC_TIME, 'id_ID');
		$registry = Zend_Registry::getInstance();
		$locale = $registry->get('Zend_Locale');		
		$this->setMethod('post');
		$this->setAttrib('id','myform');
		
		if($this->IdSchedule){
			$this->setAction('/generalsetup/course-group/edit-schedule');
		}else{
			$this->setAction('/generalsetup/course-group/add-schedule');
		}
		
		
			
		$this->addElement('hidden','IdSubject' );
		$this->IdSubject->setValue($this->idSubject);
		
		$this->addElement('hidden','idSemester');
		$this->idSemester->setValue($this->IdSemester);
		
		$this->addElement('hidden','idGroup');
		$this->idGroup->setValue($this->IdGroup);
		
		$this->addElement('hidden','idSchedule');
		$this->idSchedule->setValue($this->IdSchedule);
		
		
		//date
		$this->addElement('text','sc_date',array(
		    'label'=>'Date',
		    'class'=>'datepicker'
		));
		
		//date
		$this->addElement('select','sc_day', array(
			'label'=>'Day',
		 	'required'=>true
		));
		
				
		$timestamp = strtotime('Sunday');
		for($d=1; $d<=7; $d++){	
			$timestamp = strtotime('+1 day', $timestamp);	
			$international_day = date('l', $timestamp);
			$local_day = strftime('%A', strtotime($international_day));
			$this->sc_day->addMultiOption($international_day,$local_day);
		}
		
		
		
		//time
		$this->addElement('text','sc_start_time', array(
			'label'=>'Start Time',
		 	'required'=>true,
		    'class' => 't1'
		));
		
		//time
		$this->addElement('text','sc_end_time', array(
			'label'=>'End Time',
		 	'required'=>true,
		    'class' => 't2'
		));
		
		$this->addElement('textarea','sc_venue', array(
			'label'=>'Venue',
		 	'required'=>true
		));
		
		//faculty
		$this->addElement('select','idCollege', array(
			'label'=>'Faculty (optional)',
		    'onChange'=>"getLecturer(this,('#idLecturer'))",
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
			'label'=>'Lecturer Name (optional)',
		    'registerInArrayValidator' => false
		));	

		$this->addElement('textarea','sc_remark', array(
		 'label'=>'Remark'
		));
		
		/*$this->addElement('text','sc_class', array(
			'label'=>'Class',
		 	'required'=>true
		));*/
		
	//button
		$this->addElement('submit', 'save', array(
          'label'=>'Submit',
          'decorators'=>array('ViewHelper')
        ));
        
     /*   $this->addElement('submit', 'cancel', array(
          'label'=>'Cancel',
          'decorators'=>array('ViewHelper'),
          'onClick'=>"window.location ='" . $this->getView()->url(array('module'=>'application', 'controller'=>'placement-test','action'=>'index'),'default',true) . "'; return false;"
        ));*/
        
        $this->addDisplayGroup(array('save','cancel'),'buttons', array(
	      'decorators'=>array(
	        'FormElements',
	        array('HtmlTag', array('tag'=>'div', 'class'=>'buttons')),
	        'DtDdWrapper'
	      )
	    ));
		
	}
}
?>