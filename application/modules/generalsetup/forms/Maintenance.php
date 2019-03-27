<?php
	class GeneralSetup_Form_Maintenance extends Zend_Dojo_Form {
	public function init(){
		$gstrtranslate = Zend_Registry::get('Zend_Translate');
		$this->setName('formmaintainanaceEdit');

		$idDefType = new Zend_Form_Element_Hidden('idDefType');
		$idDefType	->setAttrib('id','idDefType')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$idDefinition = new Zend_Form_Element_Hidden('idDefinition');
		$idDefinition	->setAttrib('id','idDefinition')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');



		$DefinitionCode = new Zend_Form_Element_Text('DefinitionCode',array('regExp'=>"^[a-zA-Z0-9]+$",'invalidMessage'=>""));
		$DefinitionCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$DefinitionCode->setAttrib('required',"true")
        		->setAttrib('maxlength','20')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');


		$DefinitionDesc = new Zend_Form_Element_Text('DefinitionDesc');
		$DefinitionDesc->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$DefinitionDesc->setAttrib('required',"true")
        		->setAttrib('maxlength','1000')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

		$defTypeDesc = new Zend_Form_Element_Text('defTypeDesc');
		$defTypeDesc->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$defTypeDesc->setAttrib('required',"true")
		        ->setAttrib('propercase','true')
		        ->setAttrib('onChange','duplicate(this.value)')
        		->setAttrib('maxlength','1000')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');


        $BahasaIndonesia= new Zend_Form_Element_Text('BahasaIndonesia');
		$BahasaIndonesia->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$BahasaIndonesia->setAttrib('maxlength','100')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');


        $Description = new Zend_Form_Element_Textarea('Description');
        $Description	->setAttrib('cols', '30')
        				->setAttrib('rows','3')
        				->setAttrib('style','width = 10%;')
        				->setAttrib('maxlength','250')
        				->setAttrib('dojoType',"dijit.form.SimpleTextarea")
        				->setAttrib('style','margin-top:10px;border:1px light-solid #666666;color:#666666;font-size:11px')
        				->removeDecorator("DtDdWrapper")
        				->removeDecorator("Label")
        				->removeDecorator('HtmlTag');

         $Active = new Zend_Form_Element_Checkbox('Active');
         $Active	->setAttrib('dojoType',"dijit.form.CheckBox")
       			 	->setAttrib("checked","checked")
        			->removeDecorator("DtDdWrapper")
      				->removeDecorator("Label")
        			->removeDecorator('HtmlTag');

         $Status = new Zend_Form_Element_Checkbox('Status');
         $Status	->setAttrib('dojoType',"dijit.form.CheckBox")
       			 	->setAttrib("checked","checked")
        			->removeDecorator("DtDdWrapper")
      				->removeDecorator("Label")
        			->removeDecorator('HtmlTag');

		$Update = new Zend_Form_Element_Submit('Update');
		$Update	->setAttrib('id', 'button')
				->setAttrib('name', 'button')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

		$AddNew = new Zend_Form_Element_Submit('AddNew');
		$AddNew	->setAttrib('id', 'submitbutton')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');


		$Add = new Zend_Form_Element_Submit('Add');
		$Add->dojotype="dijit.form.Button";
        $Add->label = $gstrtranslate->_("Add");
		$Add	->setAttrib('id', 'Add')
				->setAttrib('name', 'Add')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
       	$Save->label = $gstrtranslate->_("Save");
		$Save	->setAttrib('id', 'save')
				->removeDecorator("Label")
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

		$Search = new Zend_Form_Element_Submit('Search');
		$Search->dojotype="dijit.form.Button";
        $Search->label = $gstrtranslate->_("Search");
		$Search	->setAttrib('id', 'search')
				->setAttrib('name', 'search')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

		$Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->dojotype="dijit.form.Button";
        $Clear->label = $gstrtranslate->_("Clear");
		$Clear	->setAttrib('id', 'Clear')
				->setAttrib('name', 'Clear')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

		$Close = new Zend_Form_Element_Submit('Close');
		$Close->dojotype="dijit.form.Button";
        $Close->label = $gstrtranslate->_("Close");
		$Close	->setAttrib('id', 'Close')
				->setAttrib('onclick', 'fnCloseLyteBox()')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');



		$this->addElements(
					array(
						$idDefType, $idDefinition, $DefinitionCode,$defTypeDesc,$DefinitionDesc,$Active,$Status,$Update,$AddNew,$Search,$BahasaIndonesia,$Description,
						$Save,$Clear,$Add,$Close
					)
		);
    }
}
