<?php
class GeneralSetup_SemestercalenderController extends Base_Base {
  private $lobjsemester;
  private $lobjactivity;
  private $lobjcalender;
  private $_gobjlog;
  private $lobjsemestercalenderForm;
  private $locale;
  private $registry; 

  public function init() {
    $this->fnsetObj();
    $this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
    $this->registry = Zend_Registry::getInstance();
    $this->locale = $this->registry->get('Zend_Locale');
  }
  public function fnsetObj(){
    $this->lobjsemester = new GeneralSetup_Model_DbTable_Semester();
    $this->lobjactivity = new GeneralSetup_Model_DbTable_Activity();
    $this->lobjcalender = new GeneralSetup_Model_DbTable_Calender();
    $this->lobjsemestercalenderForm = new GeneralSetup_Form_Semestercalender(); //intialize user lobjuniversityForm.

  }

  //Index Action for calender display
  public function indexAction() {
    $this->view->title="Calender Setup";    
    $this->view->activitylist = $this->lobjactivity->fngetActivityDetails();
    
    // Calendar list from semester table    
    $semesterlist = $this->lobjsemester->getAllsemesterList();     
    //asd($semesterlist);
    
    foreach ($semesterlist as $key => $row) {
        $mid[$key]  = $row['value'];
    }

    // Sort the data with mid ascending
    // Add $data as the last parameter, to sort by the common key
    array_multisort($mid, SORT_ASC, $semesterlist);    
    
    $i = 0;
    $calenderArray = array();
    foreach($semesterlist as $list){
       $semsterArray = array();
       //$semsterArray['semesterName'] = $list['SemesterCode'];
       //$semsterArray['semesterid'] = $list['IdSemester'];
       
       $idSem = explode('_',$list['key']);          
       if($idSem['1']=='detail') { 
           $semsterArray['semesterid'] = $idSem['0'];
           $semsterArray['semesterName'] = $list['value'];
           $result = $this->lobjactivity->checkcalenderexist($semsterArray['semesterid']);
       } else if ($idSem['1']=='main') {
           $semsterArray['semesterid'] = $idSem['0'];
           $semsterArray['semesterName'] = $list['value'];
           $result = $this->lobjactivity->checkcalenderexistmain($semsterArray['semesterid']);
       } 
       
       
       
       if($result){         
         foreach($result as $calender){            
            $temp = array();
            if(count($result)>1){
              $startmonth = intval(date("m",strtotime($calender['StartDate'])));
              $activityDetail = $this->lobjactivity->getActivityDetailById($calender['IdActivity']);
              if(isset($semsterArray['calender'])){
                if(array_key_exists($startmonth, $semsterArray['calender'])){
                  $result = $this->lobjactivity->getcalenderArray($calender,$activityDetail);
                  $semsterArray['calender'][$startmonth][] = $result;
                }else{
                  $result = $this->lobjactivity->getcalenderArray($calender,$activityDetail);
                  $semsterArray['calender'][$startmonth][] = $result;
                }
              }else{
                  $result = $this->lobjactivity->getcalenderArray($calender,$activityDetail);
                  $semsterArray['calender'][$startmonth][] = $result;
              }
            }else{
              $startmonth = intval(date("m",strtotime($calender['StartDate'])));              
              $activityDetail = $this->lobjactivity->getActivityDetailById($calender['IdActivity']);
              $tem = array();
              $result = $this->lobjactivity->getcalenderArray($calender,$activityDetail);
              $tem[$startmonth][] = $result;
              $semsterArray['calender'] = $tem;
            }
         }
       }
       $calenderArray[] =  $semsterArray;
    }
    
    // Calendar list from semester table
    
    
    $this->view->calenderList = $calenderArray;
  }

  // Function to create new calender
  public function addAction(){
    $this->view->title="Add New Calender";
    $this->view->lobjsemestercalenderForm = $this->lobjsemestercalenderForm;
    if($this->locale == 'ar_YE')  {
      $this->view->lobjsemestercalenderForm->StartDate->setAttrib('datePackage',"dojox.date.islamic");
      $this->view->lobjsemestercalenderForm->EndDate->setAttrib('datePackage',"dojox.date.islamic");
    }
    if ($this->_request->isPost()){
      $formData = $this->getRequest()->getPost();   
      //asd($formData);
      if (!$this->view->lobjsemestercalenderForm->isValid($this->getRequest()->getPost())) {
        $this->view->postdata = $formData;
        return $this->render("add");
      }
      //die;
      unset($formData['Save']);
      unset($formData['Back']);
      if($this->lobjactivity->fnaddCalenderActivity($formData)){
        $this->_redirect( $this->baseUrl . '/generalsetup/semestercalender/index');
      }
    }
  }

  public function editAction(){
    $this->view->title="Edit Calender";
    $this->view->lobjsemestercalenderForm = $this->lobjsemestercalenderForm;    
    $calenderId = $this->_getParam('id', 0);
    $ret = $this->lobjactivity->getcalenderDetails($calenderId);
    $temp['idcalender'] =  $ret[0]['id'];
    //$temp['IdSemester'] =  $ret[0]['IdSemester'];
    
    if($ret[0]['SemesterCodeDetail']!='' && $ret[0]['SemesterCodeMain']=='') { 
    $temp['SemesterCode'] =  $ret[0]['SemesterCodeDetail']; }
     if($ret[0]['SemesterCodeMain']!='' && $ret[0]['SemesterCodeDetail']=='') { 
    $temp['SemesterCode'] =  $ret[0]['SemesterCodeMain']; }
    
    $temp['idActivity'] =  $ret[0]['idActivity'];
    $temp['ActivityName'] =  $ret[0]['ActivityName'];
    $temp['StartDate'] =  $ret[0]['StartDate'];
    $temp['EndDate'] =  $ret[0]['EndDate'];

    $this->view->calenderDetail = $temp;
    /*$ret = $this->lobjcalender->fetchAll('id =' .$calenderId);
    $ret = $ret->toArray();    
    $this->lobjsemestercalenderForm->populate($ret);
    $this->view->lobjsemestercalenderForm->id->setValue($calenderId);
    $startDate = date('Y-m-d',strtotime($ret[0]['StartDate']));
    $this->view->lobjsemestercalenderForm->StartDate->setValue ($startDate);
    $endDate = date('Y-m-d',strtotime($ret[0]['EndDate']));
    $this->view->lobjsemestercalenderForm->EndDate->setValue($endDate);
    $this->view->lobjsemestercalenderForm = $this->lobjsemestercalenderForm;
    if ($this->_request->isPost()){      
      $larrformData = $this->_request->getPost ();
      $this->lobjactivity->fnupdatecalender($larrformData);
      $this->_redirect( $this->baseUrl . '/generalsetup/semestercalender/index');
    }*/


  }

  public function deleteAction(){
    $this->view->title="Delete Calender";
    $calenderId = $this->_getParam('id', 0);
    $this->lobjactivity->fnDeletecalender($calenderId);
    $this->_redirect( $this->baseUrl . '/generalsetup/semestercalender/index');
  }
}
