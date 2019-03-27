<?php
class GeneralSetup_Form_Coursemaster extends Zend_Dojo_Form { //Formclass for the Programmaster	 module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
		$IdCoursemaster = new Zend_Form_Element_Hidden('IdCoursemaster');
        $IdCoursemaster->removeDecorator("DtDdWrapper");
        $IdCoursemaster->removeDecorator("Label");
        $IdCoursemaster->removeDecorator('HtmlTag');
        
        $CourseName = new Zend_Form_Element_Text('CourseName');	
		$CourseName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $CourseName->setAttrib('required',"true")  
                ->setAttrib('propercase','true')         			 
        		->setAttrib('maxlength','100')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$ArabicName = new Zend_Form_Element_Text('ArabicName');
		$ArabicName->setAttrib('dojoType',"dijit.form.ValidationTextBox")    			 
        				->setAttrib('maxlength','20')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        		
      	$Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');
        
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');

        $Save = new Zend_Form_Element_Submit('Save');
        $Save->label = $gstrtranslate->_("Save");
        $Save->dojotype="dijit.form.Button";
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
    		
         		
        $Back = new Zend_Form_Element_Button('Back');
        $Back->label = $gstrtranslate->_("Back");
        $Back->dojotype="dijit.form.Button";
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

        //form elements
        $this->addElements(array($IdCoursemaster,
        						 $CourseName,
        						 $ArabicName,
                                 $Active,
                                 $UpdDate,
                                 $UpdUser,
                                 $Save,
                                 $Back));

    }
}