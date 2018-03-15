<?php
class SiteHandler {
    private $appRoot;
    private $xmlData;

    function __construct($url){
        if(!file_exists("applications.xml")){
            die("A critical error occured. Try again soon or contact the system administrator.");
        }
        $xmlData = simplexml_load_file("applications.xml");
        #echo $xmlData->application[0]->url.'<br>'.$xmlData->application[0]->appfolder;
        #print_r($this->xmlData);
        foreach($xmlData->children() as $application){
            if($application->url == $url){
                $this->appRoot = $application->appfolder;
            }
        }
    }

    public function getAppfolder(){
        return strtolower($this->appRoot);
    }
}