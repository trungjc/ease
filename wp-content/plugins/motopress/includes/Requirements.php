<?php
class Requirements {
    private $curl;
    private $minjQueryVer = '1.7';
    private $minjQueryUIVer = '1.8';

    public function __construct() {
        @ini_set('display_errors', 1);
        @ini_set('magic_quotes_gpc', 0);
        @ini_set('magic_quotes_runtime', 0);
        @ini_set('magic_quotes_sybase', 0);
        @ini_set('allow_url_fopen', 1);

        $this->curl = $this->isCurlInstalled();
    }

    private function isCurlInstalled() {
        if (in_array('curl', get_loaded_extensions()) && function_exists('curl_init')) {
            return true;
        }
        return false;
    }

    public function getCurl() {
        return $this->curl;
    }

    public function getMinjQueryVer() {
        return $this->minjQueryVer;
    }

    public function getMinjQueryUIVer() {
        return $this->minjQueryUIVer;
    }
}