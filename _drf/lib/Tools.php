<?php
/*
 * @name Tools.php
 * @description some function tools
 * @author klml based on https://github.com/lonescript/php-site-maker
 * @date 2013-09-27
 */


// error log
function error($value='') {
    $trace=debug_backtrace();
    $caller=$trace[1];
    echo "[ERROR] {$caller['function']}: $value\n";

}

// success log
function success($value='') {
    $trace=debug_backtrace();
    $caller=$trace[1];
    echo "[SUCCESS] {$caller['function']}: $value\n";
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
function splitYamlProse($filePath, $separator) {
    $ymlProse = explode( $separator , file_get_contents($filePath) ); // TODO limit ?
    if ( count($ymlProse) == 1 ) $ymlProse[1] = '';
    $ymlProse = array_slice( $ymlProse, 0, 2 );
    $ymlProse = array_combine( array('prose', 'meta' ) , $ymlProse );
    return $ymlProse ; 
}

// uses first heading in markdown as page title
function getHtmltitleMD($markdown) {
    $title = '';
    preg_match('/(?m)^#+(.*)/', $markdown, $titleheading) ;
    if ( isset( $titleheading[1]) ) {
        $title = trim( $titleheading[1] ) ;
    }
    return $title ;
}

// wikistylelinks [[ ]]
function wikistylelinks($content) {
    $doublebracket = '/\[\[(.*)\]\]/U';
    $content = preg_replace_callback(   $doublebracket, function ($matches) {
            return "<a href='/" . iconv("utf-8","ascii//TRANSLIT", str_replace(' ', '_', strtolower($matches[1]) ) ) . "'>" . $matches[1] . "</a>" ;
        }, $content
    );
    return $content ;
}

// <!-- more --> cutter
function moreCutter($content) {
    $more = explode('<!--more-->', $content);
    if (count($more) >= 2) {
        $more = $more[0];
    } else {
        $more = false;
    }
    return $more ;
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

?>
