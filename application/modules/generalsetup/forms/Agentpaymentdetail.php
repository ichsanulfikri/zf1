<?php
	class GeneralSetup_Form_Agentpaymentdetail extends Zend_Dojo_Form { 
		 public function init() {
			$gstrtranslate = Zend_Registry::get('Zend_Translate');
	    	$lstrAmountConstraint = "{pattern:'0.##'}"; 
	    	
	    	$IdPayment  = new Zend_Form_Element_Hidden('IdPayment');
			$IdPayment  	->removeDecorator("DtDdWrapper")
        		  					->removeDecorator("Label")
        		  					->removeDecorator('HtmlTag');
        		  					
        	$UpdDate  = new Zend_Form_Element_Hidden('UpdDate');
			$UpdDate  				->removeDecorator("DtDdWrapper")
        		  					->removeDecorator("Label")
        		  					->removeDecorator('HtmlTag');
        		  					
        	$UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
			$UpdUser  				->removeDecorator("DtDdWrapper")
        		  					->removeDecorator("Label")
        		  					->removeDecorator('HtmlTag');
        		  					
        	$AgentType = new Zend_Dojo_Form_Element_FilteringSelect('AgentType');	
        	$AgentType->addMultiOptions(array('1' => 'Staff',
									    '2' => 'Student',
        								'3' => 'Other'));
        	$AgentType->setAttrib('required',"true");
        	$AgentType->removeDecorator("DtDdWrapper");
        	$AgentType->removeDecorator("Label");
       		$AgentType->removeDecorator('HtmlTag');
	    	$AgentType->setAttrib('dojoType',"dijit.form.FilteringSelect");
        		  					
        	$Intake = new Zend_Dojo_Form_Element_FilteringSelect('Intake');	
        	$Intake->setAttrib('required',"true");
        	$Intake->removeDecorator("DtDdWrapper");
        	$Intake->removeDecorator("Label");
       		$Intake->removeDecorator('HtmlTag');
	    	$Intake->setAttrib('dojoType',"dijit.form.FilteringSelect");
	    	
	    	$Amount = new Zend_Form_Element_Text('Amount');	
        	$Amount	->setAttrib('dojoType',"dijit.form.ValidationTextBox")
        						->setAttrib('required',"true")       			 
					        	->setAttrib('maxlength','50')       
					        	->removeDecorator("DtDdWrapper")
					        	->removeDecorator("Label")
					        	->removeDecorator('HtmlTag');
        						
			$Save = new Zend_Form_Element_Submit('Save');
			$Save->dojotype="dijit.form.Button";
	        $Save->label = $gstrtranslate->_("Save");
	        $Save->setAttrib('class','NormalBtn');
			$Save->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');				
        		
			$Search = new Zend_Form_Element_Submit('Search');
			$Search	->setAttrib('id', 'search')
				->setAttrib('name', 'search')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
			$Erase = new Zend_Form_Element_Button('Erase');
			$Erase->setAttrib('class', 'NormalBtn');
			$Erase->setAttrib('dojoType',"dijit.form.Button");
			$Erase->label = $gstrtranslate->_("Clear");
			$Erase->setAttrib('OnClick', 'clearpayment()');
        	$Erase->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
			$Insert= new Zend_Form_Element_Button('Insert');
			$Insert->setAttrib('class', 'NormalBtn');
			$Insert->setAttrib('dojoType',"dijit.form.Button");
			$Insert->label = $gstrtranslate->_("Add");
			$Insert->setAttrib('OnClick', 'addPayment()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
	   //form elements
        $this->addElements(array($Insert,
        						 $Erase,
        						 $Search,
        						 $Save,
        						 $Amount,
        						 $Intake,
        						 $AgentType,
        						 $UpdUser,
        						 $UpdDate,
        						 $IdPayment,
        						 ));
	    	
	    	
		
	}
	}