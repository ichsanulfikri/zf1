<?php 
class GeneralSetup_Form_NavigationMenu extends Zend_Form{
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id','navigation_menu_form');
		
		$this->addElement('text','label', array(
			'label'=>'Label',
			'required'=>'true'
		));
		
		$this->addElement('text','title', array(
			'label'=>'Title',
			'required'=>'true'
		));
		
		$this->addElement('text','module', array(
			'label'=>'Module'));
		
		$this->addElement('text','controller', array(
			'label'=>'Controller'));
		
		$this->addElement('text','action', array(
			'label'=>'Action'));
		
	
		//button
		$this->addElement('submit', 'save', array(
          'label'=>'Submit',
          'decorators'=>array('ViewHelper')
        ));
        
        $this->addElement('submit', 'cancel', array(
          'label'=>'Cancel',
          'decorators'=>array('ViewHelper'),
          'onClick'=>"window.location ='" . $this->getView()->url(array('module'=>'generalsetup', 'controller'=>'navigation','action'=>'index'),'default',true) . "'; return false;"
        ));
        
        $this->addDisplayGroup(array('save','cancel'),'buttons', array(
	      'decorators'=>array(
	        'FormElements',
	        array('HtmlTag', array('tag'=>'div', 'class'=>'buttons')),
	        'DtDdWrapper'
	      )
	    ));
		
	}
}
?>