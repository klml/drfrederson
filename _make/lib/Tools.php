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
function suc($value='') {
	echo "[SUCCESS] $value\n";
}

// return article content in html.
function getMdHtml($filePath) {
	return trim(Markdown(preg_replace('/^---.*?---/s', '', file_get_contents($filePath))));
	// return preg_replace('/[\-]+\s+.*\s+[\-]+/i', '', file_get_contents($filePath));
}


// return yaml conf and md
function splitYamlMD($filePath, $index) {
	$ymlMD = array();
    $ymlMD = explode('---', file_get_contents($filePath) ) ; // no get
    return spyc_load_file($ymlMD[$index]); // TODO test is config
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
