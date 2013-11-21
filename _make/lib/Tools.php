<?php
/*
 * @name Tools.php
 * @description some function tools
 * @author klml based on https://github.com/lonescript/php-site-maker
 * @date 2013-09-27
 */


// error log
function error($value='') {
	echo "[ERROR] NOT written: $value\n";
}

// success log
function success($value='') {
	echo "[SUCCESS] written: $value\n";
}

// prevent Directory traversal attack
function preventDirectoryTraversal($sourcepath) {
    if (false === strpos($sourcepath, '..') ) {  
        // TODO http://stackoverflow.com/questions/4205141/preventing-directory-traversal-in-php-but-allowing-paths/4205182#4205182
        // http://www.phpfreaks.com/tutorial/php-security
    } else {
        die( $sourcepath . ' contains illegal characters' );
    }
    return $sourcepath ;
}


// return yaml conf and md seperated from source
function splitYamlMD($filePath, $separator) {
    $ymlMD = array();
    $ymlMD = explode( $separator , file_get_contents($filePath) ) ;
    return $ymlMD ; 
}


// create *.html  // TODO remove
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

// write source file from webeditor // TODO rm inline?
function writeFile( $sourcepath, $content ) { 
    $msg = file_put_contents( $sourcepath , $content ) ? success( $sourcepath ) : error( $sourcepath ) ;
    return $msg ;
}

?>
