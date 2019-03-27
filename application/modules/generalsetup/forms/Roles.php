<?php
class GeneralSetup_Form_Roles extends Zend_Dojo_Form
{
	public function init()
	{	$gstrtranslate = Zend_Registry::get('Zend_Translate');
		
		$this->setMethod('post');
		
		$lstrrolecode = $this->createElement('text', 'DefinitionCode');
		$lstrrolecode->removeDecorator('DtDdWrapper');
		$lstrrolecode->removeDecorator('Label');
		$lstrrolecode->removeDecorator('HtmlTag');
		

        $lbtnrolereset = $this->createElement('submit','Clear');
        $lbtnrolereset->removeDecorator("DtDdWrapper");
        $lbtnrolereset->class = "NormalBtn";
        
        $Save = new Zend_Form_Element_Submit('Save');
        $Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
		$Save	->setAttrib('id', 'save')
				->removeDecorator("Label")
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
        
        $Clear = new Zend_Form_Element_Submit('Clear');
		$Clear	->setAttrib('id', 'Clear')
				->setAttrib('name', 'Clear')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
        $Add = new Zend_Form_Element_Submit('Add');
		$Add	->setAttrib('id', 'Add')
				->setAttrib('name', 'Add')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
	    $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        
		
        $this->addElements(array($lstrrolecode,
        				$UpdDate,$UpdUser,
        				$Add,
        				$Clear,$Save,
        				$lbtnrolereset));
	}
}