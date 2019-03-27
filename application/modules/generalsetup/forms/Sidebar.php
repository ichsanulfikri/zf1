<?php 
class GeneralSetup_Form_Sidebar extends Zend_Form{
	
	protected $roleid, $menuid;
	
	public function setRoleid($roleid){
		$this->roleid = $roleid;
	}
	
	public function setMenuid($menuid){
		$this->menuid = $menuid;
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id','form_sidebar');
		
		$this->addElement('text','name', array(
			'label'=>'Name',
			'required'=>'true'
		));
		
		$this->addElement('text','module', array(
			'label'=>'Module'));
		
		$this->addElement('text','controller', array(
			'label'=>'Controller'));
		
		$this->addElement('text','action', array(
			'label'=>'Action'));
		
		//title head
		$this->addElement('radio','title_head', array(
			'label'=>'Title Header'
		));
		
		$this->title_head->addMultiOptions(array(
			'1' => 'Yes',
			'0' => 'No'
		))->setSeparator('&nbsp;&nbsp;')->setValue("0");
		
		//visibility
		$this->addElement('radio','visible', array(
			'label'=>'Visibility'
		));
		
		$this->visible->addMultiOptions(array(
			'1' => 'Show',
			'0' => 'Hide'
		))->setSeparator('&nbsp;&nbsp;')->setValue("1");
		

		//button
		$this->addElement('submit', 'save', array(
          'label'=>'Submit',
          'decorators'=>array('ViewHelper')
        ));
        
        $this->addElement('submit', 'cancel', array(
          'label'=>'Cancel',
          'decorators'=>array('ViewHelper'),
          'onClick'=>"window.location ='" . $this->getView()->url(array('module'=>'generalsetup', 'controller'=>'navigation','action'=>'sidebar', 'role_id'=>$this->roleid, 'menu_id'=>$this->menuid),'default',true) . "'; return false;"
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