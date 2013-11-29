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
    protected $config; // TODO ?
    protected $filePath;
    protected $source;

    public function __construct() {
        if( !file_exists( 'config.yml' ) ) { die("missing config.yml\n") ; };
        $this->makeconfig = spyc_load_file('config.yml');
        $this->filePath = $this->makeconfig['path'];
        $this->httpandcliRouting();
    }

    public function createPage($sourcepath) {
        $this->source = $this->sourcepath($sourcepath);
        $this->meta = $this->collectMeta();
        $this->content = $this->buildContent();
        $this->buildHtml();
    }

    protected function httpandcliRouting() {
        global $argv ;

        if ( count($argv) > 1  ) {      // create single pages from cli input
            array_shift($argv);         // remove script name
            $this->createPage( $argv[0] );       // create single pages from webeditor

        } else if (  isset( $_POST["sourcepath"] )  ) { // writes single pages from webeditor

            $sourcepath = '../' . preventDirectoryTraversal( $_POST["sourcepath"] );

            if ( isset ( $_POST["content"] )  ) { 
                file_put_contents( $sourcepath , $_POST["content"] ) ? success( $sourcepath ) : error( $sourcepath ) ;
            }

            $this->createPage( $sourcepath );

        } else {
            $sourcedirrecursive = new RecursiveDirectoryIterator( $this->filePath['source'] );
            foreach (new RecursiveIteratorIterator($sourcedirrecursive) as $sourcepath => $file) { // ? diffrenc $file  vs $sourcepath
                if ( is_dir( $sourcepath ) ) {                             // dont parse directories
                    //~ return ;
                } else {
                    $this->createPage($sourcepath);
                }
            }
        }
    }
    
    public function sourcepath($sourcepath) { // protected ? TODO

            $source['path'] = $sourcepath ;

            $directoriesName = explode('/', $sourcepath ) ;
            $source['filename'] = array_pop( $directoriesName ) ;                   // e.g. my.page.md
            $source['directoriesName'] = implode('/', $directoriesName ) ;          // e.g ../_source/mysubdir/

            $filenamewithExtension = explode('.', $source['filename'] ) ;
            $source['filenameExtension'] = array_pop( $filenamewithExtension ) ;    // remove fileextension e.g. md

            $source['lemma'] = implode('.', $filenamewithExtension ) ;              // e.g. my.page


            $source['content'] = splitYamlMD( $source['path'] , $this->makeconfig['ymlseparator'] ) ; // TODO external function readSource

            $source['htmlPath'] = $this->filePath['html'] . $source['lemma'] . $this->makeconfig['htmlextension']; // TODO fill inn $directoriesName
            $source['websourcepath'] = substr( $source['path'] , 3 ) ;        // remove leading "../"

            return $source ;

    }

    public function collectMeta() { // read page config (template, meta, etc) from file, directory or mainconf

            $meta = $this->makeconfig ;                     // write general config

            if ( file_exists($directoriesConf = $this->source['directoriesName'] . '/config.yml' ) ) { // overwrite with directory config
                $meta = array_merge( $meta , spyc_load_file( file_get_contents($directoriesConf) ) ) ;
            }
            if ( isset( $this->source['content'][1]) ) {  // overwrite with page config
                $metaPage = spyc_load_file( $this->source['content'][1] ) ;
                $meta = array_merge( $meta , $metaPage ) ;
            }
            if ( !isset( $metaPage['pagetitle'] ) ) {  // use first markdown heading as title if not in pageconfig
                 $meta['pagetitle'] = getHtmltitleMD( $this->source['content'][0] );
            }

            return $meta ;

    }
    public function buildContent() {

            // file parse handling
            switch ( $this->source['filenameExtension'] ) {
                case ("md"):
                    $content['html'] = Markdown( $this->source['content'][0] ) ;
                break;
                case ("html"):
                    $content['html'] = $this->source['content'][0] ;
                break;
                default:    // css js yaml txt etc
                    $content['html'] =  nl2br( $this->source['content'][0] ) ;
                    $this->meta['pagetitle'] = $this->source['lemma'] ;               // use lemma, there is no meta
                break;
            }

            $content['pagedurable'] = Markdown( file_get_contents( $this->meta['pagedurable']) ); // TODO md switching

            return $content ;
    }
    public function buildHtml() {

            $this->tmplData = array_merge( $this->source, $this->meta, $this->content ) ; // TODO not overwrite?
            //~ var_dump( $this->content );

            Mustache_Autoloader::register();
            // use .html instead of .mustache for default template extension
            $mustacheopt =  array('extension' => $this->meta['htmlextension']); // TODO Check other array
            $mustache = new Mustache_Engine(array(
                'loader' => new Mustache_Loader_FilesystemLoader( $this->filePath['template'] , $mustacheopt),
            ));
            $mustachecontent = $mustache->render($this->meta['template'], $this->tmplData );
            file_put_contents( $this->source['htmlPath'], $mustachecontent) ? success( $this->source['htmlPath'] . ' ' . $this->meta['pagetitle'] ) : error( $this->source['htmlPath'] );
    }
}
$site = new MakeSite();
?>
