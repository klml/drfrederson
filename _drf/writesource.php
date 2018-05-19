<?php
/*
 * @name writesource.php
 * @class WriteSource
 * @description write content from POST to file on server
 * @author klml
 */

require_once 'lib/spyc/Spyc.php';

class WriteSource {

    public function __construct() {
        // Duplicate TODO
        
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
            if ( isset ( $_POST["content"] ) &&  isset( $_POST["drf_sourcepath"] ) ) {
                file_put_contents( $this->directories['webroot'] . $_POST["drf_sourcepath"] , $_POST["content"] ) ;
            } else {
                die( "no POST-Value 'content' or/and 'drf_sourcepath' given." );
            }
    }
}
$site = new WriteSource();
?>
