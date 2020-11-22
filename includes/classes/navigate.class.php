<?php

class Navigate
{
    private $NavigateName;
    private $NavigateAuto = true;
    private $_PHP_FILE_Name = array();
    private $_HTML_FILE_Name = array();
    private $_CSS_FILE_Name = array();
    private $_JS_FILE_Name = array();
    private $Path;
    private $HoldingData = array();
    
    public function __construct($NavigateName, $Path)
    {
        $this->newPage($NavigateName, $Path);
    }
    
    public function newPage($NavigateName, $Path)
    {
        if(array_key_exists($NavigateName, $GLOBALS['NavigatePages']))
        {
            $this->NavigateName = $NavigateName;
            $NavigateArray = $GLOBALS['NavigatePages'][$NavigateName];
            //print_r($NavigateArray);
            if(array_key_exists('php', $NavigateArray))
                $this->_PHP_FILE_Name = $NavigateArray['php'];
            if(array_key_exists('html', $NavigateArray))
                $this->_HTML_FILE_Name = $NavigateArray['html'];
            if(array_key_exists('css', $NavigateArray))
                $this->_CSS_FILE_Name = $NavigateArray['css'];
            if(array_key_exists('js', $NavigateArray))
                $this->_JS_FILE_Name = $NavigateArray['js'];
            $this->Path = $Path;
            $this->HoldingData = $GLOBALS['Navigte_Global_array'];
        }
        else
        {
            header('Location: 404.php');
            die();
        }
    }
    
    public function setNavigateAuto($flag)
    {
        $this->NavigateAuto = $flag;
    }
    
    public function getPages()
    {
        return array('php' => $this->getPHPFiles(),
                    'html' => $this->getHTMLFiles(),
                    'css' => $this->getCSSFiles(),
                    'js' => $this->getJSFiles()
        );
    }
    
    
    public function getFilesByType($TypeName, $RealPath = '')
    {
        $VaribleTypeName = '_'.strtoupper($TypeName).'_FILE_Name';
        $folderName = strtolower($TypeName);
        $ListFiles = array();
        
        for($i = 0,$size = sizeof($this->{$VaribleTypeName});$i < $size;$i++)
        {
            $PathFile = $this->Path.$folderName.'/'.$this->{$VaribleTypeName}[$i].'.'.$folderName;
            $FileName = $RealPath.$this->{$VaribleTypeName}[$i].'.'.$folderName;
            if(file_exists($PathFile))
               $ListFiles[] =  $FileName;
        }
        return $ListFiles;
    }
    
    public function SetData($array)
    {
        foreach($array as $key => $value)
            $this->HoldingData[$key] = $value;
    }
    
    public function GetData($key)
    {
        if(array_key_exists($key, $this->HoldingData))
            return $this->HoldingData[$key];
        return false;
    }
    
    public function NavigateAuto($twig)
    {
        if(!$this->NavigateAuto)
            return false;
            
        $this->Navigate($twig);
        return true;
    }
    
    public function Navigate($twig)
    {
        $Page = $this->getFilesByType('html');
        if(!is_array($Page) && sizeof($Page) <= 0)
            return false;
        
        $GunZ = $GLOBALS['GunZ'];
        
        $this->HoldingData['css_links'] = $this->getFilesByType('css');
        $this->HoldingData['js_links'] = $this->getFilesByType('js');
        $this->HoldingData['top_players'] = top5players($GunZ);
        $this->HoldingData['top_clans'] = top5clans($GunZ);
        $this->HoldingData['Best_of_EXP'] = BestOf($GunZ,'EXP');
        $this->HoldingData['Best_of_KDR'] = BestOf($GunZ,'KDR');
        $this->HoldingData['Best_of_KILL'] = BestOf($GunZ,'KILL');
        $this->HoldingData['Best_of_DEATH'] = BestOf($GunZ,'DEATH');
        $this->HoldingData['Best_of_PLAYTIME'] = BestOf($GunZ,'PLAYTIME');
        $this->HoldingData['ServerData'] = getServerData($GunZ, SERVER_ID);
        $this->HoldingData['UserConnected'] = $GLOBALS['Session']->IsConnected();
        
        echo $twig->render($Page[0], $this->HoldingData);
        $this->setNavigateAuto(false);
        return true;
    }
}