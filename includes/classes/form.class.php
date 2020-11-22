<?php

class Form
{
    private $GunZ;
    private $Messages;
    private $_Fields;
    private $FieldsData = array();
    private $SystemMessage = array();
    private $NoErrorsFlag = true;
    
    
    public function __construct($GunZ)
    {
        $this->GunZ = $GunZ;
        $this->_Fields = $GLOBALS['Register_Global_Vars'];
        $this->Messages = $GLOBALS['Form_Errors'];
    }
    
    public function getField($key)
    {
        if(array_key_exists($key, $this->FieldsData))
            return $this->FieldsData[$key];
        return false;
    }
    
    public function setField($keyName, $keyType, $val)
    {
        if(array_key_exists($keyType, $this->_Fields))
        {
            $this->FieldsData[$keyName] = array('Type' => $keyType,'Value' => $val);
            return true;
        }
        return false;
    }
    
    public function setError($errorPath,$key,$data = array())
    {
        $ErrorKeys = explode('/', $errorPath);
        $Error = $this->Messages;
        for($i = 0,$size = sizeof($ErrorKeys);$i < $size;$i++)
            if(array_key_exists($ErrorKeys[$i], $Error))
                $Error = $Error[$ErrorKeys[$i]];
        if(!is_string($Error))
            $Error = '';
        
        $Start = '#{{ ';
        $End = ' }}#';
        
        $patterns = array();
        $replcement = array();
        if(is_array($data) && sizeof($data) > 0)
        {
            foreach($data as $k => $v)
            {
                $patterns[] = $Start.$k.$End;
                $replcement[] = $v;
            }
            $Error = preg_replace($patterns, $replcement, $Error);
        }
        
        
        $this->SystemMessage[$key][] = $Error;
    }
    
    public function setMessage($key, $val)
    {
       $this->SystemMessage[$key][] = $val; 
    }
    
    public function getSystemMessages()
    {
        return $this->SystemMessage;
    }
    
    public function getFieldsValues()
    {
        $myData = array();
        foreach($this->FieldsData as $key => $value)
            $myData[$key] = $value['Value'];
        
        return $myData;
    }
    
    public function IsNotEmpty($keysNotToCheck = array())
    {
        $flagNotEmpty = true;
        foreach($this->FieldsData as $key => $v)
        {
            if(!in_array($key, $keysNotToCheck))
                if(empty($v['Value']))
                {
                    $flagNotEmpty = false;
                    $this->setError('Fields/IsNotEmpty/Empty', $key, array('key' => $key));
                }
        }
        if(!$flagNotEmpty)
           $this->NoErrorsFlag = false;
        return $flagNotEmpty;
    }
    
    public function IsEquals($key1, $key2)
    {
        if(array_key_exists($key1, $this->FieldsData) && array_key_exists($key2, $this->FieldsData))
        {
            if(strcasecmp($this->FieldsData[$key1]['Value'], $this->FieldsData[$key2]['Value']) != 0)
            {
                $this->NoErrorsFlag = false;
                $this->setError('Fields/IsEquals/NotSame', $key2, array('key1' => $key1,'key2' => $key2));
                return false;
            }
            return true;
        }
        $this->NoErrorsFlag = false;
        $this->setError('Fields/NotFound', $key2, array('keys' => $key1.'` and `'.$key2));
        return false;
    }
    
    public function IsNumber($key)
    {
        if(array_key_exists($key, $this->FieldsData))
        {
            if(!is_numeric($this->FieldsData[$key]['Value']))
            {
                $this->NoErrorsFlag = false;
                $this->setError('Fields/IsNumber/NotNumber', $key, array('key' => $key));
                return false;
            }
            return true;
        }
        $this->NoErrorsFlag = false;
        $this->setError('Fields/NotFound', $key, array('key' => $key));
        return false;
    }
    
    public function IsValuesAllowed($keysNotToCheck = array())
    {
        $flagAllowed = true;
        foreach($this->FieldsData as $k => $v)
        {
            if(!in_array($k, $keysNotToCheck))
                if(!$this->IsAllowed($k))
                    $flagAllowed = false;
        }
        return $flagAllowed;
    }
    
    public function NoErrors()
    {
        return $this->NoErrorsFlag;
    }
    
