<?php
class GeneralSetup_Model_DbTable_Semester extends Zend_Db_Table_Abstract
{
	protected $_name = 'tbl_semester';
	private $lobjDbAdpt;

	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}

	public function fngetSemesterList(){//Function to get the subject master list
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array('a'=>'tbl_semester'),array("key"=>"a.IdSemester","value"=>"CONCAT_WS(' ',IFNULL(b.SemesterMainName,''),IFNULL(a.year,''))"))
		->join(array('b' => 'tbl_semestermaster'),'a.Semester = b.IdSemesterMaster ')
		->where('a.Active = 1')
		->where('b.Active = 1')
		->order("a.year");
		//echo $lstrSelect;die();
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	public function fngetidsemesterstatus(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array('a'=>'tbl_semesterstatus'),array("a.IdSemesterStatus"))
		->where('a.SemesterStatus = 1');
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;

	}

	public function fngetSemesterDetailList() { //Function to get the semester details list
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array('a'=>'tbl_semester'));
		return $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
	}
        
        public function fngetSemesterMasterDetailList() { //Function to get the semester details list
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array('a'=>'tbl_semestermaster'));
		return $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
	}

	public function fngetSemesterDetail(){//Function to get the subject semester list
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array('a'=>'tbl_semester'),array("key"=>"a.IdSemester","value"=>"a.SemesterCode"))
                ->where('a.SemesterCode <> ?',' ')
		->order("a.SemesterCode");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	public function fngetlandscapeSemesterDetails() { //Function to get the user details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_semester'),array('IdSemester'))
		->join(array('b'=>'tbl_semestermaster'),'a.Semester = b.IdSemesterMaster')
		->where('a.StudentIntake = ?',1)
		->where('a.Active = 1');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fngetlandscapeoldSemesterLandscapeDetails() { //Function to get the user details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_semester'),array('IdSemester'))
		->join(array('b'=>'tbl_semestermaster'),'a.Semester = b.IdSemesterMaster')
		->where('a.SemesterStartDate < now()')
		->where('a.Active = 1');

		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fngetlandscapeprogSemesterDetails() { //Function to get the user details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_semester'),array('IdSemester'))
		->join(array('b'=>'tbl_semestermaster'),'a.Semester = b.IdSemesterMaster');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fnaddSemester($formData,$idUniversity,$CodeType,$objIC) {////Function for adding the University details to the table
                $lintIdSemester = $this->insert($formData);
		if($CodeType == 1){
			$SemCode = $this->fnGenerateCode($idUniversity,$lintIdSemester,$formData['ShortName'],'Semester',$objIC);
			$formData1['SemesterCode'] = $SemCode;
			$this->fnupdateSemester($formData1,$lintIdSemester);
		}
	}

	public function fnupdateSemester($formData,$lintIdSemester) { //Function for updating the university
		unset ( $formData ['Save'] );
		$where = 'IdSemester = '.$lintIdSemester;
		$this->update($formData,$where);
	}

	public function fnSearchSemester($post = array()) { //Function for searching the university details
		//$field7 = "a.Active = ".$post["field7"];
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('b' => 'tbl_semestermaster'),array("IdSemesterMaster"))
		//->join(array('b' => 'tbl_semestermaster'),'a.Semester = b.IdSemesterMaster')
		->where('LOWER(b.SemesterMainName) like "%" ? "%"',strtolower($post['field3']))
                ->where('b.SemesterMainDefaultLanguage like "%" ? "%"',strtolower($post['field28']))
		->where('LOWER(b.SemesterMainCode) like "%" ? "%"',strtolower($post['field2']));
		//->where($field7);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	public function fnGenerateCode($idUniversity,$IdInserted,$semShortName,$page,$objIC){
		$result = 	$objIC->fnGetInitialConfigDetails($idUniversity);
		$sepr	=	$result[$page.'Separator'];
		$str	=	$page."CodeField";
		$strText=	$page."CodeText";
		for($i=1;$i<=4;$i++){
			$check = $result[$str.$i];
			$Text = $result[$strText.$i];
			switch ($check){
				case 'Year':
					$code	= date('Y');
					break;
				case 'Uniqueid':
					$code	= $IdInserted;
					break;
				case 'ShortName':
					$code	= $semShortName;
					break;
				case 'Text':
					$code	= $Text;
					break;
				default:
					break;
			}
			if($i == 1) $accCode 	 =  $code;
			else 		$accCode	.=	$sepr.$code;
		}
		return $accCode;
	}

	public function fnGetShcemeList(){
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a"=>"tbl_scheme"),array("key"=>"a.IdScheme","value"=>"a.EnglishDescription"));

		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	public function fnSemesterList() {
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a"=>"tbl_semester"),array("key"=>"a.IdSemester","value"=>"a.ShortName"))
		->where("a.Active = 1")
		->order("a.ShortName");
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnGetAcademicYearList(){
	  $lstrSelect = $this->lobjDbAdpt->select()
	  ->from(array("a"=>"tbl_academic_year"),array("key"=>"a.ay_id","value"=>"a.ay_code"))
	  ->order("a.ay_start_date DESC");
	
	  $larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
	  return $larrResult;
	}

	/**
	 *
	 * Function get the semester details of a semester.
	 * @param $IdSemester
	 */
	public function fnGetSemesterDetails($IdSemesterMain,$mode="") {
		if(trim($mode)=="") {
			$lstrSelect = $this->select()
			->setIntegrityCheck(false)
			->from(array("a"=>"tbl_semester"))
			//->join(array('b' => 'tbl_definationms'),'a.SemesterStatus =b.idDefinition',array('b.DefinitionDesc as SemesterStatus','a.SemesterStatus as IdSemesterStatus'))
			->join(array('c' => 'tbl_program'),'a.Program =c.IdProgram',array('c.ProgramName as Program','c.IdProgram as IdProgram'))
			->join(array('d' => 'tbl_intake'),'a.StudentIntake =d.IdIntake',array('d.IntakeId as IntakeId','d.IdIntake as IdIntake'))
			->where("a.Active = 1")
			->where("a.Semester = $IdSemesterMain");

			$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		}
		else if($mode=="copy") {
			$lstrSelect = $this->select()
			->setIntegrityCheck(false)
			->from(array("a"=>"tbl_semester"))
			->where("a.Active = 1")
			->where("a.Semester = $IdSemesterMain");

			$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		}
		return $larrResult;
	}

	public function fnDeleteSemesterDetail($IdSemester) {
		$table = "tbl_semester";
		$where = $this->lobjDbAdpt->quoteInto('IdSemester = ?', $IdSemester);
		$this->lobjDbAdpt->delete($table, $where);
	}

	/**
	 *
	 * Method to delete all the previous mappings
	 */
	public function fndeleteSemesterMappings($semesterMasterId) {
		$table = "tbl_semester";
		$where = $this->lobjDbAdpt->quoteInto('Semester = ?', $semesterMasterId);
		$this->lobjDbAdpt->delete($table, $where);
	}

        public function fngetsemdetailByid($IdSemester){
          $table = "tbl_semester";
          $where = $this->lobjDbAdpt->quoteInto('IdSemester = ?', $IdSemester);
          $this->lobjDbAdpt->fetchRow($table, $where);
        }
        
         public function fngetsemmainByid($IdSemester){
          $table = "tbl_semestermaster";
          $where = $this->lobjDbAdpt->quoteInto('IdSemesterMaster = ?', $IdSemester);
          $this->lobjDbAdpt->fetchAll($table, $where);
        }
        

        public static function checkstartdate($startdate,$formdata){         
           $semesterId = $formdata['IdSemester'];
           $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
           $idSem = explode('_',$formdata['IdSemester']);
          
           if($idSem['1']=='detail') {          
                $lstrSelect = $lobjDbAdpt->select()
                                          ->from(array("a"=>"tbl_semester"))
                                          ->where('a.IdSemester = ?',$idSem['0']);
                $semdetail = $lobjDbAdpt->fetchAll($lstrSelect);
                $semstartDate = $semdetail[0]['SemesterStartDate'];
                $semendtDate = $semdetail[0]['SemesterEndDate'];
           } else if ($idSem['1']=='main') {
                $lstrSelect = $lobjDbAdpt->select()
                                    ->from(array("a"=>"tbl_semestermaster"))
                                    ->where('a.IdSemesterMaster = ?',$idSem['0']);
                $semdetail = $lobjDbAdpt->fetchAll($lstrSelect);
                $semstartDate = $semdetail[0]['SemesterMainStartDate'];
                $semendtDate = $semdetail[0]['SemesterMainEndDate'];
           }

           if ($startdate >= $semstartDate && $startdate <= $semendtDate){
            return true;
           }else{
            return false;
           }
          
          
          
          
          
        }

        public function getsemDet($idSem){
          $table = "tbl_semester";
          $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
          $lstrSelect = $lobjDbAdpt->select()
                  ->from(array("a"=>"tbl_semester"))
                  ->where('a.IdSemester = ?',$idSem);
          return $semdetail = $lobjDbAdpt->fetchAll($lstrSelect);
        }
        
        public function getsemMainDet($idSem){          
          $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
          $lstrSelect = $lobjDbAdpt->select()
                  ->from(array("a"=>"tbl_semestermaster"))
                  ->where('a.IdSemesterMaster = ?',$idSem);
          return $semmaindetail = $lobjDbAdpt->fetchAll($lstrSelect);
        }
        
        

        /**
         * Function to check duplicate SEMESTER CODE
         * @author Vipul
         */
        public function fncheckdupSemCode($condition) {
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()->from('tbl_semester',array("num"=>"COUNT(*)"))->where($condition);
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		
		public function fncheckdupMainSemCode($conditionmainsem){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()->from('tbl_semestermaster',array("num"=>"COUNT(*)"))->where($conditionmainsem);
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}

        public function getAllsemesterList(){
                $semList = array();
                
			$lstrSelect = $this->lobjDbAdpt->select()
                                   ->from(array('a'=>'tbl_semester'))
                                   ->where("a.Active =?",1)
                                   ->where('a.SemesterCode <> ?',' ');
			$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		
                $Select = $this->lobjDbAdpt->select()
                                   ->from(array('a'=>'tbl_semestermaster'))
                                   ->where("a.IsCountable =?",1)
                                    ->where("a.DummyStatus IS NULL")
                                   ->where('a.SemesterMainCode <> ?',' ');                          
			$Result = $this->lobjDbAdpt->fetchAll($Select);

                foreach($larrResult as $arr){
                    $tem['key'] = $arr['IdSemester'].'_detail';
                    $tem['value'] = $arr['SemesterMainName'];
                    $semList[] = $tem;
                }
                
                foreach($Result as $arr){
                    $tem['key'] = $arr['IdSemesterMaster'].'_main';
                    $tem['value'] = $arr['SemesterMainName'];
                    $semList[] = $tem;
                }
                
               // print_r($semList);
                return $semList;
        }

        public function getStudentSemester($idscheme,$idprogram,$studentId){
            $semList = array();
            
            $lstrSelect = $this->lobjDbAdpt->select()
                                   ->from(array('a'=>'tbl_semester'))
                                   ->where("a.Active =?",1)
                                   ->where("a.Program =?",$idprogram)
                                   ->where('a.SemesterCode <> ?',' ');
            $larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);

            $Select = $this->lobjDbAdpt->select()
                                   ->from(array('a'=>'tbl_semestermaster'))
                                   ->where("a.IsCountable =?",1)
                                    ->where("a.DummyStatus IS NULL")
                                   ->where("a.Scheme =?",$idscheme)
                                   ->where('a.SemesterMainCode <> ?',' ');
            
            $Result = $this->lobjDbAdpt->fetchAll($Select);
            foreach($larrResult as $arr){
                $tem['key'] = $arr['IdSemester'].'_detail';
                $tem['name'] = $arr['SemesterCode'];
                $semList[] = $tem;
            }

            foreach($Result as $arr){
                $tem['key'] = $arr['IdSemesterMaster'].'_main';
                $tem['name'] = $arr['SemesterMainCode'];
                $semList[] = $tem;
            }
            return $semList;
        }
        
        public function fngetsemesterdates($semcode){
           $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
           $idSem = explode('_',$semcode);
           if($idSem['1']=='detail') {          
                $lstrSelect = $lobjDbAdpt->select()
                                          ->from(array("a"=>"tbl_semester"),array('StartDate'=>'a.SemesterStartDate','EndDate'=>'a.SemesterEndDate AS EndDate'))
                                          ->where('a.SemesterCode = ?',$idSem['0']);
                $semdetail = $lobjDbAdpt->fetchAll($lstrSelect);
           } else if ($idSem['1']=='main') {
                $lstrSelect = $lobjDbAdpt->select()
                                          ->from(array("a"=>"tbl_semestermaster"),array('StartDate'=>'a.SemesterMainStartDate','EndDate'=>'a.SemesterMainEndDate'))
                                          ->where('a.SemesterMainCode = ?',$idSem['0']);
                $semdetail = $lobjDbAdpt->fetchAll($lstrSelect);
           }
           return $semdetail;
        }
		
        
public function getAllsemesterListCode(){
		$semList = array();

		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array('a'=>'tbl_semester'))
		->where("a.Active =?",1)
		->where('a.SemesterCode <> ?',' ');
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);

		$Select = $this->lobjDbAdpt->select()
		->from(array('a'=>'tbl_semestermaster'))
		->where("a.IsCountable =?",1)
		->where("a.DummyStatus IS NULL")
		->where('a.SemesterMainCode <> ?',' ');
		$Result = $this->lobjDbAdpt->fetchAll($Select);

		foreach($larrResult as $arr){
			$tem['key'] = $arr['SemesterCode'];
			$tem['value'] = $arr['SemesterCode'];
			$semList[] = $tem;
		}

		foreach($Result as $arr){
			$tem['key'] = $arr['SemesterMainCode'];
			//$tem['value'] = $arr['SemesterMainCode'];
			$tem['value'] = $arr['SemesterMainName'];
			$semList[] = $tem;
		}
		return $semList;
	}


	public function getAllsemesterListCodeID(){
		$semList = array();

		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array('a'=>'tbl_semester'))
		->where("a.Active =?",1)
		->where('a.SemesterCode <> ?',' ');
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);

		$Select = $this->lobjDbAdpt->select()
		->from(array('a'=>'tbl_semestermaster'))
		->where("a.IsCountable =?",1)
		->where("a.DummyStatus IS NULL")
		->where('a.SemesterMainCode <> ?',' ');
		$Result = $this->lobjDbAdpt->fetchAll($Select);

		foreach($larrResult as $arr){
			$tem['key'] = $arr['IdSemester']. '_detail';
			$tem['value'] = $arr['SemesterCode'];
			$semList[] = $tem;
		}

		foreach($Result as $arr){
			$tem['key'] = $arr['IdSemesterMaster']. '_main';
			$tem['value'] = $arr['SemesterMainCode'];
			$semList[] = $tem;
		}
		return $semList;
	}



	public function getsemesterofstudent($idStudent){
		$semList = array();
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array('a'=>'tbl_studentsemesterstatus'))
		->where("a.IdStudentRegistration =?",$idStudent);
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		foreach($larrResult as $list){
			if($list['idSemester']!=''){
				$Select1 = $this->lobjDbAdpt->select()
				->from(array('a'=>'tbl_semester'),array('a.SemesterCode'))
				->where("a.IdSemester =?",$list['idSemester']);
				$Result1 = $this->lobjDbAdpt->fetchRow($Select1);
				$semList[] = array('key' => $Result1['SemesterCode'],'value' => $Result1['SemesterCode']);
			}

			if($list['IdSemesterMain']!=''){
				$Select = $this->lobjDbAdpt->select()
				->from(array('a'=>'tbl_semestermaster'),array('a.SemesterMainCode'))
				->where("a.IdSemesterMaster =?",$list['IdSemesterMain']);
				$Result = $this->lobjDbAdpt->fetchRow($Select);
				$semList[] = array('key' => $Result['SemesterMainCode'],'value' => $Result['SemesterMainCode']);
			}
		}
		return $semList;

	}


	public function fnSearchSemesterforCoursesOffered($post = array()) { //Function for searching the university details
		//$field7 = "a.Active = ".$post["field7"];
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('b' => 'tbl_semestermaster'),array("IdSemesterMaster"))
		->join(array('c' => 'tbl_scheme'),'c.IdScheme = b.Scheme',array("c.EnglishDescription as Schemename"));

		if (isset($post['field3']) &&  $post['field3']!='') {
			$select->where('LOWER(b.SemesterMainName) like "%" ? "%"',strtolower($post['field3']));
		}
		if (isset($post['field3']) && $post['field3']!='') {
			$select->where('b.SemesterMainDefaultLanguage like "%" ? "%"',strtolower($post['field3']));
		}
		if (isset($post['field2']) && $post['field2']!='') {
			$select->where('LOWER(b.SemesterMainCode) like "%" ? "%"',strtolower($post['field2']));
		}
		if (isset($post['field2']) && $post['field2']=='') {
			$post['field2'] = '0';
		}
		$select->where("b.IsCountable =?",$post['field2']);
		//$select->where("b.Scheme =?",$post['field8']);
		//->where($field7);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
        
        /*yatie*/
        
 		public function getSemesterData($IdSemester){
         
 		  $db = Zend_Db_Table::getDefaultAdapter();
 		  
          $select = $db->select()
                       ->from(array("s"=>$this->_name))
                       ->where("s.IdSemester = ?",$IdSemester);
                       
          $row = $db->fetchRow($select);
          
          return $row;
          
        }
        
        
        public function getListSemester(){
         
 		  $db = Zend_Db_Table::getDefaultAdapter();
 		  
          $select = $db->select()
                       ->from(array("sm"=>'tbl_semestermaster'),array('key'=>'IdSemesterMaster','value'=>'SemesterMainName'));
          $row = $db->fetchAll($select);          
          return $row;
          
        }
        
        
       
        
}
?>