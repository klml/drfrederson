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

        // concat global and local configuration
        $globalconfig = realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'config.global.yml' ) ;
        $localconfig  = realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'config.local.yml'  ) ;

        $this->makeconfig = spyc_load_file( $globalconfig ) ;
        if( file_exists( $localconfig ) ) {
            $this->makeconfig = array_merge( $this->makeconfig, spyc_load_file( $localconfig ) );
        };

        $this->defineDirectories();
        $this->httpandcliRouting();
    }

    public function createPage($sourcepath) {
        $this->source = $this->contentMetaPathFromSource($sourcepath);
        $this->meta = $this->collectMeta();

        // check whether file should be published
        if ( !$this->meta['publish'] ) {
            return ;
        }

        $this->content = $this->buildContent();
        $this->buildHTML();
        $this->buildJSON();
    }

    public function defineDirectories() {

        // define webroot
        $this->directories['webroot'] = $webroot  = chop( realpath('./') ,  dirname($_SERVER['PHP_SELF']) ) . DIRECTORY_SEPARATOR ;

        // add webroot to each directory from configuration
        foreach( $this->makeconfig['directory'] as $makeconfigdirectorykey => $makeconfigdirectoryvalue ) {
            $this->directories[$makeconfigdirectorykey] = $webroot . $makeconfigdirectoryvalue . DIRECTORY_SEPARATOR ;
        }
    }

    protected function httpandcliRouting() {
        global $argv ;

        // create single pages from cli input
        if ( count($argv) > 1  ) {
            array_shift($argv);         // remove script name
            $this->buildSourcepath( $argv[0] ) ;

        // writes single pages from webeditor
        } else if (  isset( $_POST["drf_sourcepath"] )  ) {

            // TODO deprecated
            if ( isset ( $_POST["content"] )  ) {
                file_put_contents( $this->directories['webroot'] . $_POST["drf_sourcepath"]  , $_POST["content"] ) ? success( $_POST["drf_sourcepath"] ) : error( $_POST["drf_sourcepath"] ) ;
            }
            $this->buildSourcepath( $_POST["drf_sourcepath"] );

        // create all pages, if no dedicated page is named be url or cli
        } else {
            $this->allPages();
        }
    }

    protected function buildSourcepath( $sourcepathrelative ) {

            // add webroot to sourcepath coming from GET or cli e.g. source/foobarpage.md
            $sourcepathabsolute = realpath( $this->directories['webroot'] . $sourcepathrelative ) ;

            // if file does not exit, realpath gives false
            // and prevent Directory traversal attack
            // from http://stackoverflow.com/questions/4205141/preventing-directory-traversal-in-php-but-allowing-paths/4205182#4205182
            // and sourcepath has to start with sourcedirectory from config
            // like /_drf/make.php?drf_sourcepath=source/../_drf/make.php

            if ($sourcepathabsolute === false || strpos( $sourcepathabsolute, $this->directories['source'] ) !== 0) {
                // illegal directory or directory traversal
                die( $sourcepathrelative . ' is a illegal source directory' );
            }

            // check if edited page is a area 
            // after webediting an area like navgation or sidebar
            if ( strpos($sourcepathabsolute, $this->directories['area']) === 0  ) {

                $this->allPages();
                return ; 
            }
            $this->createPage( $sourcepathabsolute );
    }

    protected function allPages( ) {

        $sourcedir = $this->directories['source'] ;
        $exclude = array();
        array_push($exclude, $this->makeconfig['sourceexclude'] );

        $filter = function ($file, $key, $iterator) use ($exclude) {
            if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
                return true;
            }
            return $file->isFile();
        };

        $innerIterator = new RecursiveDirectoryIterator(
            $sourcedir,
            RecursiveDirectoryIterator::SKIP_DOTS
        );
        $iterator = new RecursiveIteratorIterator(
            new RecursiveCallbackFilterIterator($innerIterator, $filter)
        );

        foreach ($iterator as $pathname => $fileInfo) {
                $this->createPage($fileInfo);
        }
    }

    // get content and meta from sourcefile
    // and prepare filepath for web (namespace, seperator, fileextension)
    public function contentMetaPathFromSource($sourcepath) {

            $source['path'] = $sourcepath ;
            $source['pathinfo'] = pathinfo( $sourcepath );

            $source['content'] = splitYamlProse( file_get_contents($source['path']) , $this->makeconfig['metaseparator'] ) ;

            // remove source base directory eg 'source/'
            $source['namespaceslash'] = $namespace = substr( dirname( $sourcepath ) , strlen( $this->directories['source'] ) ) ;

            // change slash to namespaceseparator
            $namespace = str_replace( DIRECTORY_SEPARATOR , $this->makeconfig['namespaceseparator'], $namespace ) ;

            // add trailing namespaceseparator
            if ( $namespace != "" ) {
                $namespace .= $this->makeconfig['namespaceseparator'] ;
                $source['namespaceslash'] .= DIRECTORY_SEPARATOR ;
            }

            $source['dirlimb'] = $dirlimb = $namespace . $source['pathinfo']['filename'] ;
            $source['htmlPath'] =   $this->directories['html'] . $dirlimb . $this->makeconfig['htmlextension'];

            if( isset($this->directories['json']) ) {
                $source['jsonPath'] =   $this->directories['json'] . $dirlimb . '.json';
            };


            // remove webroot from sourcepath for frontend usage
            $source['websourcepath'] =  explode( $this->directories['webroot'] , $sourcepath )[1] ;

            return $source ;

    }

    // read page config (template, meta, etc) from file, directory or mainconf
    public function collectMeta() {

            $meta = array();
            $meta['template'] = $this->makeconfig['defaulttemplate'] ;
            $meta['sourceextension'] = $this->makeconfig['sourceextension'] ;

            // use every file in area-dir as area
            if ( is_dir($sourceDirectoriesArea = $this->directories['area'] ) ) { 
                $areadirrecursive = new RecursiveDirectoryIterator( $sourceDirectoriesArea );
                foreach (new RecursiveIteratorIterator($areadirrecursive) as $areapath => $areaname) {

                    // dont parse directories
                    if ( !is_dir( $areapath ) ) {
                        $areapathinfo  = pathinfo($areaname) ;
                        $meta["area"][ $areapathinfo['filename'] ] = $areapath ;
                    }
                }
            }

            // overwrite with general source config
            if ( file_exists($sourceDirectoriesConf = $this->directories['source'] . '/meta.yml' ) ) { 
                $meta = array_merge( $meta , spyc_load_file( file_get_contents($sourceDirectoriesConf) ) ) ;
            }

            // overwrite with directory config
            if ( file_exists($directoriesConf = $this->source['pathinfo']['dirname'] . '/meta.yml' ) ) {
                $meta = array_merge( $meta , spyc_load_file( file_get_contents($directoriesConf) ) ) ;
            }

            // overwrite with page config
            if ( isset( $this->source['content']['meta']) ) {
                $metaPage = spyc_load_file( $this->source['content']['meta'] ) ;
                $meta = array_merge( $meta , $metaPage ) ;
            }

            // use first markdown heading as title if not in pageconfig
            if ( !isset( $metaPage['pagetitle'] ) ) { 
                $meta['pagetitle'] = getHtmltitleMD( $this->source['content']['prose'] );
            }

            return $meta ;

    }
    public function buildContent() {

            $content = array();

            // parse content depending on fileextension
            switch ( $this->source['pathinfo']['extension'] ) {
                case ("md"):
                    $content['main'] = Markdown( $this->source['content']['prose'] ) ;
                    // internal wikistyle links [[ ]]
                    $content['main'] = wikistylelinks($content['main']) ;
                break;
                case ("html"):
                    $content['main'] = $this->source['content']['prose'] ;
                break;
                // css js yaml txt etc
                default:
                    $content['main'] =  nl2br( $this->source['content']['prose'] ) ;
                    $this->meta['pagetitle'] = $this->source['pathinfo']['filename'] ;               // use lemma, there is no meta
                break;
            }

            // get content for all areas to use it in template
            if( !empty( $this->meta["area"] ) ) {

                // overwrite meta area/sidebar with sidebar (without)
                // to overwrite sidebar in every page or namespace (aka directory)
                // TODO generic solution
                if( !empty( $this->meta["sidebar"] ) ) {
                    $this->meta["area"]["sidebar"] =  $this->directories["area"] . $this->meta["sidebar"] ;
                }

                // render area source with markdown und wikilinks [[page]]
                foreach( $this->meta["area"] as $areaname => $area ) {
                   if ( $area != '' ) $content[ $areaname ] = wikistylelinks( Markdown( file_get_contents( $area ) ) ); // TODO md switching
                }
            }

            return $content ;
    }
    public function buildHTML() {

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

            file_put_contents( $this->source['htmlPath'], $mustachecontent) ? success( $this->source['websourcepath'] . ' ' . $this->meta['pagetitle'] ) : error( $this->source['websourcepath'] );
    }
    public function buildJSON() {

            if( isset( $this->source['jsonPath'] ) ) {
                $jsoncontent = $this->meta ;
                $jsoncontent['prose_html'] =  $this->content  ;
                $jsoncontent = json_encode( $jsoncontent );
    
                file_put_contents( $this->source['jsonPath'], $jsoncontent) ? success( $this->source['websourcepath'] ) : error( $this->source['websourcepath'] );
            }
    }
    
}
$site = new MakeSite();
?>
