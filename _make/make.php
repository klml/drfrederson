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
    
    public function __construct() {
        if( !file_exists( 'config.yml' ) ) { die("missing config.yml\n") ; };
        $this->makeconfig = spyc_load_file('config.yml');
        $this->filePath = $this->makeconfig['path'];
        $this->process() ;
    }

    public function process() {
        $this->httpandcliRouting();
    }
    // init pages data

    protected function httpandcliRouting() {
        global $argv ;

        if ( count($argv) > 1  ) {      // create single pages from cli input
            array_shift($argv);         // remove script name
            $this->createPage( $argv[0] );       // create single pages from webeditor

        } else if (  isset( $_POST["sourcepath"] )  ) { // writes single pages from webeditor

            $sourcepath = '../' . preventDirectoryTraversal( $_POST["sourcepath"] );

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
    
    // create page    
    public function createPage($sourcepath) { // protected // e.g. my.page.md

            // splitfilenames TODO function
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


            $ymlMD = splitYamlMD( $sourcepath, $this->makeconfig['ymlseparator'] ) ;

            // collectMeta TODO function
            // read page config (template, meta, etc) from file, directory or mainconf
            $pageMeta = $this->makeconfig ;                     // write general config

            if ( file_exists($directoriesConf = $directoriesName . '/config.yml' ) ) { // overwrite with directory config
                $pageMeta = array_merge( $pageMeta , spyc_load_file( file_get_contents($directoriesConf) ) ) ;
            }
            if ( isset($ymlMD[1]) ) {  // overwrite with page config
                $pageMetaPage = spyc_load_file( $ymlMD[1] ) ;
                $pageMeta = array_merge( $pageMeta , $pageMetaPage ) ;
            }
            if ( !isset( $pageMetaPage['pagetitle'] ) ) {  // use first markdown heading as title if not in pageconfig
                 $pageMeta['pagetitle'] = getHtmltitleMD( $ymlMD[0] );
            }

            $this->tmplData['meta'] = $pageMeta;




            $page = array();

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
                    $pageMeta['pagetitle'] = $lemma ;               // use lemma, there is no meta
                break;
            }


            $page['filePath'] = $this->filePath['html'] . $lemma . $this->makeconfig['htmlextension']; // TODO fill inn $directoriesName
            $page['lemma'] = $lemma ;
            $page['sourcepath'] = substr( $sourcepath , 3 ) ; // remove leading "../"

            $page['pagedurable'] = Markdown( file_get_contents($pageMeta['pagedurable']) ); // TODO md switching




            // <!-- more --> cutter //~ TODO move to outermarkdown
            //~ $more = explode('<!--more-->', $item['content']);
            //~ if (count($more) >= 2) {
                //~ $item['more'] = $more[0];
            //~ } else {
                //~ $item['more'] = false;
            //~ }



            // merge config, template and content
            $this->tmplData['page'] = $page;




            Mustache_Autoloader::register();
            // use .html instead of .mustache for default template extension
            $mustacheopt =  array('extension' => $pageMeta['htmlextension']);
            $mustache = new Mustache_Engine(array(
                'loader' => new Mustache_Loader_FilesystemLoader( $this->filePath['template'] , $mustacheopt),
            ));

            $mustachecontent = $mustache->render($pageMeta['template'], $this->tmplData );
            file_put_contents( $page['filePath'], $mustachecontent) ? success( $page['filePath'] . ' ' . $pageMeta['pagetitle'] ) : error( $page['filePath'] );
    }
}
$site = new MakeSite();
?>
