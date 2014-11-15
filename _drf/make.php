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
        $this->makeconfig = spyc_load_file('config.global.yml');
        if( file_exists( 'config.local.yml' ) ) { 
            $this->makeconfig = array_merge( $this->makeconfig, spyc_load_file('config.local.yml') );
        };
        $this->directories = $this->makeconfig['directory'];
        $this->httpandcliRouting();
    }

    public function createPage($sourcepath) {
        $this->source = $this->source($sourcepath);
        $this->meta = $this->collectMeta();
        $this->content = $this->buildContent();
        $this->buildHTML();
        $this->buildJSON();
    }

    protected function httpandcliRouting() {
        global $argv ;

        // create single pages from cli input
        if ( count($argv) > 1  ) {
            array_shift($argv);         // remove script name
            $this->createPage( $argv[0] );

        // writes single pages from webeditor
        } else if (  isset( $_POST["drf_sourcepath"] )  ) {

            $sourcepath = '../' . preventDirectoryTraversal( $_POST["drf_sourcepath"] );

            // sourcepath starts not with sourcedir from config
            if ( $sourcepath == substr( $sourcepath , 0, strlen( $this->directories['source'] ) ) ) { 
                return ;
            } ;

            if ( isset ( $_POST["content"] )  ) { 
                file_put_contents( $sourcepath , $_POST["content"] ) ? success( $sourcepath ) : error( $sourcepath ) ;
            }

            // after webediting an area like navgation or sidebar
            if ( in_array( $sourcepath , $this->makeconfig['area'] ) ) {
                $this->allPages();
                return ; 
            }
            $this->createPage( $sourcepath );

        } else {
            $this->allPages( $this->directories['source'] );
        }

    }
    protected function allPages( $sourcedir ) {

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

    // processing all sources
    public function source($sourcepath) {

            $source['path'] = $sourcepath ;
            $source['pathinfo'] = pathinfo( $sourcepath );

            $source['content'] = splitYamlProse( $source['path'] , $this->makeconfig['metaseparator'] ) ;

            // remove source base directory
            $namespace = substr( $source['pathinfo']['dirname'] , strlen( $this->directories['source'] ) ) ;

            // change slash to namespaceseparator
            $namespace = str_replace( DIRECTORY_SEPARATOR , $this->makeconfig['namespaceseparator'], $namespace ) ;

            // trailing namespaceseparator
            if ( $namespace != "" ) {
                $namespace .= $this->makeconfig['namespaceseparator'] ;
            }
            
            $source['dirlimb'] = $dirlimb = $namespace . $source['pathinfo']['filename'] ;
            $source['htmlPath'] =   $this->directories['html'] . $dirlimb . $this->makeconfig['htmlextension'];

            if( isset($this->directories['json']) ) {
                $source['jsonPath'] =   $this->directories['json'] . $dirlimb . '.json';
            };

            // remove leading "../"
            $source['websourcepath'] = substr( $source['path'] , 3 ) ;

            return $source ;

    }

    // read page config (template, meta, etc) from file, directory or mainconf
    public function collectMeta() {

            $meta = array();
            $meta['template'] = $this->makeconfig['defaulttemplate'] ;

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

            // file parse handling
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

            // get content for area
            if( !empty( $this->meta["area"] ) ) {

                if( !empty( $this->meta["sidebar"] ) ) { // overwrite sidebar TODO generic solution
                    $this->meta["area"]["sidebar"] =  $this->directories["area"] . $this->meta["sidebar"] ;
                }

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

            file_put_contents( $this->source['htmlPath'], $mustachecontent) ? success( $this->source['htmlPath'] . ' ' . $this->meta['pagetitle'] ) : error( $this->source['htmlPath'] );
    }
    public function buildJSON() {

            if( isset( $this->source['jsonPath'] ) ) {
                $jsoncontent = $this->meta ;
                $jsoncontent['prose_html'] =  $this->content  ;
                $jsoncontent = json_encode( $jsoncontent );
    
                file_put_contents( $this->source['jsonPath'], $jsoncontent) ? success( $this->source['jsonPath'] ) : error( $this->source['jsonPath'] );
            }
    }
    
}
$site = new MakeSite();
?>