    public function IsNumberBetween($key, $options, $array = false)
    {
        if(!is_array($array))
            $array = $this->FieldsData;
        if(array_key_exists($key, $array))
        {
            $this->FieldsData[$key]['Value'] = intval($this->FieldsData[$key]['Value']);
            $minF = array_key_exists('min', $options);
            $maxF = array_key_exists('max', $options);
            if($minF && $maxF && ($array[$key]['Value'] < $options['min'] || $array[$key]['Value'] > $options['max']))
            {
                $this->NoErrorsFlag = false;
                $this->setError('Fields/IsNumberBetween/NotBetween', $key, array('key' => $key,'min' => $options['min'],'max' => $options['max']));
                return false;
            }
            if($minF && $array[$key]['Value'] < $options['min'])
            {
                $this->NoErrorsFlag = false;
                $this->setError('Fields/IsNumberBetween/Max', $key, array('key' => $key,'max' => $options['max']));
                return false;
            }
            if($maxF && $array[$key]['Value'] > $options['max'])
            {
                $this->NoErrorsFlag = false;
                $this->setError('Fields/IsNumberBetween/Max', $key, array('key' => $key,'max' => $options['max']));
                return false;
            }
            return true;
        }
        $this->NoErrorsFlag = false;
        $this->setError('Fields/NotFound', $key, array('key' => $key));
        return false;
    }
    
    public function IsLengthBetween($key, $options, $array = false)
    {
        if(!is_array($array))
            $array = $this->FieldsData;
        if(array_key_exists($key, $array))
        {
            $length = mb_strlen($array[$key]['Value']);
            $minF = array_key_exists('min', $options);
            $maxF = array_key_exists('max', $options);
            if($minF && $maxF && ($length < $options['min'] || $length > $options['max']))
            {
                $this->NoErrorsFlag = false;
                $this->setError('Fields/IsLengthBetween/NotBetween', $key, array('key' => $key,'min' => $options['min'],'max' => $options['max']));
                return false;
            }
            if($minF && $length < $options['min'])
            {
                $this->NoErrorsFlag = false;
                $this->setError('Fields/IsLengthBetween/Min', $key, array('key' => $key,'min' => $options['min']));
                return false;
            }
            if($maxF && $length > $options['max'])
            {
                $this->NoErrorsFlag = false;
                $this->setError('Fields/IsLengthBetween/Max', $key, array('key' => $key,'max' => $options['max']));
                return false;
            }
            return true;
        }
        $this->NoErrorsFlag = false;
        $this->setError('Fields/NotFound', $key, array('key' => $key));
        return false;
    }
    
    public function IsAllowed($key, $array = false)
    {
        if(!is_array($array))
            $array = $this->FieldsData;
        if(array_key_exists($key, $array))
        {
            $regex = $this->_Fields[$this->FieldsData[$key]['Type']]['regex'];
            if(preg_match('#'.$regex['statement'].'#', $array[$key]['Value']))
            {
                $this->NoErrorsFlag = false;
                $this->setError('Fields/IsAllowed/NotAllowed', $key, array('key' => $key,'contains' => $regex['contains']));
                return false;
            }
            return true;
        }
        $this->NoErrorsFlag = false;
        $this->setError('Fields/NotFound', $key, array('key' => $key));
        return false;
    }
    
    public function IsExists($key)
    {
        $FieldsArray = $this->FieldsData;
        if(array_key_exists($key, $FieldsArray))
        {
            $query_str = 'SELECT TOP 1 1 FROM Account WHERE '.$this->_Fields[$FieldsArray[$key]['Type']]['DatabaseField'].' = :keyvalue';
            $query = $this->GunZ->prepare($query_str);
            $query->bindParam(':keyvalue', $FieldsArray[$key]['Value'], PDO::PARAM_STR);
            $query->execute();
            
            if($a = $query->fetch())
            {
                $this->NoErrorsFlag = false;
                $this->setError('Fields/IsExists/Exists', $key, array('key' => $key,'keyvalue' => $FieldsArray[$key]['Value']));
                return true;
            }
            return false;
        }
        $this->NoErrorsFlag = false;
        $this->setError('Fields/NotFound', $key, array('key' => $key));
        return false;
    }
}