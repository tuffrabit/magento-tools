<?php

class Waterboy_Logs_Adminhtml_WaterboylogsController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction() {
        $this->loadLayout()
            ->_addContent($this->getLayout()->createBlock('waterboy_logs/adminhtml_logs_grid'))
            ->renderLayout();
    }

    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('waterboy_logs/adminhtml_logs_grid')->toHtml()
        );
    }

    public function downloadAction() {
        $session = Mage::getSingleton('adminhtml/session');
        $filename = $this->getRequest()->getParam('filename');
        $dir = $this->getRequest()->getParam('dir');

        if ($filename && $dir) {
            if ($dir == 'log' || $dir == 'report') {
                $io = new Varien_Io_File();
                $baseDir = Mage::getBaseDir();
                $varDir = $baseDir . DS . 'var';
                $fileDir = $varDir . DS . $dir;

                $io->cd($fileDir);

                $tmpName = tempnam(sys_get_temp_dir(), 'data');
                $file = fopen($tmpName, 'w');
                $fileContents = '';
                $fileContents = $io->read($filename);

                if ($fileContents) {
                    fwrite($file, $fileContents);
                    fclose($file);

                    $this->getResponse ()
                        ->setHttpResponseCode ( 200 )
                        ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
                        ->setHeader('Pragma', 'public', true)
                        ->setHeader('Content-type', 'application/force-download')
                        ->setHeader('Content-Length', filesize($tmpName))
                        ->setHeader('Content-Disposition', 'attachment' . '; filename=' . $filename);
                    $this->getResponse()->clearBody();
                    $this->getResponse()->sendHeaders();

                    readfile($tmpName);
                    unlink($tmpName);
                    exit;
                }
                else {
                    $session->addError('Could not get file contents.');
                }
            }
            else {
                $session->addError('Access to the requested directory is not permitted.');
            }
        }
        else {
            $session->addError('Request had bad or missing parameters.');
        }

        $this->_redirect('*/*/index');
    }
}
