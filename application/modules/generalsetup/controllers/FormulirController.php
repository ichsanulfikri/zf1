<?php
class GeneralSetup_FormulirController extends Base_Base { //Controller for the User Module
    
	private $locale;
	private $registry;
	private $lobjdeftype;
	private $lobformulir;
	private $_gobjlog;

	
	public function init() { //initialization function
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		Zend_Form::setDefaultTranslator($this->view->translate);
		$this->fnsetObj();
		
	}

	public function fnsetObj() {
		$this->lobformulir = new GeneralSetup_Form_Formulir(); //formulir model object
		$this->registry = Zend_Registry::getInstance();
		$this->view->locale = $this->locale = $this->registry->get('Zend_Locale');
		$this->lobjdeftype = new App_Model_Definitiontype();
	}
	

	public function indexAction() { // action for search and view
		$lobjFormulirModel=New GeneralSetup_Model_DbTable_Formulir();
		$this->view->lobjform = $this->lobformulir; //send the lobformulir object to the view
		
		$applicationtype = $this->lobjdeftype->fnGetDefinations('Application Type');
		foreach($applicationtype as $applicationtype) {
			$this->view->lobjform->Apptype->addMultiOption($applicationtype['idDefinition'],$applicationtype['DefinitionDesc']);
		}
		
		 $semester = new GeneralSetup_Model_DbTable_Semester();
		 $semarray = $semester->fngetlandscapeSemesterDetails();
		foreach($semarray as $semdata) {
			$this->view->lobjform->Year->addMultiOption($semdata['SemesterMainName'],$semdata['SemesterMainName']);
		}
		
		
		$result=$lobjFormulirModel->fnGetFormulirDetails();
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		$this->view->paginator =  $this->lobjCommon->fnPagination($result,$lintpage,$lintpagecount);
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'submit' )) {
			$larrformData = $this->_request->getPost ();
			$maxid = $lobjFormulirModel->fnGetmaxid();
			$year=explode("-",$larrformData['Year']);
			$yy=substr($year[1],2,5);
			$ApplicationType=substr($larrformData['Apptype'],0,1);
			
			$length = 5;			
			for($i=0;$i<$larrformData['EnterNo'];$i++){
				@$num = $maxid['maxid']+$i;
				$FamulirNo =  $yy.$ApplicationType.str_pad($num, 5, "0", STR_PAD_LEFT);
				$password = $this->fnCreateRandPassword($length);
				$insertintoformulier=$lobjFormulirModel->fnInsertintoformulier($larrformData,$FamulirNo,$password);

			}

			$this->_redirect( $this->baseUrl . '/generalsetup/formulir');

		}
	}
	
    public function fnCreateRandPassword($length) 
    {
		$chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$i = 0;
		$password = "";
		while ($i <= $length) 
		{
			@$password .= $chars{mt_rand(0,strlen($chars))};
			$i++;
		}
		return $password;
    
	}
	

function array_to_scv($array, $header_row = true, $col_sep = ",", $row_sep = "\n", $qut = '"')
{
	if (!is_array($array) or !is_array($array[0])) return false;
	
	//Header row.
	if ($header_row)
	{
		foreach ($array[0] as $key => $val)
		{
			//Escaping quotes.
			$key = str_replace($qut, "$qut$qut", $key);
			$output .= "$col_sep$qut$key$qut";
		}
		$output = substr($output, 1)."\n";
	}
	//Data rows.
	foreach ($array as $key => $val)
	{
		$tmp = '';
		foreach ($val as $cell_key => $cell_val)
		{
			//Escaping quotes.
			$cell_val = str_replace($qut, "$qut$qut", $cell_val);
			$tmp .= "$col_sep$qut$cell_val$qut";
		}
		@$output .= substr($tmp, 1).$row_sep;
	}
	
	return $output;
}

	public function exportcsvAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$lobjFormulirModel=New GeneralSetup_Model_DbTable_Formulir();
		$result=$lobjFormulirModel->fnGetFormulirDetails();
		$csv_data = $this->array_to_scv($result, false);
		$ourFileName = realpath('.')."/data";
		$ourFileHandle = fopen($ourFileName, 'w')or die("can't open file"); 
		ini_set('max_execution_time', 3600);
		fwrite($ourFileHandle,$csv_data);
		fclose($ourFileHandle);
		header("Content-type: application/csv,charset=UTF-8");
		
		header("Content-Type: application/vnd.ms-excel,charset=UTF-8");
			header("Content-Disposition: attachment; filename=formulir.csv");
			header("Pragma: no-cache");
			header("Expires: 0");
			readfile($ourFileName);
			unlink($ourFileName);
	}

}