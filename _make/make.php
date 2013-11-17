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

class MakeSite {
	protected $config;
	protected $wwwPath;
	protected $filePath;
	protected $pages;	// all pages data
	protected $tmplData;	// used for tmpl

	public function __construct() {
		if( !file_exists( 'config.yml' ) ) { die("missing config.yml\n") ; };
        $this->config = spyc_load_file('config.yml');
		$this->filePath = $this->config['path'];
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
            $this->createPages( $argv[0] );       // create single pages from webeditor

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

            $this->createPages( $sourcepath );

        } else {
            $sourcedirrecursive = new RecursiveDirectoryIterator( $this->filePath['source'] );
            foreach (new RecursiveIteratorIterator($sourcedirrecursive) as $sourcepath => $file) { // ? diffrenc $file  vs $sourcepath
                $this->createPages($sourcepath);
                
            }
		}
	}

	// init data for tmpl
	protected function initTmplData() {
		$this->tmplData = array(
			'config' => $this->config,
			'page' => null
		);
		$this->tmplData['config']['siteTitle'] = $this->config['siteName']; // ??
	}

	// create page    
	public function createPages($sourcepath) { // protected // e.g. my.page.md

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

            $ymlMD = splitYamlMD( $sourcepath, $this->config['ymlseparator'] ) ;

            // read page config (template, meta, etc) from file, directory or mainconf
            $tmpInfo = $this->config ;      // read general config
            
            if ( file_exists($directoriesConf = $directoriesName . '/config.yml' ) ) { // overwrite with directory config if exist
                $tmpInfo = array_merge( $tmpInfo , spyc_load_file( file_get_contents($directoriesConf) ) ) ;
            }
            if ( isset($ymlMD[1]) ) {  // overwrite with page config if exist
                $tmpInfopage = spyc_load_file( $ymlMD[1] ) ;
                $tmpInfo = array_merge( $tmpInfo , $tmpInfopage ) ;
            }
            if ( !isset($ymlMD[1], $tmpInfopage['title']) ) {  // use first markdown heading as title if not in pageconfig
                preg_match('/(?m)^#+(.*)/', $ymlMD[0], $titelheading) ;
                if ( isset( $titelheading[1]) ) {
                    $tmpInfo['title'] = trim( $titelheading[1] ) ;
                }
            }

            $page = array();
            $page['url'] = $this->config['baseurl'] . $lemma ;
            $page['filePath'] = $this->filePath['html'] . $lemma . $this->config['htmlextension']; // TODO fill inn $directoriesName

            $page['layout'] = $this->filePath['layout'] . $tmpInfo['layout'] . '.php';
            $page['name'] = $tmpInfo['title']; // TODO use title in template
            $page['lemma'] = $lemma ;
            $page['sourcepath'] = $sourcepath ;
            $page['comment'] = $tmpInfo['comment'];

            $page['pagedurable'] = Markdown( file_get_contents($tmpInfo['pagedurable']) ); // TODO md switching

            switch ( $filenameExtension ) {                     // file parse handling
                case ("txt"):
                case ("css"):
                case ("js"):
                    $page['content'] =  nl2br( $ymlMD[0] ) ;
                    $page['name'] = $lemma ;
                break;

                case ("md"):
                    $page['content'] = Markdown( $ymlMD[0] ) ;
                break;
                case ("html"):
                default:
                    $page['content'] = $ymlMD[0] ;
                break;
            }

            $page['description'] = $tmpInfo['description'];

            // <!-- more --> cutter //~ TODO move to outermarkdown
            //~ $more = explode('<!--more-->', $item['content']);
            //~ if (count($more) >= 2) {
                //~ $item['more'] = $more[0];
            //~ } else {
                //~ $item['more'] = false;
            //~ }

            //  merge config, template and content
            $this->tmplData['page'] = $page;

			makeHtmlFile( $page['filePath'] , $page['layout'] , $this->tmplData);
			$this->initTmplData();
			success('created page: ' . $page['filePath']);
    }
}

$site = new MakeSite();
?>
