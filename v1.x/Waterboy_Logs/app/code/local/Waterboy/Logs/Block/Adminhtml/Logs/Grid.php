<?php

class Waterboy_Logs_Block_Adminhtml_Logs_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('waterboy_logs_grid');
        $this->setDefaultSort('name');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = new Varien_Data_Collection();
        $io = new Varien_Io_File();
        $baseDir = Mage::getBaseDir();
        $varDir = $baseDir . DS . 'var';
        $logDir = $varDir . DS . 'log';
        $reportDir = $varDir . DS . 'report';

        $io->cd($logDir);
        $logFiles = $io->ls();

        foreach ($logFiles as $file) {
            $fileInfo = new Varien_Object();
            $fileInfo->setName($file['text']);
            $fileInfo->setDirectory($logDir);
            $fileInfo->setFullPath($logDir . DS . $file['text']);
            $fileInfo->setDirectoryType('log');

            $collection->addItem($fileInfo);
        }

        $io->cd($reportDir);
        $reportFiles = $io->ls();

        foreach ($reportFiles as $file) {
            $fileInfo = new Varien_Object();
            $fileInfo->setName($file['text']);
            $fileInfo->setDirectory($reportDir);
            $fileInfo->setFullPath($reportDir . DS . $file['text']);
            $fileInfo->setDirectoryType('report');

            $collection->addItem($fileInfo);
        }

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns() {
        $helper = Mage::helper('waterboy_logs');

        $this->addColumn('name', array(
            'header' => $helper->__('Name'),
            'index'  => 'name',
            'sortable'  => true
        ));

        $this->addColumn('directory', array(
            'header' => $helper->__('Directory'),
            'index'  => 'directory'
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/download', array(
            'filename' => $row->getName(),
            'dir' => $row->getDirectoryType()));
    }
}