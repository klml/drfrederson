<?php
/*
 * @name make.php
 * @class WriteFile
 * @description generate a static site
 * @author klml based on https://github.com/lonescript/php-site-maker
 */

require_once 'lib/spyc/Spyc.php';


// needede ??? TODo
require_once 'lib/Tools.php';

class WriteSource {
    protected $directories;
    protected $source;

    public function __construct() {
        // Dublicate TODO
        
        // concat global and local configuration
        $globalconfig = realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'config.global.yml' ) ;
        $localconfig  = realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'config.local.yml'  ) ;

        $this->makeconfig = spyc_load_file( $globalconfig ) ;
        if( file_exists( $localconfig ) ) {
            $this->makeconfig = array_merge( $this->makeconfig, spyc_load_file( $localconfig ) );
        };

        $this->directories['webroot'] = chop( realpath('./') ,  dirname($_SERVER['PHP_SELF']) ) . DIRECTORY_SEPARATOR ;

        if( !$this->makeconfig['webwrite'] ) {
            die('write is not permitted');
        }
        $this->WriteFile();

    }
    protected function WriteFile() {
            $sourcepath = $_POST["drf_sourcepath"] ;
            if ( isset ( $_POST["content"] )  ) {
                file_put_contents( $this->directories['webroot'] . $sourcepath , $_POST["content"] ) ;
            }
    }
}
$site = new WriteSource();
?>
