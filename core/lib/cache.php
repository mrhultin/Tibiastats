<?php

class Cache {
    private $folder = "/cache";
    private $cacheFile;
    private $cacheLifeTime;
    private $cacheActive;
    function __construct(){
        /* Set the cacheFile name to pagename */
        $this->cacheFile = $this->setCacheFile();
        /* Set RefreshTimeStamp */
        $this->refreshTimestamp = $this->createRefreshTimestamp();
    }

    public function startCache(){
        $this->cacheActive = true;

        /* Check if cache exists and it's not to old, if there are old files we empty it. */
        if(file_exists($this->cacheFile) and filemtime($this->cacheFile) <= $this->refreshTimestamp){

            /* File exists and isn't to old */
            return false;
        } else {
            /* Check if file exists, if so clean it out */
            if(file_exists(($this->cacheFile))){
                $file = fopen($this->cacheFile, "a");
                if($file !== false){
                    ftruncate($file, 0);
                    fclose($file);
                }
            }
            /* Start output buffer */
            ob_start();
            return true;
        }
    }

    public function endCache(){
        echo 'Ending cache here';
        if(!$this->saveCache()){
            echo $this->saveCache();
            die("Cache error!");
        }
    }

    public function getCachedFile(){
        return $this->cacheFile;
    }
    /* Save the cache to file */
    private function saveCache(){
        /* We can't save if we're not caching */
        if($this->cacheActive){
            $file = fopen($this->cacheFile, "w");

            fwrite($file, ob_get_contents());
            #file_put_contents($this->cacheFile, ob_get_contents());
            /* Data was sucessfully saved */
            #ob_end_flush();
            return true;
        } else {
            return false;
        }
    }
    /* Since updates are done between 04 and 06 AM CET we want to get the correct file and not risk creating a incomplete one */
    private function createRefreshTimestamp(){
        $timestamp = $updateDate = mktime(23, 0, 0, date("m"), date("d"), date("Y"));
        if(intval(date("G")) < 6){
            $timestamp = $timestamp - 86400; # We still want to serve yesterdays file since data collection isn't finished yet.
        }
        return (int)$timestamp;
    }

    private function setCacheFile(){
        $string = $this->folder.$_SERVER['REQUEST_URI'];
        if(substr($string, -1) == '/') {
            $string = substr($string, 0, -1);
        }
        str_replace("/", "\\", $string);
        return ROOT.$string.'.php';
    }
}