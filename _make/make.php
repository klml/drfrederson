<?php
/*
 * @name make.php
 * @class MakeSite
 * @description generate a static site
 * @author 2dkun
 */

require_once 'lib/Markdown.php';
require_once 'lib/Spyc.php';
require_once 'lib/Tools.php';

class MakeSite {
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
		//$this->createArchives();
	}

    public function process() {
        $this->readSourceDir();
        $this->initTmplData();
        $this->createPages();
    }
	// init pages data

	protected function readSourceDir() { // rename in whcih page shell created
        global $argv ;
        $this->pages = array();

        if ( count($argv) > 1  ) {      // create single pages from cli input
            array_shift($argv);         // remove script name
            foreach( $argv as $lemma ) {
                $this->preparePage( $this->config['sourcedir'] . $lemma );
            }
        } else if ( isset($_GET["lemma"])  ) { // create single pages from cli input
            // TODO if file exist
            $this->preparePage( $this->config['sourcedir'] . $_GET["lemma"] . '.' . $this->config['sourceextension'] );
        } else {
            $sourcedirrecursive = new RecursiveDirectoryIterator( $this->config['sourcedir'] );
            foreach (new RecursiveIteratorIterator($sourcedirrecursive) as $filenamepath => $file) { // ? diffrenc $file  vs $filenamepath
                $this->preparePage($filenamepath);
            }
		}
        
	}


	public function preparePage($filenamepath) { // protected

            $directoriesName = explode('/', $filenamepath ) ;
            $filename = array_pop($directoriesName) ;               // e.g. my.page.md
            $directoriesName = implode('/', $directoriesName) ;     // e.g ../_source/mysubdir/

            $filenamewithExtension = explode('.', $filename ) ;
            $filenameExtension = end($filenamewithExtension);           //  fileextension e.g. md
            array_pop( $filenamewithExtension ) ;                       // remove fileextension

            $lemma = implode('.', $filenamewithExtension ) ;            // e.g. my.page

            if ( $this->config['pagedurable'] == $filenamepath || $filenameExtension != $this->config['sourceextension'] ) {  // exceptions: sidebar, config 
                 return;
            }

            $ymlMD = splitYamlMD( $filenamepath ) ;

            // read page config (template, meta, etc) from file, directory or mainconf
            $directoriesConf = $directoriesName . '/config.yml' ;
            if ( isset($ymlMD[1]) ) {
                $tmpInfo = spyc_load_file( $ymlMD[1] ) ;
            } else if ( file_exists($directoriesConf) ) {
                $tmpInfo = spyc_load_file( file_get_contents($directoriesConf) );
                // TODO dir climb up
            } else {
                $tmpInfo = $this->config ;
            }


            $page = array();
            $page['url'] = $this->config['baseurl'] . $lemma ;
            $page['filePath'] = $this->config['htmldir'] . $lemma . $this->config['htmlextension']; // TODO fill inn $directoriesName

            $page['layout'] = $this->filePath['layout'] . $tmpInfo['layout'] . '.php';
            $page['name'] = $tmpInfo['name'];
            $page['lemma'] = $lemma ;
            $page['comment'] = $tmpInfo['comment'];

            $page['pagedurable'] = Markdown( file_get_contents($this->config['pagedurable']) );
            $page['content'] = Markdown( $ymlMD[0] ) ;

            // <!-- more --> cutter //~ TODO move to outermarkdown
            //~ $more = explode('<!--more-->', $item['content']);
            //~ if (count($more) >= 2) {
                //~ $item['more'] = $more[0];
            //~ } else {
                //~ $item['more'] = false;
            //~ }
            $this->pages[] = $page;
    }

	// init data for tmpl
	protected function initTmplData() {
		$this->tmplData = array(
			'config' => $this->config,
			'pages' => $this->pages,
			'page' => null
		);
		$this->tmplData['config']['siteTitle'] = $this->config['siteName'];
	}

	// create page
	protected function createPages() {
		foreach ($this->pages as $item) {

			$this->tmplData['page'] = $item;
			$this->tmplData['config']['siteTitle'] = $item['name'] . ' | ' .$this->tmplData['config']['siteName'];
			makeHtmlFile( $item['filePath'] , $item['layout'] , $this->tmplData);
			$this->initTmplData();
			success('created page: ' . $item['name']);
		}
	}
}

$site = new MakeSite();


?>
