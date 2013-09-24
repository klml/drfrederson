<?php
/*
 * @name writer.php
 * @class WritePage
 * @description writes source file from web
 * @author klml
 */

require_once 'lib/Spyc.php'; // needed? 
require_once 'lib/Tools.php';


class WritePage {
	protected $config;
	protected $wwwPath;
	protected $filePath;
	protected $pages;	// all pages data
	protected $tmplData;	// used for tmpl

	public function __construct() {
		$this->config = spyc_load_file('config.yml');
		$this->wwwPath = $this->config['path'];
		$this->filePath = $this->wwwPath; // ?
        $this->process() ;
	}

    public function process() {
        $this->writeFile();
    }

	public function writeFile() { 

        if (!isset( $_POST["sourcepath"], $_POST["content"] )) {
            $msg = 'no data';
        } else {
            $sourcepath = $_POST["sourcepath"] ;

            $content = $_POST["content"] ;
            $msg = $sourcepath ;
            if (false === strpos($sourcepath, '..')) {
                $msg .= file_put_contents( '../' . $sourcepath , $content ) ? ' written' : ' not written'; // '../' . $sourcepath baääääähhh
            } else {
                $msg .= ' contains illegal characters';
            }
        }
        echo $msg ;
        require_once 'make.php';  // bähhhhh 
        die();
    }

}

$page = new WritePage();



?>
