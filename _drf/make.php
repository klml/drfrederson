<?php
/*
 * @name make.php
 * @class MakeSite
 * @description generate a static site
 * @author klml based on https://github.com/lonescript/php-site-maker
 */

require_once 'lib/Markdown.php';
require_once 'lib/spyc/Spyc.php';
require_once 'lib/mustache/src/Mustache/Autoloader.php';

require_once 'lib/Tools.php';

class MakeSite {
    protected $directories;
    protected $source;

    public function __construct() {
        if( !file_exists( 'config.yml' ) ) { die("missing config.yml\n") ; };
        $this->makeconfig = spyc_load_file('config.yml');
        $this->directories = $this->makeconfig['directory'];
        $this->httpandcliRouting();
    }

    public function createPage($sourcepath) {
        $this->source = $this->source($sourcepath);
        $this->meta = $this->collectMeta();
        $this->content = $this->buildContent();
        $this->buildHtml();
    }

    protected function httpandcliRouting() {
        global $argv ;

        if ( count($argv) > 1  ) {      // create single pages from cli input
            array_shift($argv);         // remove script name
            $this->createPage( $argv[0] );

        } else if (  isset( $_POST["drf_sourcepath"] )  ) { // writes single pages from webeditor

            $sourcepath = '../' . preventDirectoryTraversal( $_POST["drf_sourcepath"] );

            if ( $sourcepath == substr( $sourcepath , 0, strlen( $this->directories['source'] ) ) ) { // sourcepath starts not with sourcedir from config
                return ;
            } ;

            if ( isset ( $_POST["content"] )  ) { 
                file_put_contents( $sourcepath , $_POST["content"] ) ? success( $sourcepath ) : error( $sourcepath ) ;
            }
            if ( in_array( $sourcepath , $this->makeconfig['area'] ) ) { // after webediting an area like navgation or sidebar
                $this->allPages();
                return ; 
            }
            $this->createPage( $sourcepath );

        } else {
            $this->allPages();
        }

    }
    protected function allPages() {
        $sourcedirrecursive = new RecursiveDirectoryIterator( $this->directories['source'] );
        foreach (new RecursiveIteratorIterator($sourcedirrecursive) as $sourcepath => $file) { // TODO differece $file  vs $sourcepath
            if ( is_dir( $sourcepath ) ) {                                                      // dont parse directories
                //~ return ;
            } else {
                $this->createPage($sourcepath);
            }
        }
    }

    public function source($sourcepath) { // processing all sources

            $source['path'] = $sourcepath ;
            $source['pathinfo'] = pathinfo( $sourcepath );

            $source['content'] = splitYamlProse( $source['path'] , $this->makeconfig['metaseparator'] ) ;

            $namespace = substr( $source['pathinfo']['dirname'] , strlen( $this->directories['source'] ) )  ;   // remove source base directory
            $namespace = str_replace("/", $this->makeconfig['namespaceseparator'], $namespace ) ;               //  change slash to namespaceseparator
            if ( $namespace != "" ) {
                $namespace .= $this->makeconfig['namespaceseparator'] ; // trailing namespaceseparator
            }
            $source['htmlPath'] =   $this->directories['html'] . $namespace . $source['pathinfo']['filename'] . $this->makeconfig['htmlextension'];

            $source['websourcepath'] = substr( $source['path'] , 3 ) ;        // remove leading "../"

            return $source ;

    }

    public function collectMeta() { // read page config (template, meta, etc) from file, directory or mainconf

            $meta = array();

            // use every file in area-dir as area
            $areadirrecursive = new RecursiveDirectoryIterator( $this->directories['area'] );
            foreach (new RecursiveIteratorIterator($areadirrecursive) as $areapath => $areaname) {

                if ( is_dir( $areapath ) ) {                                     // dont parse directories

                } else {
                    $areapathinfo  = pathinfo($areaname) ;
                    $meta["area"][ $areapathinfo['filename'] ] = $areapath ;
                }
            }

            if ( file_exists($sourceDirectoriesConf = $this->directories['source'] . '/meta.yml' ) ) { // overwrite with general source config
                $meta = array_merge( $meta , spyc_load_file( file_get_contents($sourceDirectoriesConf) ) ) ;
            }
            if ( file_exists($directoriesConf = $this->source['pathinfo']['dirname'] . '/meta.yml' ) ) { // overwrite with directory config
                $meta = array_merge( $meta , spyc_load_file( file_get_contents($directoriesConf) ) ) ;
            }
            if ( isset( $this->source['content']['meta']) ) {  // overwrite with page config
                $metaPage = spyc_load_file( $this->source['content']['meta'] ) ;
                $meta = array_merge( $meta , $metaPage ) ;
            }
            if ( !isset( $metaPage['pagetitle'] ) ) {  // use first markdown heading as title if not in pageconfig
                $meta['pagetitle'] = getHtmltitleMD( $this->source['content']['prose'] );
            }

            return $meta ;

    }
    public function buildContent() {

            // file parse handling
            switch ( $this->source['pathinfo']['extension'] ) {
                case ("md"):
                    $content['main'] = Markdown( $this->source['content']['prose'] ) ;
                break;
                case ("html"):
                    $content['main'] = $this->source['content']['prose'] ;
                break;
                default:    // css js yaml txt etc
                    $content['main'] =  nl2br( $this->source['content']['prose'] ) ;
                    $this->meta['pagetitle'] = $this->source['pathinfo']['filename'] ;               // use lemma, there is no meta
                break;
            }

            foreach( $this->meta["area"] as $areaname => $area ) {
               if ( $area != '' ) $content[ $areaname ] = Markdown( file_get_contents( $area ) ); // TODO md switching
            }

            return $content ;
    }
    public function buildHtml() {

            $this->tmplData['source'] = $this->source ;
            $this->tmplData['meta'] = $this->meta ;
            $this->tmplData['content'] = $this->content ;

            Mustache_Autoloader::register();
            // use .html instead of .mustache for default template extension
            $mustacheopt =  array('extension' => $this->makeconfig['tplextension']);
            $mustache = new Mustache_Engine(array(
                'loader' => new Mustache_Loader_FilesystemLoader( $this->directories['template'] , $mustacheopt),
            ));
            $mustachecontent = $mustache->render($this->meta['template'], $this->tmplData );
            file_put_contents( $this->source['htmlPath'], $mustachecontent) ? success( $this->source['htmlPath'] . ' ' . $this->meta['pagetitle'] ) : error( $this->source['htmlPath'] );
    }
}
$site = new MakeSite();
?>
