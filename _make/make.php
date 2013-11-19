<?php
/*
 * @name make.php
 * @class MakeSite
 * @description generate a static site
 * @author klml based on https://github.com/lonescript/php-site-maker
 */

require_once 'lib/Markdown.php';
require_once 'lib/spyc/Spyc.php';
require_once 'lib/Tools.php';
require_once 'lib/mustache/src/Mustache/Autoloader.php';

class MakeSite {
	protected $config;
	protected $filePath;
	protected $pages;	// all pages data
	protected $tmplData;	// used for tmpl

	public function __construct() {
        if( !file_exists( 'config.yml' ) ) { die("missing config.yml\n") ; };
        $this->siteconfig = spyc_load_file('config.yml');
        $this->filePath = $this->siteconfig['path'];
        $this->process() ;
	}

    public function process() {
        $this->initTmplData();
        $this->selectPages();
    }
	// init pages data

	protected function selectPages() { // http and cli routing
        global $argv ;
        $this->pages = array();

        if ( count($argv) > 1  ) {      // create single pages from cli input
            array_shift($argv);         // remove script name
            $this->createPage( $argv[0] );       // create single pages from webeditor

        } else if (  isset( $_POST["sourcepath"] )  ) { // writes single pages from webeditor

            if (false === strpos($sourcepath, '..') ) { 
                $sourcepath = '../' . $_POST["sourcepath"] ; // TODO URL vs dir 
            } else {
                echo $msg .= ' contains illegal characters';
                die();
            }

            if ( isset ( $_POST["content"] )  ) { 
                echo writeFile( $sourcepath, $_POST["content"]  );
            }

            $this->createPage( $sourcepath );

        } else {
            $sourcedirrecursive = new RecursiveDirectoryIterator( $this->filePath['source'] );
            foreach (new RecursiveIteratorIterator($sourcedirrecursive) as $sourcepath => $file) { // ? diffrenc $file  vs $sourcepath
                $this->createPage($sourcepath);
                
            }
		}
	}

	// init data for tmpl
	protected function initTmplData() {
		$this->tmplData = array(
			'config' => $this->siteconfig, // needed? TODO
			'page' => null
		);
	}

	// create page    
	public function createPage($sourcepath) { // protected // e.g. my.page.md

            $directoriesName = explode('/', $sourcepath ) ;
            $filename = array_pop($directoriesName) ;               // e.g. my.page.md
            $directoriesName = implode('/', $directoriesName) ;     // e.g ../_source/mysubdir/

            $filenamewithExtension = explode('.', $filename ) ;
            $filenameExtension = end($filenamewithExtension);           //  fileextension e.g. md
            array_pop( $filenamewithExtension ) ;                       // remove fileextension

            $lemma = implode('.', $filenamewithExtension ) ;            // e.g. my.page

            if ( is_dir( $filename) ) {                             // dont parse directories
                 return;
            }

            $ymlMD = splitYamlMD( $sourcepath, $this->siteconfig['ymlseparator'] ) ;

            // read page config (template, meta, etc) from file, directory or mainconf
            $tmpInfo = $this->siteconfig ;      // read general config

            if ( file_exists($directoriesConf = $directoriesName . '/config.yml' ) ) { // overwrite with directory config if exist
                $tmpInfo = array_merge( $tmpInfo , spyc_load_file( file_get_contents($directoriesConf) ) ) ;
            }
            if ( isset($ymlMD[1]) ) {  // overwrite with page config if exist
                $tmpInfopage = spyc_load_file( $ymlMD[1] ) ;
                $tmpInfo = array_merge( $tmpInfo , $tmpInfopage ) ;
            }
            if ( !isset($ymlMD[1], $tmpInfopage['title']) ) {  // use first markdown heading as title if not in pageconfig // TODO to tools
                preg_match('/(?m)^#+(.*)/', $ymlMD[0], $titelheading) ;
                if ( isset( $titelheading[1]) ) {
                    $tmpInfo['title'] = trim( $titelheading[1] ) ;
                }
            }

            $page = array();
            $page['filePath'] = $this->filePath['html'] . $lemma . $this->siteconfig['htmlextension']; // TODO fill inn $directoriesName
            $page['lemma'] = $lemma ;
            $page['sourcepath'] = substr( $sourcepath , 3 ) ; // remove leading "../"

            $page['pagetitle'] = $tmpInfo['title'];      // needed her to overwrite 
            $page['description'] = $tmpInfo['description'];
            $page['comment'] = $tmpInfo['comment'];


            $page['pagedurable'] = Markdown( file_get_contents($tmpInfo['pagedurable']) ); // TODO md switching

            // file parse handling
            switch ( $filenameExtension ) {
                case ("md"):
                    $page['content'] = Markdown( $ymlMD[0] ) ;
                break;
                case ("html"):
                    $page['content'] = $ymlMD[0] ;
                break;
                default:    // css js yaml txt etc
                    $page['content'] =  nl2br( $ymlMD[0] ) ;
                    $page['pagetitle'] = $lemma ;               // use lemma, there is no meta
                break;
            }



            // <!-- more --> cutter //~ TODO move to outermarkdown
            //~ $more = explode('<!--more-->', $item['content']);
            //~ if (count($more) >= 2) {
                //~ $item['more'] = $more[0];
            //~ } else {
                //~ $item['more'] = false;
            //~ }



            //  merge config, template and content TODO
            $this->tmplData['page'] = $page;

            Mustache_Autoloader::register();
            // use .html instead of .mustache for default template extension
            $mustacheopt =  array('extension' => '.html'); // TODO config
            $mustache = new Mustache_Engine(array(
                'loader' => new Mustache_Loader_FilesystemLoader( $this->filePath['template'] , $mustacheopt),
            ));

            $mustachecontent = $mustache->render('skeleton', $this->tmplData ); // TODO config
            file_put_contents( $page['filePath'], $mustachecontent);

            success('created page: ' . $page['filePath']);
    }
}
$site = new MakeSite();
?>
