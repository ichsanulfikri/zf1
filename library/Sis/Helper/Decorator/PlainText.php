<?php
class Sis_Helper_Decorator_PlainText extends Zend_Form_Decorator_Abstract
{
	public function render($content)
	{
		return $content . $this->getOption('text');
	}
}
?>