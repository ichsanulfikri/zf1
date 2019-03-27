<?php 

class GeneralSetup_Form_SearchProgram extends Zend_Form
{
	
	public function init()
	{
				
		$this->setMethod('post');
		$this->setAttrib('id','Search');
						
		
		$this->addElement('text','keywords', array(
			'label'=>$this->getView()->translate('Keywords')				    
		));
		
		//button
		$this->addElement('submit', 'save', array(
          'label'=>$this->getView()->translate('Search'),
          'decorators'=>array('ViewHelper')
        ));
        
        
        $this->addDisplayGroup(array('save'),'buttons', array(
	      'decorators'=>array(
	        'FormElements',
	        array('HtmlTag', array('tag'=>'div', 'class'=>'buttons')),
	        'DtDdWrapper'
	      )
	    ));
	    
	}
		
}

?>