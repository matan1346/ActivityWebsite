<?php

class Language
{
    private $LangType;
    private $langXPath;
    private $LangXmlDoc;
    private static $NO_MSG = 'NO_MSG';
    
    public function __construct($lang = null)
    {
        if(is_null($lang) && isset($_SESSION['LANG']))
            $lang = $_SESSION['LANG'];
            
        if(is_null($lang))
            $lang = 'en';
            
        $this->LangType = $lang;
            
        $xml_path = 'public/lang/'.$lang.'.xml';
        if(!is_file($xml_path))
            return false;
            
        $this->LangXmlDoc = new DOMDocument();
        $this->LangXmlDoc->load($xml_path);
        $this->langXPath = new DOMXPath($this->LangXmlDoc);
    }
    
    public function getString($strName, $strCat = null, $retDefault = true) {
        $searchQuery = '/str[@name=\'' . $strName . '\']';
        if($strCat) {
            $searchQuery = '/' . $strCat . $searchQuery;
        } $searchQuery = '/' . $searchQuery;
        
        $resNodes = $this->langXPath->query($searchQuery);
        if($resNodes->length == 0) {
            if($retDefault) {
                return ($strName != 'STR_NOT_FOUND' ? $this->getString('STR_NOT_FOUND', 'error') : self::$FATAL_NO_STR);
            } else {
                return false;
            }
        }
            
        return $resNodes->item(0)->nodeValue;
    }
        
    public function directionHTML() {
        $directionStr = $this->getString('DIRECTION', 'langspec');
        
        return '
            <style>
                * {
                    direction: ' . $directionStr . ';
                }
            </style>
        ';
    }
    
    public function getAlignText() {
        return $this->getString('TEXT_ALIGN', 'langspec');
    }
        
    public function panelHTML() {
        global $_CONFIG;
        
        $retHTML = '';
        foreach($_CONFIG['LANG'] as $langKey => $langXml) {
            if($langKey == 'default') continue;
            
            $retHTML .= '<a href="?lang=' . $langKey . '"><img alt="" src="/img/flags/' . $langKey . '.png" /></a>';
        }
        
        return $retHTML;
    }
    
}