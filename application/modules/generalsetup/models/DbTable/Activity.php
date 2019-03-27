<?php
class GeneralSetup_Model_DbTable_Activity extends Zend_Db_Table { 	
  protected $_name = 'tbl_activity'; // table name
  private $lobjDbAdpt;
  protected $addDropId = '18';

  public function init(){
    $this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
  }	
  public function fnaddActivity($larrformData) {
    $this->insert($larrformData);
  }

  public function fngetActivityDetails() { //Function to get the Activity details
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()->from($this->_name);
    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    return $larrResult;
  }

  public function fnupdateActivity($formData,$lintIdActivity) { //Function for updating the Activity details
    unset ( $formData ['Save'] );
    $where = 'idActivity = '.$lintIdActivity;
    $this->update($formData,$where);
  }

  public function fnDeleteActivity($idActivity) {  // function to delete activity details
    $db = Zend_Db_Table::getDefaultAdapter();
    $table = 'tbl_activity';
    $where = $db->quoteInto('idActivity = ?', $idActivity);
    $db->delete('tbl_activity', $where);
  }

  public function fnSearchActivity($post = array()) { //Function for searching the Activity details
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()
                  ->from(array("a"=>"tbl_activity"),array("a.*"))
                  ->where('a.ActivityName like "%" ? "%"',$post['field3'])
                  ->order("a.ActivityName");
    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    return $larrResult;
  }


  public function fngetActivityList(){
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()->from(array('a' => $this->_name),array("key" => "a.idActivity" , "value" => "IFNULL(a.ActivityName,'')"));
    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    return $larrResult;
  }

  // Function to add calender entry for semester
  public function fnaddCalenderActivity($formData){
    
    $idSem = explode('_',$formData['IdSemester']);
    if($idSem['1']=='detail') { 
        $IdSemester = $idSem['0'];
        $IdSemesterMain = NULL;
    } else {
        $IdSemesterMain = $idSem['0'];
        $IdSemester = NULL;
    } 
    $activityMappingtable = new Zend_Db_Table('tbl_activity_calender');
    $data = array('IdSemester'=> $IdSemester,
                  'IdSemesterMain'=> $IdSemesterMain,
                  'IdActivity' => $formData['IdActivity'],
                  'StartDate' => $formData['StartDate'],
                  'EndDate' => $formData['EndDate']
            );
    
    if($activityMappingtable->insert($data)){
      return true;
    }
    return false;
  }

  // Function to get calender and semester mapping entries according to semester id
  public function getcalenderMappingDetail($semId){
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()->from(array('a' => 'tbl_activity_calender'))->where('a.IdSemester = ?',$semId);
    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    return $larrResult;
  }

  // Function to get the calender detatil according to id
  public function getcalenderMappingDetailById($Id){
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()->from(array('a' => 'tbl_activity_calender'))->where('a.id = ?',$Id);
    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    return $larrResult;
  }

  // Function to get calender and semester mapping entries according to semester id
  public function getActivityDetailById($Id){
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()->from(array('a' => 'tbl_activity'))->where('a.idActivity = ?',$Id);
    $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
    return $larrResult;
  }

  //Function to check semester mapping exist in activity semester mapping table
  public function checkcalenderexist($semId){
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()->from(array('a' => 'tbl_activity_calender'))->where('a.IdSemester = ?',$semId);
    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    if(!empty($larrResult)){
      return $larrResult;
    }
    return false;
  }
  
  
  //Function to check semester mapping exist in activity semester mapping table
  public function checkcalenderexistmain($semId){
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()->from(array('a' => 'tbl_activity_calender'))->where('a.IdSemesterMain = ?',$semId);
    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    if(!empty($larrResult)){
      return $larrResult;
    }
    return false;
  }
  

  public function getcalenderArray($calender,$activityDetail){
    $tem = array();
    $tem['activityid'] = $calender['IdActivity'];
    $tem['activityname'] = $activityDetail['ActivityName'];
    $tem['colorcode'] = $activityDetail['ActivityColorCode'];
    $tem['calenderId'] = $calender['id'];
    $tem['calenderstartdate'] = $calender['StartDate'];
    $tem['calenderenddate'] = $calender['EndDate'];
    return $tem;
  }

  public function fnDeletecalender($id) {  // function to delete activity details
    $db = Zend_Db_Table::getDefaultAdapter();
    $table = 'tbl_activity_calender';
    $where = $db->quoteInto('id = ?', $id);
    $db->delete('tbl_activity_calender', $where);
  }

  public function fnupdatecalender($formData) { //Function for updating the Activity details
    unset($formData['Save']);
    $calenderId = $formData['id'];
    unset($formData['id']);    
    $where = 'id = '.$calenderId;
    $db = Zend_Db_Table::getDefaultAdapter();
    $db->update('tbl_activity_calender' , $formData , $where);
  }


  public function getcalenderDetails($id){
    $db = Zend_Db_Table::getDefaultAdapter();
    $select = $db->select()
              ->from(array('a' => 'tbl_activity_calender'))
              ->joinLeft(array("b"=>"tbl_semester"),'a.IdSemester = b.IdSemester', array('b.SemesterCode as SemesterCodeDetail'))
              ->joinLeft(array("d"=>"tbl_semestermaster"),'a.IdSemesterMain = d.IdSemesterMaster', array('d.SemesterMainCode as SemesterCodeMain'))
              ->join(array("c"=>"tbl_activity"),'a.IdActivity = c.idActivity')
              ->where('a.id = '.$id);
    $result = $db->fetchAll($select);
    return $result;
  }
  
    /*
    * GET activity for add and drop subject
    */ 
    public function getaddDrop($id,$programId = 0) {
        $db = Zend_Db_Table::getDefaultAdapter();

        $select = $db->select();
        $select->from('tbl_activity_calender')
                ->where('IdSemesterMain = ?', (int)$id);
               // ->where('IdActivity = ?', (int)$this->addDropId);
        if($programId != 0)
        {
            $select->where('IdProgram = ?', (int)$programId);
        }
        else
        {
            $select->where('IdProgram IS NULL');
        }
        //echo $select;
        $result = $db->fetchAll($select);

        return $result;

    }
  
    public function fnainsertCalenderActivity($formData){
        
        $newCalender = new Zend_Db_Table('tbl_activity_calender');
        $data = array(
        'IdSemesterMain'=> $formData['IdSemesterMaster'],
        'IdActivity' => $formData['IdActivity'],
        'type' => 1,
        'StartDate' => $formData['StartDate'],
        'EndDate' => $formData['EndDate']
        );
        
        if(isset($formData['IdProgram']))
        {
            $data['IdProgram'] = $formData['IdProgram'];
        }
        
        $newCalender->insert($data);
    }
    
    public function fngetActivityActive(){
        $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
        $lstrSelect = $lobjDbAdpt->select()
            ->from(array('a' => $this->_name),array("key" => "a.idActivity" , "value" => "IFNULL(a.ActivityName,'')"))
            ->where('a.status = 1');
        $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
        return $larrResult;
    }
   
}