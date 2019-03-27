<?php 
class GeneralSetup_Model_DbTable_Landscapesubject extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_landscapesubject';
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
	
    public function fnaddLandscapesubject($formData) { 
			if($formData['LandscapeType'] == 42 || $formData['LandscapeType'] == 44){
				$formData ['LandscapeCreditHoursgrid'] = 0;	
			}


    		 $count = count($formData['IdSubjectgrid']);
    		 for($i = 0;$i<$count;$i++) {

    			$data = array('IdProgram' =>$formData['IdProgram'],
    					  	  'IdLandscape' => $formData ['IdLandscape'],
    					      'IdSubject' => $formData ['IdSubjectgrid'][$i],
    						  'IdSemester' => $formData ['IdSemestergrid'][$i],
						  	  'SubjectType' =>  $formData ['LandscapeSubjectTypegrid'][$i],
    			              'Active' =>  $formData ['Active'],
    					  	  'UpdDate'  =>	$formData ['UpdDate'],
    					  	  'UpdUser'	=> 	$formData ['UpdUser']);
    			
    		if($formData['LandscapeType'] == 42 || $formData['LandscapeType'] == 44){
				$data ['CreditHours'] = 0;	
			}else {
    				$data['CreditHours'] = $formData ['LandscapeCreditHoursgrid'][$i];
    			}

			 $this->insert($data);
    		 }
			$lobjdb = Zend_Db_Table::getDefaultAdapter();
			return $lobjdb->lastInsertId();
	}
	
	
    public function fnaddLandscapesubjectLevel($formData,$resultLandscape) { 


    		 $count = count($formData['IdSubjectgrid']);
    		 for($i = 0;$i<$count;$i++) {
    			$data = array('IdProgram' =>$formData['IdProgram'],
    					  	  'IdLandscape' => $resultLandscape,
    			      		  'CreditHours' => $formData ['LandscapeCreditHoursgrid'][$i],
    					      'IdSubject' => $formData ['IdSubjectgrid'][$i],
    						  'IdSemester' => $formData ['IdSemestergrid'][$i],
    			              'Compulsory' => $formData ['Compulsory'][$i],
						  	  'SubjectType' =>  $formData ['LandscapeSubjectTypegrid'][$i],
    				          'IDProgramMajoring'=>$formData ['IdProgramMajoringgrid'][$i],
    			              'Active' =>  $formData ['Active'],
    					  	  'UpdDate'  =>	$formData ['UpdDate'],
    					  	  'UpdUser'	=> 	$formData ['UpdUser']);
    		

			 $this->insert($data);
    		 }
			$lobjdb = Zend_Db_Table::getDefaultAdapter();
			return $lobjdb->lastInsertId();
	}
}
?>