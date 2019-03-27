<?php
class GeneralSetup_Form_City extends Zend_Form
{
	
public function init()
    {    
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    
         $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
         $UpdDate->removeDecorator("DtDdWrapper");
         $UpdDate->removeDecorator("Label");
         $UpdDate->removeDecorator('HtmlTag');
     
         $UpdUser = new Zend_Form_Element_Hidden('UpdUser');
         $UpdUser->removeDecorator("DtDdWrapper");
         $UpdUser->removeDecorator("Label");
         $UpdUser->removeDecorator('HtmlTag');
        
         $idState = new Zend_Form_Element_Hidden('idCity');
         $idState->removeDecorator("DtDdWrapper");
         $idState->removeDecorator("Label");
         $idState->removeDecorator('HtmlTag');
                   
         $idCountry = new Zend_Form_Element_Text('idState');
		 $idCountry  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")       
			        ->setAttrib('class','txt_put')
			        ->setAttrib('style','width:155px;')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');
                  
        $StateName = new Zend_Form_Element_Text('CityName',array('regExp'=>"^[a-zA-Z0-9]+$",'invalidMessage'=>""));
		$StateName  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")  
					-> setAttrib('required',"true")                               
			        ->setAttrib('class','txt_put')
					->setAttrib('maxlength','80')
					->setAttrib('propercase','true')   
					->setAttrib('style','width:155px;')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');
					
		$CityCode = new Zend_Form_Element_Text('CityCode',array('regExp'=>"^[a-zA-Z0-9]+$",'invalidMessage'=>""));
		$CityCode  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")  
					-> setAttrib('required',"true")                               
			        ->setAttrib('class','txt_put')
					->setAttrib('maxlength','80')
					->setAttrib('propercase','true')   
					->setAttrib('style','width:155px;')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');
                  
                         
			$Active = new Zend_Form_Element_Checkbox('Active');
			$Active->  setAttrib('dojoType',"dijit.form.CheckBox") 
			        -> setAttrib('required',"true")
					->setChecked(true)
					->removeDecorator("DtDdWrapper")
					->removeDecorator("Label") 					
					->removeDecorator('HtmlTag');

					
	         
         $Save = new Zend_Form_Element_Submit('Save');
         $Save->dojotype="dijit.form.Button";
         $Save->label = $gstrtranslate->_("Save");
         $Save->setAttrib('class', 'NormalBtn');
         $Save->setAttrib('id', 'submitbutton');
         $Save->removeDecorator("DtDdWrapper");
         $Save->removeDecorator("Label");
         $Save->removeDecorator('HtmlTag');
        
         $this->addElements(array($UpdDate,$UpdUser,$idState,$CityCode,
         						 $idCountry,$StateName,$Active,$Save));
    }
}
?>