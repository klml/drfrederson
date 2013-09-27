<?php
/*
 * @name Tools.php
 * @description some function tools
 * @author klml based on https://github.com/lonescript/php-site-maker
 * @date 2013-09-27
 */


// error log
function error($value='') {
	echo "[ERROR] $value\n";
}

// success log
function success($value='') {
	echo "[SUCCESS] $value\n";
}

// return yaml conf and md seperated from source
function splitYamlMD($filePath) {
    $ymlMD = array();
    $ymlMD = explode('---', file_get_contents($filePath) ) ;
    return $ymlMD ; 
}


// create *.html
function makeHtmlFile($target, $layout, $data) {
	ob_start();
	require $layout;
	$html = ob_get_contents();
	ob_end_clean();
	$fp = fopen($target, 'w');
	if ($fp) {
		fwrite($fp, $html);
		fclose($fp);
	}
}

// write source file from webeditor
function writeFile( $sourcepath, $content ) { 
    $msg = $sourcepath ;
    $msg .= file_put_contents( $sourcepath , $content ) ? ' written' : ' not written';
    return $msg ;
}

?>
