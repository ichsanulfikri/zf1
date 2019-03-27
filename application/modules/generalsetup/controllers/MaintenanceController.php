<?php
class GeneralSetup_MaintenanceController extends Zend_Controller_Action {
	private $gobjsessionsis; //class session global variable
	private $gintPageCount;
	
	
	public function init() {
		$this->gobjsessionsis = Zend_Registry::get('sis'); //initialize session variable
		$lobjinitialconfigModel = new GeneralSetup_Model_DbTable_Initialconfiguration(); //user model object
		$larrInitialSettings = $lobjinitialconfigModel->fnGetInitialConfigDetails($this->gobjsessionsis->idUniversity);
		$this->gintPageCount = isset($larrInitialSettings['noofrowsingrid'])?$larrInitialSettings['noofrowsingrid']:"5";
		
  	}
  	
  	//Index Action
	public function indexAction() {
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		//Intialize Search Form 
		$lobjSearchForm = new App_Form_Search();
		$this->view->lobjSearchForm = $lobjSearchForm;
		
		//Intialize Maintenance Form 
		$lobjMaintenanceForm = new GeneralSetup_Form_Maintenance();
		$this->view->lobjMaintenanceForm = $lobjMaintenanceForm;
		
		//Intialize Maintenance Model 
		$lobjMaintenanceModel= new GeneralSetup_Model_DbTable_Maintenance();
		
		//Get Maintenance Details
		$larrResult = $lobjMaintenanceModel->fnGetMaintenanceDetails();
		
		  if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->maintenancepaginatorresult);
   	    	
		
		$lintpagecount = $this->gintPageCount;		
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		
		if(isset($this->gobjsessionsis->maintenancepaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionsis->maintenancepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrResult,$lintpage,$lintpagecount);
		}
		
