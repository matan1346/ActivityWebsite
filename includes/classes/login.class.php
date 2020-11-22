<?php

class Login extends Form
{
    private $GunZ;
    private $UserAID;
    
    public function __construct($GunZ, $data = array())
    {
        parent::__construct($GunZ);
        $this->GunZ = $GunZ;
        foreach($data as $k => $v)
        {
            if(array_key_exists($k, $this->_Fields))
                $this->FieldsData[$k] = $v;
        }
    }
    
    public function getUserAID()
    {
        return $this->UserAID;
    }
    
    
    public function Login()
    {
        /*$query_str = 'SELECT Account.UserID AS UserID,Account.AID AS AID,Account.UGradeID AS UGradeID,Account.Name AS Name,Account.Email AS Email,Account.RegDate AS RegDate,Account.SQ AS SQ,Account.SA AS SA,Account.Coins AS Coins FROM Account
         LEFT JOIN Login ON Account.AID = Login.AID WHERE Login.UserID = :userid AND Login.Password = :password';
         */
         //echo 'sad';
        $Fields = $this->getFieldsValues();
        //echo 'asdas'.$this->FieldsData['Username'];
        /*$query_str = 'SELECT AID FROM Login WHERE UserID = :userid AND Password = :password';
        $LoginQuery = $this->GunZ->prepare($query_str);
        $LoginQuery->bindParam(':userid', $Fields['Username'], PDO::PARAM_STR);
        $LoginQuery->bindParam(':password', $Fields['Password'], PDO::PARAM_STR);
        $LoginQuery->execute();
        */
        $getData = $this->GunZ->SelectData('Login',array('AID'),array(array('UserID' => ':userid'),array('Password' => ':pass')),array(':userid' => $Fields['Username'],':pass' => $Fields['Password']));
        
                                
        //if($a = $LoginQuery->fetch(PDO::FETCH_ASSOC))
        if(is_array($getData) && sizeof($getData) > 0)
        {
            //echo 'OK';
            $this->setError('System/Login/Succeed','System');
            //$this->setNewError('System', 'You have been logged in!');
            //$this->SystemMessage['System'][] = 'You have been logged in!';
            $this->UserAID = $getData[0]['AID'];//$a['AID'];
            return true;
        }
        $this->setError('System/Login/Failed','System');
        return false;
    }
}