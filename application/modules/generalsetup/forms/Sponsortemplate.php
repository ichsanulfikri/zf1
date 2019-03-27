<?php
class GeneralSetup_Form_Sponsortemplate extends Zend_Dojo_Form {
    public function init(){			
		$gstrtranslate =Zend_Registry::get('Zend_Translate'); 			
		
		$idSponsortemplate = new Zend_Form_Element_Hidden('idSponsortemplate');
		$idSponsortemplate->setAttrib('id','idSponsortemplate')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        		 	 
		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->setAttrib('id','UpdUser')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
				 	->removeDecorator('HtmlTag');
				 	
				 	
		$templateName = new Zend_Form_Element_Text('templateName');
		$templateName  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")  
					-> setAttrib('required',"true")                               
					->setAttrib('maxlength','100')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');	

		$Description = new Zend_Form_Element_Textarea('Description');	
        $Description	->setAttrib('cols', '21')
        				->setAttrib('rows','5')
        				->setAttrib('maxlength','250')
        				->setAttrib('dojoType',"dijit.form.SimpleTextarea")
        				->setAttrib('style','margin-top:10px;border:1px light-solid #666666;color:#666666;font-size:11px')
        				->removeDecorator("DtDdWrapper")
        				->removeDecorator("Label")
        				->removeDecorator('HtmlTag');					
		
        $idAccount=new Zend_Dojo_Form_Element_FilteringSelect('idAccount');
        $idAccount->setAttrib('maxlength','20')
      	  			->setRegisterInArrayValidator(false);
      	$idAccount->setAttrib('multiple','multiple');
		//$idAccount-> setAttrib('required',"true");
		$idAccount->removeDecorator("DtDdWrapper");
		$idAccount->removeDecorator("Label");
		$idAccount->removeDecorator('HtmlTag');
		$idAccount->setAttrib('dojoType',"dijit.form.MultiSelect");
		$idAccount->setAttrib('style', 'width: 15.8em; height: 10em; margin-left:0px;' ); 

		
		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
		$Save->setAttrib('class', 'NormalBtn');
        $Save->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
         		
        $this->addElements(
						array(
							$idSponsortemplate,
							$UpdDate,
							$UpdUser,
							$templateName,
							$Description,
							$idAccount,
							$Save
						)
			);
    	}
	}
?>