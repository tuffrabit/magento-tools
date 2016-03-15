<?php

class Waterboy_Logs_Block_Adminhtml_Logs extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'waterboy_logs';
        $this->_controller = 'adminhtml_waterboy_logs';
        $this->_headerText = Mage::helper('waterboy_logs')->__('Logs and Reports');

        parent::__construct();
    }
}