		//Maintenance Search 
		if ($this->_request->isPost() && $this->_request->getPost('Search')) {
			$larrFormData = $this->_request->getPost();
			if ($lobjSearchForm->isValid($larrFormData)) {
				
				//Get Maintenance Search Data
				$larrResult = $lobjMaintenanceModel->fnSearchMaintenace($larrFormData['field3'],$larrFormData['field4']);
				
				//Maintenance Details Search - Pagination
		    	$this->view->paginator = $lobjPaginator->fnPagination($larrResult,$lintpage,$lintpagecount);
		    	$this->gobjsessionsis->maintenancepaginatorresult = $larrResult;				
			}
		}
		//Clear
			if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'maintenance', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/maintenance/index');
		
		}
	}
	
	//Action To Save New Definition Type Ms
	public  function newdefinitionmsAction(){
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$lstrTable='tbl_definationtypems';
		
		//Initialize Maintenace Form
		$lobjMaintainanceForm = new GeneralSetup_Form_Maintenance();
		$this->view->lobjMaintenanceForm = $lobjMaintainanceForm;
		$this->view->lobjMaintenanceForm->defTypeDesc->setAttrib('validator','validateDefinition');
		
		$lobjMaintenanceModel= new GeneralSetup_Model_DbTable_Maintenance();		
		//Get Maintenance Details
		$larrResults = $lobjMaintenanceModel->fnGetMaintenanceDescriptionDetails();
	    $this->view->defTypeDescs=$larrResults;
		
		
		$lobjDBAdpt = Zend_Db_Table::getDefaultAdapter();
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();		
			$defTypeDesc   = $larrFormData["defTypeDesc"];
			$defTypeActive = $larrFormData["Active"];
			$BahasaIndonesia=$larrFormData["BahasaIndonesia"];
			$Description=$larrFormData["Description"];
			$lstrFormData  = array('defTypeDesc' => $defTypeDesc,'Active'=> $defTypeActive,'BahasaIndonesia'=>$BahasaIndonesia,'Description'=>$Description );
			$lstrMsg=$lobjDBAdpt->insert($lstrTable,$lstrFormData);
			
			
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'maintenance', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/maintenance/index');
		}
	}
	
	//Action To Save New Maintenance
	public function newmaintenanceAction() {
              $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		//Set Transaltion 
		$this->view->translate = $this->gstrtranslate;
		
		//Send roles to the view
		$this->view->roles = $this->gobjroles;
		$this->_helper->layout->disableLayout();
 		
		//Initialize Maintenace Form
		$lobjMaintainanceForm = new GeneralSetup_Form_Maintenance();
		$this->view->lobjMaintenanceForm = $lobjMaintainanceForm;
	
		//Initialize Maintenace Model
 		$lobjMaintenanceModel = new GeneralSetup_Model_DbTable_();
        
 		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();
			if ($lobjMaintainanceForm->isValid($larrFormData)) {				
			    $lstrResult = $MaintenanceModel->fnAddMaintenance($larrFormData);
				
			   
			    $lstrURL = $this->view->baseUrl().'/maintenance';
				echo "<script>parent.location = '".$lstrURL."';</script>";
			}
		}
	}
 
	//Action To Get Maintenance Details
	public function maintenancelistAction() {
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$lobjDB = Zend_Db_Table::getDefaultAdapter();
		
		//Get Maintenance Type Id
		$lintIdDefinition = (int)$this->_getParam('id');
		//echo $lintIdDefinition;die();
				
				
		//Get Maintenance Ms Id
		$lintIdDefType=(int)$this->_getParam('id');
		
		$this->view->IdDefType=$lintIdDefType;
		
		$lintedit = $this->_getParam('edit');
		$this->view->larredit=$lintedit;
		
		if($this->_getParam('rate'))
         {
         	$lintiddorm = $this->_getParam('rate');
         	if($lintiddorm==1)//new or edit
         	{
         		/*$lstrredirecturl = 'javascript:window.location.href="/ratemaster/newratemaster/redirect/true"';*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/ratemaster/newratemaster/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/ratemaster/newratemaster/redirect/true'";
         		}
         		$lstrredirectaction = "/rate/".$lintiddorm."/Module/".$this->_getParam('Module')."/id/";
         	}
        	elseif($lintiddorm==2) 
         	{
         		/*$lstrredirecturl = "javascript:window.location.href='/ratemaster/editratemaster/id/".$this->_getParam('idrate')."/redirect/true'";*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/ratemaster/editratemaster/id/".$this->_getParam('idrate')."/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/ratemaster/editratemaster/id/".$this->_getParam('idrate')."/redirect/true'";
         		}
         		$lstrredirectaction = "/rate/".$lintiddorm."/Module/".$this->_getParam('Module')."/idrate/".$this->_getParam('idrate');         
         	}
         }
         
		if($this->_getParam('item'))
         {
         	$lintiddorm = $this->_getParam('item');
         	if($lintiddorm==1)//new or edit
         	{
         		/*$lstrredirecturl = 'javascript:window.location.href="/itemmaster/newitemmaster/redirect/true"';*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemmaster/newitemmaster/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemmaster/newitemmaster/redirect/true'";
         		}
         		$lstrredirectaction = "/item/".$lintiddorm."/Module/".$this->_getParam('Module')."/id/";
         	}
        	elseif($lintiddorm==2) 
         	{
         		/*$lstrredirecturl = "javascript:window.location.href='/itemmaster/edititemmaster/id/".$this->_getParam('iditem')."/redirect/true'";*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemmaster/edititemmaster/id/".$this->_getParam('iditem')."/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemmaster/edititemmaster/id/".$this->_getParam('iditem')."/redirect/true'";
         		}
         		$lstrredirectaction = "/item/".$lintiddorm."/Module/".$this->_getParam('Module')."/iditem/".$this->_getParam('iditem');        
         	}
         }
         
         
		if($this->_getParam('itemgrpall'))
         {
         	$lintiddorm = $this->_getParam('itemgrpall');
         	if($lintiddorm==1)//new or edit
         	{
         		/*$lstrredirecturl = 'javascript:window.location.href="/itemgroupallotment/newitemgroupallotment/redirect/true"';*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemgroupallotment/newitemgroupallotment/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemgroupallotment/newitemgroupallotment/redirect/true'";
         		}
         		$lstrredirectaction = "/itemgrpall/".$lintiddorm."/Module/".$this->_getParam('Module')."/id/";
         	}
        	elseif($lintiddorm==2) 
         	{
         		/*$lstrredirecturl = "javascript:window.location.href='/itemgroupallotment/edititemgroupallotment/id/".$this->_getParam('iditemgrpall')."/redirect/true'";*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemgroupallotment/edititemgroupallotment/id/".$this->_getParam('iditemgrpall')."/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemgroupallotment/edititemgroupallotment/id/".$this->_getParam('iditemgrpall')."/redirect/true'";
         		}
         		$lstrredirectaction = "/itemgrpall/".$lintiddorm."/Module/".$this->_getParam('Module')."/iditemgrpall/".$this->_getParam('iditemgrpall');         
         	}
         }
         
		if($this->_getParam('itemgroup'))
         {
         	$lintiddorm = $this->_getParam('itemgroup');
         	if($lintiddorm==1)//new or edit
         	{
         		/*$lstrredirecturl = 'javascript:window.location.href="/itemgrouping/newitemgrouping/redirect/true"';*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemgrouping/newitemgrouping/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemgrouping/newitemgrouping/redirect/true'";
         		}
         		$lstrredirectaction = "/itemgroup".$lintiddorm."/Module/".$this->_getParam('Module')."/id/";
         	}
        	elseif($lintiddorm==2) 
         	{
         		/*$lstrredirecturl = "javascript:window.location.href='/itemgrouping/viewitemgrouping/iddefinition/".$this->_getParam('iddefinition')."/redirect/true'";*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemgrouping/viewitemgrouping/iddefinition/".$this->_getParam('iddefinition')."/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemgrouping/viewitemgrouping/iddefinition/".$this->_getParam('iddefinition')."/redirect/true'";
         		}
         		$lstrredirectaction = "/itemgroup/".$lintiddorm."/Module/".$this->_getParam('Module')."/iddefinition/".$this->_getParam('iddefinition');         
         	}
            elseif($lintiddorm==3) 
         	{
         		/*$lstrredirecturl = "javascript:window.location.href='/itemgrouping/edititemgrouping/id/".$this->_getParam('iditemGroup')."/groupid/".$this->_getParam('groupid')."/redirect/true'";*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemgrouping/edititemgrouping/id/".$this->_getParam('iditemGroup')."/groupid/".$this->_getParam('groupid')."/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemgrouping/edititemgrouping/id/".$this->_getParam('iditemGroup')."/groupid/".$this->_getParam('groupid')."/redirect/true'";
         		}
         		$lstrredirectaction = "/itemgroup/".$lintiddorm."/Module/".$this->_getParam('Module')."/iditemGroup/".$this->_getParam('iditemGroup')."/groupid/".$this->_getParam('groupid')."/Module/".$this->_getParam('Module');         
         	} 
         }

	
         
         //send the redirect url to view
		if(isset($lstrredirecturl))
			$this->view->lstrredirecturl = $lstrredirecturl; 
		if(isset($lstrredirectaction))
			$this->view->lstrredirectaction = $lstrredirectaction;
		
			
		//Initialize Maintenance Form
		$lobjMaintainanceForm = new GeneralSetup_Form_Maintenance();
		$this->view->lobjMaintenanceForm = $lobjMaintainanceForm;
		
		$this->view->lobjMaintenanceForm->idDefinition->setValue($lintIdDefinition);
		$this->view->lobjMaintenanceForm->DefinitionCode->setAttrib('validator','validateDefinitionCode');
		//$this->view->lobjMaintenanceForm->idDefType->setValue($lintIdDefType);
		
		//Initialize Maintenance Model
		$lobjMaintenanceModel = new GeneralSetup_Model_DbTable_Maintenance();
		
		//Get Maintenance Ms Details 
        $larrResult = $lobjMaintenanceModel->fnViewMaintenance($lintIdDefinition);
		
		//echo "<pre>";
		//print_r($larrResult );die();

		//Maintenance Ms - Pagination
		$lintPage = $this->_getParam('page',1);
		$lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); 
		$this->view->lobjPaginator = $lobjPaginator->fnPagination($larrResult,$lintPage,$lintpagecount);
		

	}
	
	
	public function ddmaintanancedetailsaveAction() {
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$lobjDB = Zend_Db_Table::getDefaultAdapter();
		
		//Initialize Maintenance Form
		$lobjMaintainanceForm = new GeneralSetup_Form_Maintenance();
		$this->view->lobjMaintenanceForm = $lobjMaintainanceForm;
		
		//Initialize Maintenance Model
		$lobjMaintenanceModel = new GeneralSetup_Model_DbTable_Maintenance();
		
		
		if($this->_getParam('rate'))
         {
         	$lintiddorm = $this->_getParam('rate');
         	if($lintiddorm==1)//new or edit
         	{
          		$lstrredirectaction = "/rate/$lintiddorm"."/Module/".$this->_getParam('Module');
         	}
        	elseif($lintiddorm==2) 
         	{
         		$lstrredirectaction = "/rate/$lintiddorm/idrate/".$this->_getParam('idrate')."/Module/".$this->_getParam('Module');         
         	}
         }
         
		if($this->_getParam('item'))
         {
         	$lintiddorm = $this->_getParam('item');
         	if($lintiddorm==1)//new or edit
         	{
         		$lstrredirectaction = "/item/$lintiddorm"."/Module/".$this->_getParam('Module');
         	}
        	elseif($lintiddorm==2) 
         	{
         		$lstrredirectaction = "/item/$lintiddorm/iditem/".$this->_getParam('iditem')."/Module/".$this->_getParam('Module');         
         	}
         }
         

         
			if($this->_getParam('itemgrpall'))
         {
         	$lintiddorm = $this->_getParam('itemgrpall');
         	if($lintiddorm==1)//new or edit
         	{
         		$lstrredirectaction = "/itemgrpall/$lintiddorm"."/Module/".$this->_getParam('Module');
         	}
        	elseif($lintiddorm==2) 
         	{
         		$lstrredirectaction = "/itemgrpall/$lintiddorm/iditemgrpall/".$this->_getParam('iditemgrpall')."/Module/".$this->_getParam('Module');         
         	}
         }

		if($this->_getParam('itemgroup'))
         {
         	$lintiddorm = $this->_getParam('itemgroup');
         	if($lintiddorm==1)//new or edit
         	{
         		$lstrredirectaction = "/itemgroup/$lintiddorm"."/Module/".$this->_getParam('Module');
         	}
        	elseif($lintiddorm==2) 
         	{
         		
				$lstrredirectaction = "/itemgroup/$lintiddorm/iddefinition/".$this->_getParam('iddefinition')."/Module/".$this->_getParam('Module'); 
         	}
            elseif($lintiddorm==3) 
         	{
         		$lstrredirectaction = "/itemgroup/$lintiddorm/iditemGroup/".$this->_getParam('iditemGroup')."/groupid/".$this->_getParam('groupid')."/Module/".$this->_getParam('Module');         
         	} 
         }
         
		if($this->getRequest()->getPost('Search')) {
			$lstrDefinitionDesc=$this->getRequest()->getPost('definitionDesc');
			$lstrDefinitionCode=$this->getRequest()->getPost('definitionCode');
			$lintIdRoute=$this->getRequest()->getPost('idDefType');

			if($lstrDefinitionCode!=NULL && $lstrDefinitionDesc!=NULL){
				$lstrsearch="DefinitionDesc like '".$lstrDefinitionDesc."%' and DefinitionCode like '".$lstrDefinitionCode."%' and idDefType=".$idRoute;
			}elseif($lstrDefinitionCode==NULL && $lstrDefinitionDesc!=NULL){
				$lstrsearch="DefinitionDesc like '".$lstrDefinitionDesc."%' and idDefType=".$lintIdRoute;
			}elseif($lstrDefinitionCode!=NULL && $lstrDefinitionDesc==NULL){
				$lstrsearch="DefinitionDesc like '".$lstrDefinitionDesc."%' and idDefType=".$lintIdRoute;
			}else{
				$lstrsearch="idDefType=".$lintIdRoute;
			}
			$lstrSelectSQL = $lobjDB->select()->from('tbl_definationms')->where($lstrsearch)->order('DefinitionDesc');
			$larrResultData=$lobjDB->select($lstrSelectSQL);

			$this->view->routeId=$lintIdRoute;    
			$this->view->data=$larrResultData;
			$this->_redirect('/maintenance/maintenacelist/id/'.$lintIdRoute);
		}else if($this->_request->isPost() && $this->getRequest()->getPost('Save')) {
			//Save Maintenance Ms Details
			$lobjFormData = $this->_request->getPost();
			
			//$defDesc=$this->getRequest()->getPost();
			unset($lobjFormData['Save']);
			
			$lintIdDefinition=$this->_getParam('idDefinition');
			$lstrDefinitionCode=$this->_getParam('DefinitionCode');
			$lstrDefinitionDesc=$this->_getParam('DefinitionDesc');
			$lstrDefinitionStat =$this->_getParam('Status');
			$lstrDefinitionBahasaIndonesia =$this->_getParam('BahasaIndonesia');
			$lstrDefinitionDescription =$this->_getParam('Description');
			
			$lobjResultData = $lobjMaintenanceModel->fnCheckMaintenanceDetails($lstrDefinitionDesc,$lintIdDefinition);
			/*$sql = $lobjDB->select()->from('tbl_definationms','count(*) as num')->where("DefinitionDesc=?",$lstrDefinitionDesc);
			$data=$lobjDB->select($sql);*/
			
			//$rowCount=$data->num;
			$lstrRowCount=$lobjResultData['num'];
			if($lstrRowCount==0 || $lstrRowCount== "") {
				//$lobjFormData = $this->_request->getPost();
				$lstrTable='tbl_definationms';
				$larrMaintenanceInsertData = array(
			 			'idDefType' => $lintIdDefinition,
						'DefinitionCode' => $lstrDefinitionCode,
						'DefinitionDesc' => $lstrDefinitionDesc,
						'Status' => $lstrDefinitionStat,
						'BahasaIndonesia' =>$lstrDefinitionBahasaIndonesia,
						'Description' => $lstrDefinitionDescription
					);
					
				
				$lobjDB->insert($lstrTable,$larrMaintenanceInsertData);
				$LastInsertID=$lobjDB->lastInsertId();
				
				$gobj_model_deftypems=new App_Model_Definitiontype();
				$DefTypeDesc=$gobj_model_deftypems->fnGetDefinationTypeString($lintIdDefinition);
				
				
/*				if($DefTypeDesc['defTypeDesc']=="Room Group Type")
				{				
				$this->_redirect('/sam/ratemaster/newratemaster/DefRate/true/RoomGroup/'.$LastInsertID);				
				}*/
				
			} else {
				$lstrMsg="Description Name is existing";
			}

			$this->_redirect('/generalsetup/maintenance/maintenancelist/id/'.$lintIdDefinition.$lstrredirectaction);
			/*$lstrURL = $this->view->baseUrl().'/maintenance/maintenancelist/id/'.$lintIdDefinition.$lstrredirectaction;
			echo "<script>parent.location = '".$lstrURL."';</script>";*/
    	}
	}
	
	
	public function maintenancedetailAction() {
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$lobjDB = Zend_Db_Table::getDefaultAdapter();
		
			//Get Maintenance Ms Id
		$lstrIdDefinition = (int)$this->_getParam('idDefn');
		
		//Get Maintenance Type Ms Id
		$lstrIdDefinitionType = (int)$this->_getParam('idDefnType');
		
		
		if($this->_getParam('rate'))
         {
         	$lintiddorm = $this->_getParam('rate');
         	if($lintiddorm==1)//new or edit
         	{
         		/*$lstrredirecturl = 'javascript:window.location.href="/ratemaster/newratemaster/redirect/true"';*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."'/ratemaster/newratemaster/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."'/ratemaster/newratemaster/redirect/true'";
         		}
         		$lstrredirectaction = "/rate/".$lintiddorm."/Module/".$this->_getParam('Module')."/id/";
         	}
        	elseif($lintiddorm==2) 
         	{
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/ratemaster/editratemaster/id/".$this->_getParam('idrate')."/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/ratemaster/editratemaster/id/".$this->_getParam('idrate')."/redirect/true'";
         		}
         		$lstrredirectaction = "/rate/".$lintiddorm."/Module/".$this->_getParam('Module')."/idrate/".$this->_getParam('idrate');         
         	}
         }
         
		if($this->_getParam('item'))
         {
         	$lintiddorm = $this->_getParam('item');
         	if($lintiddorm==1)//new or edit
         	{
         		/*$lstrredirecturl = 'javascript:window.location.href="/itemmaster/newitemmaster/redirect/true"';*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."'/itemmaster/newitemmaster/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."'/itemmaster/newitemmaster/redirect/true'";
         		}
         		$lstrredirectaction = "/item/".$lintiddorm."/Module/".$this->_getParam('Module')."/id/";
         	}
        	elseif($lintiddorm==2) 
         	{
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemmaster/edititemmaster/id/".$this->_getParam('iditem')."/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemmaster/edititemmaster/id/".$this->_getParam('iditem')."/redirect/true'";
         		}
         		$lstrredirectaction = "/item/"."/Module/".$this->_getParam('Module')."/".$lintiddorm."/iditem/".$this->_getParam('iditem');         
         	}
         }
         
         
		if($this->_getParam('itemgrpall'))
         {
         	$lintiddorm = $this->_getParam('itemgrpall');
         	if($lintiddorm==1)//new or edit
         	{
         		/*$lstrredirecturl = 'javascript:window.location.href="/itemgroupallotment/newitemgroupallotment/redirect/true"';*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."'/itemgroupallotment/newitemgroupallotment/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."'/itemgroupallotment/newitemgroupallotment/redirect/true'";
         		}
         		$lstrredirectaction = "/itemgrpall/".$lintiddorm."/Module/".$this->_getParam('Module')."/id/";
         	}
        	elseif($lintiddorm==2) 
         	{
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemgroupallotment/edititemgroupallotment/id/".$this->_getParam('iditemgrpall')."/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemgroupallotment/edititemgroupallotment/id/".$this->_getParam('iditemgrpall')."/redirect/true'";
         		}
         		$lstrredirectaction = "/itemgrpall/".$lintiddorm."/Module/".$this->_getParam('Module')."/iditemgrpall/".$this->_getParam('iditemgrpall');         
         	}
         }
         
		if($this->_getParam('itemgroup'))
         {
         	$lintiddorm = $this->_getParam('itemgroup');
         	if($lintiddorm==1)//new or edit
         	{
         		/*$lstrredirecturl = 'javascript:window.location.href="/itemgrouping/newitemgrouping/redirect/true"';*/
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."'/itemgrouping/newitemgrouping/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."'/itemgrouping/newitemgrouping/redirect/true'";
         		}
         		$lstrredirectaction = "/itemgroup/".$lintiddorm."/Module/".$this->_getParam('Module')."/id/";
         	}
        	elseif($lintiddorm==2) 
         	{
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemgrouping/viewitemgrouping/iddefinition/".$this->_getParam('iddefinition')."/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemgrouping/viewitemgrouping/iddefinition/".$this->_getParam('iddefinition')."/redirect/true'";
         		}
         		$lstrredirectaction = "/itemgroup/".$lintiddorm."/Module/".$this->_getParam('Module')."/iddefinition/".$this->_getParam('iddefinition');         
         	}
            elseif($lintiddorm==3) 
         	{
         		if($this->_getParam('Module') && $this->_getParam('Module')!=""){
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/".$this->_getParam('Module')."/itemgrouping/edititemgrouping/id/".$this->_getParam('iditemGroup')."/groupid/".$this->_getParam('groupid')."/redirect/true'";
         		}else{
         			$lstrredirecturl = "window.location.href='".$this->view->baseUrl()."/itemgrouping/edititemgrouping/id/".$this->_getParam('iditemGroup')."/groupid/".$this->_getParam('groupid')."/redirect/true'";
         		}
         		$lstrredirectaction = "/itemgroup/".$lintiddorm."/Module/".$this->_getParam('Module')."/iditemGroup/".$this->_getParam('iditemGroup')."/groupid/".$this->_getParam('groupid');         
         	} 
         }

	
         
         //send the redirect url to view
		if(isset($lstrredirecturl))
			$this->view->lstrredirecturl = $lstrredirecturl; 
		if(isset($lstrredirectaction))
			$this->view->lstrredirectaction = $lstrredirectaction;
         
		
		//Initialize Maintenance Form
		$lobjMaintainanceForm = new GeneralSetup_Form_Maintenance();
		$this->view->lobjMaintenanceForm = $lobjMaintainanceForm;
		
		//Initialize Maintenance Model
		$lobjMaintenanceModel = new GeneralSetup_Model_DbTable_Maintenance();
		
		
		
		$lstrSelectSQL = $lobjDB->select()
								->from('tbl_definationms',array('idDefinition','idDefType','DefinitionCode','DefinitionDesc','BahasaIndonesia','Description'))
								->where("Status='1' and idDefType=?",$lstrIdDefinitionType)
								->order('definitionDesc'); 
		$lobjPagnresult = $lobjDB->fetchAll($lstrSelectSQL);
	
		/*===========Pagination ====================*/
		$lintPage = $this->_getParam('page',1);
		$lobjPaginator = Zend_Paginator::factory($lobjPagnresult);
		$lobjPaginator->setItemCountPerPage(5);
		$lobjPaginator->setCurrentPageNumber($lintPage);
		$this->view->lobjPaginator = $lobjPaginator;
		/*=======================================*/

		//Get Maintenance Ms Details
		$lobjMaintenanceMsResult = $lobjMaintenanceModel->fnViewMaintenanceMs($lstrIdDefinition,$lstrIdDefinitionType);
		
		//Assign Values To Respective Fields
		$this->view->lobjMaintenanceForm->idDefType->setValue($lstrIdDefinitionType);
		$this->view->lobjMaintenanceForm->idDefinition->setValue($lstrIdDefinition);
		$this->view->lobjMaintenanceForm->DefinitionCode->setValue($lobjMaintenanceMsResult['DefinitionCode']);
		$this->view->lobjMaintenanceForm->DefinitionDesc->setValue($lobjMaintenanceMsResult['DefinitionDesc']);
		$this->view->lobjMaintenanceForm->BahasaIndonesia->setValue($lobjMaintenanceMsResult['BahasaIndonesia']);
		$this->view->lobjMaintenanceForm->Description->setValue($lobjMaintenanceMsResult['Description']);
		$this->view->lobjMaintenanceForm->Status->setValue($lobjMaintenanceMsResult['Status']);
		
		
		if($this->getRequest()->getPost('Search')) {
			$lstrDefinitionDesc=$this->getRequest()->getPost('definitionDesc');
			$lstrDefinitionCode=$this->getRequest()->getPost('definitionCode');
			$lintIdRoute=$this->getRequest()->getPost('idDefType');

			if($lstrDefinitionCode!=NULL && $lstrDefinitionDesc!=NULL){
				$lstrsearch="DefinitionDesc like '".$lstrDefinitionDesc."%' and DefinitionCode like '".$lstrDefinitionCode."%' and idDefType=".$idRoute;
			}elseif($lstrDefinitionCode==NULL && $lstrDefinitionDesc!=NULL){
				$lstrsearch="DefinitionDesc like '".$lstrDefinitionDesc."%' and idDefType=".$lintIdRoute;
			}elseif($lstrDefinitionCode!=NULL && $lstrDefinitionDesc==NULL){
				$lstrsearch="DefinitionDesc like '".$lstrDefinitionDesc."%' and idDefType=".$lintIdRoute;
			}else{
				$lstrsearch="idDefType=".$lintIdRoute;
			}
			$lstrSelectSQL = $lobjDB->select()->from('tbl_definationms')->where($lstrsearch)->order('DefinitionDesc');
			$larrResultData=$lobjDB->select($lstrSelectSQL);

			$this->view->routeId=$lintIdRoute;    
			$this->view->data=$larrResultData;
			//$this->_redirect('/maintenance/maintenacelist/id/'.$lintIdRoute);
			$lstrURL = $this->view->baseUrl().'/maintenance/maintenacelist/id/'.$lintIdRoute;
			echo "<script>parent.location = '".$lstrURL."';</script>";
			
		}else if($this->getRequest()->getPost('Save')) {
			//Save Maintenance Ms Details
			$lobjFormData = $this->_request->getPost();
			
			//$defDesc=$this->getRequest()->getPost();
			unset($lobjFormData['Save']);
			
			$lintIdDefn=$this->_getParam('idDefinition');
			$lstrDefinitionCode=$this->_getParam('DefinitionCode');
			$lstrDefinitionDesc=$this->_getParam('DefinitionDesc');
			$lstrDefinitionStat=$this->_getParam('Status');
			$lstrDefinitionBahasaIndonesia=$this->_getParam('BahasaIndonesia');
			$lstrDefinitionDescription=$this->_getParam('Description');
			
			$lobjResultData = $lobjMaintenanceModel->fnCheckMaintenanceDetails($lstrDefinitionDesc,$lintIdDefn);
			/*$sql = $lobjDB->select()->from('tbl_definationms','count(*) as num')->where("DefinitionDesc=?",$lstrDefinitionDesc);
			$data=$lobjDB->select($sql);*/
			
			$lstrRowCount=$lobjResultData['num'];
			echo $lobjResultData['num'];
			if($lstrRowCount==0 || $lstrRowCount== "") {
				if($lstrDefinitionCode!="" && $lstrDefinitionDesc!="") {
					$lstrTable='tbl_definationms';
					$larrMaintenanceInsertData = array(
					 			'idDefType' => $lintIdDefn,
								'DefinitionDesc' => $lstrDefinitionDesc,
								'DefinitionCode' => $lstrDefinitionCode,
								'Status' => $lstrDefinitionStat,
								'BahasaIndonesia' =>$lstrDefinitionBahasaIndonesia,
								'Description' => $lstrDefinitionDescription
							);
					$lstrMsg=$lobjDB->insert($lstrTable,$larrMaintenanceInsertData);
					
				}
			} else {
				$lstrMsg="Description Name is existing";
			}
			
			$this->_redirect('/maintenance/maintenancelist/id/'.$lintIdDefn.$lstrredirectaction);
			/*$lstrURL = $this->view->baseUrl().'/maintenance/maintenancelist/id/'.$lintIdDefn.$lstrredirectaction;
			echo "<script>parent.location = '".$lstrURL."';</script>";*/
    	}
	}

	/*
	public function deletemaintenanceAction(){
		
		$this->_helper->layout->disableLayout();
		$idDefinition = (int)$this->_getParam('idDefinition');
		$idDefinitionType = (int)$this->_getParam('idDefType');
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$strUpdt=array('Status' => '0');
		$table='tbl_definationms';
		$where="idDefinition = '".$idDefinition . " ' ";
		
		$msg=$db->update($table,$strUpdt,$where);
		 
		$this->_redirect('/maintenance/maintenancelist/id/'.$idDefinitionType);
	}
	*/
	
	//Action To Update Maintenance Ms Details
	public function updatemaintenanceAction(){
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		//Initialize Maintenance Model
		$lobjMaintenanceModel = new GeneralSetup_Model_DbTable_Maintenance();
		
			if($this->_getParam('dorm'))
         {
         	$lintiddorm = $this->_getParam('dorm');
         	if($lintiddorm==1)//new or edit
         	{
         		$lstrredirectaction = "/dorm/".$lintiddorm."/From/Room/"."Module/".$this->_getParam('Module');
         	}
        	elseif($lintiddorm==2) 
         	{
         		$lstrredirectaction = "/dorm/".$lintiddorm."/From/".$this->_getParam('From')."/Module/".$this->_getParam('Module')."/HostelId/".$this->_getParam('HostelId')."/BlockId/".$this->_getParam('BlockId')."/FloorId/".$this->_getParam('FloorId')."/ApartmentId/".$this->_getParam("ApartmentId")."/id/".$this->_getParam('FloorId');
         	}
            elseif($lintiddorm==3) 
         	{
         		$lstrredirectaction = "/dorm/".$lintiddorm."/From/".$this->_getParam('From')."/Module/".$this->_getParam('Module')."/HostelId/".$this->_getParam('HostelId')."/BlockId/".$this->_getParam('BlockId')."/FloorId/".$this->_getParam('FloorId')."/RoomId/".$this->_getParam('RoomId')."/ApartmentId/".$this->_getParam("ApartmentId")."/id/".$this->_getParam('RoomId');
         	}
         }

		if($this->_getParam('bed'))
         {
         	$lintiddorm = $this->_getParam('bed');
         	if($lintiddorm==1)//new or edit
         	{
         		$lstrredirectaction = "/bed/$lintiddorm/From/Bed"."/Module/".$this->_getParam('Module');
         	}
        	elseif($lintiddorm==2) 
         	{
         		$lstrredirectaction = "/bed/".$lintiddorm."/From/".$this->_getParam('From')."/Module/".$this->_getParam('Module')."/HostelId/".$this->_getParam('HostelId')."/BlockId/".$this->_getParam('BlockId')."/FloorId/".$this->_getParam('FloorId')."/RoomId/".$this->_getParam('RoomId')."/ApartmentId/".$this->_getParam("ApartmentId")."/iddarmitory/".$this->_getParam('RoomId');         
         	}
            elseif($lintiddorm==3) 
         	{
         		$lstrredirectaction = "/bed/".$lintiddorm."/From/".$this->_getParam('From')."/Module/".$this->_getParam('Module')."/HostelId/".$this->_getParam('HostelId')."/BlockId/".$this->_getParam('BlockId')."/FloorId/".$this->_getParam('FloorId')."/RoomId/".$this->_getParam('RoomId')."/ApartmentId/".$this->_getParam("ApartmentId")."/BeedId/".$this->_getParam('BedId')."/iddarmitory/".$this->_getParam('RoomId')."/idbed/".$this->_getParam('idbed');         
         	} 
         }
         
		if($this->_getParam('rate'))
         {
         	$lintiddorm = $this->_getParam('rate');
         	if($lintiddorm==1)//new or edit
         	{
         		$lstrredirectaction = "/rate/$lintiddorm"."/Module/".$this->_getParam('Module');
         	}
        	elseif($lintiddorm==2) 
         	{
         		$lstrredirectaction = "/rate/$lintiddorm/idrate/".$this->_getParam('idrate')."/Module/".$this->_getParam('Module');         
         	}
         }
         
		if($this->_getParam('item'))
         {
         	$lintiddorm = $this->_getParam('item');
         	if($lintiddorm==1)//new or edit
         	{
         		$lstrredirectaction = "/item/$lintiddorm"."/Module/".$this->_getParam('Module');
         	}
        	elseif($lintiddorm==2) 
         	{
         		$lstrredirectaction = "/item/$lintiddorm/iditem/".$this->_getParam('iditem')."/Module/".$this->_getParam('Module');         
         	}
         }
         

         
			if($this->_getParam('itemgrpall'))
         {
         	$lintiddorm = $this->_getParam('itemgrpall');
         	if($lintiddorm==1)//new or edit
         	{
         		$lstrredirectaction = "/itemgrpall/$lintiddorm"."/Module/".$this->_getParam('Module');
         	}
        	elseif($lintiddorm==2) 
         	{
         		$lstrredirectaction = "/itemgrpall/$lintiddorm/iditemgrpall/".$this->_getParam('iditemgrpall')."/Module/".$this->_getParam('Module');         
         	}
         }

		if($this->_getParam('itemgroup'))
         {
         	$lintiddorm = $this->_getParam('itemgroup');
         	if($lintiddorm==1)//new or edit
         	{
         		$lstrredirectaction = "/itemgroup/$lintiddorm"."/Module/".$this->_getParam('Module');
         	}
        	elseif($lintiddorm==2) 
         	{
         		
				$lstrredirectaction = "/itemgroup/$lintiddorm/iddefinition/".$this->_getParam('iddefinition')."/Module/".$this->_getParam('Module'); 
         	}
            elseif($lintiddorm==3) 
         	{
         		$lstrredirectaction = "/itemgroup/$lintiddorm/iditemGroup/".$this->_getParam('iditemGroup')."/groupid/".$this->_getParam('groupid')."/Module/".$this->_getParam('Module');         
         	} 
         }
		//Get Maintenance Ms Id
		$lintIdDefinition = (int)$this->_getParam('idDefinition');
		
		//Get Maintenance Type Ms Id
		$idDefinitionType = (int)$this->_getParam('idDefType');
		
		$lstrDefinitionCode = $this->_getParam('DefinitionCode');
		$lstrDefinitionDesc = $this->_getParam('DefinitionDesc');
		$lstrDefinitionActi = $this->_getParam('Status');
		$lstrDefinitionBahasaIndonesias = $this->_getParam('BahasaIndonesia');
		$lstrDefinitionDescription = $this->_getParam('Description');
		
		
		$larrUpdateData=array(
							'DefinitionCode' => $lstrDefinitionCode,
							'DefinitionDesc' => $lstrDefinitionDesc,
							'Status' 		 => $lstrDefinitionActi,
							'BahasaIndonesia' => $lstrDefinitionBahasaIndonesias,
							'Description' => $lstrDefinitionDescription
						);
		
		
		$lstrMsg=$lobjMaintenanceModel->fnUpdateMaintenanceMs($larrUpdateData,$lintIdDefinition);
		
				
		$this->_redirect('/generalsetup/maintenance/maintenancelist/id/'.$idDefinitionType.$lstrredirectaction);
		/*$lstrURL = $this->view->baseUrl().'/maintenance/maintenancelist/id/'.$idDefinitionType.$lstrredirectaction;
		echo "<script>parent.location = '".$lstrURL."';</script>";*/
		
	}
	
	
	public function getdefnitiontypeAction(){		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();				
		$Deftype = $this->_getParam('Deftype');	
		$lobjMaintenanceModel = new GeneralSetup_Model_DbTable_Maintenance();			
		$larrDetails = $lobjMaintenanceModel->fngetDeftype($Deftype);		
		echo $larrDetails['defTypeDesc'];				
	}
	
	public function getdefnitioncodeAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();				
		$DefCode = $this->_getParam('DefCode');	
		$Iddef = $this->_getParam('Iddef');	
		
		$lobjMaintenanceModel = new GeneralSetup_Model_DbTable_Maintenance();			
		$larrDetails = $lobjMaintenanceModel->fngetDefCode($DefCode,$Iddef);		
		echo $larrDetails['DefinitionCode'];		
		
	}
 
}