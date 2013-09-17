<?php
/*
 * @name Tools.php
 * @description some function tools
 * @author yqtaku
 * @date 26 DEC, 2012
 */


// error log
function err($value='') {
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

?>
