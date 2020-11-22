<?php
require_once 'includes/functions.php';
require_once 'includes/classes/form.class.php';
require_once 'includes/functions.php';

IsAccessAllowed();

$isAvailable = false;

if($Session->IsConnected())
{
    $isAvailable = true;
    
    $editPassword = false;
    if(isset($_POST['SubEditProfile']))
    {
        
        $Form = new Form($GunZ);
   
        //Add register fields
        $Form->setField('Name', 'Name',$_POST['newName']);
        $Form->setField('Password', 'Password',$_POST['myPassword']);
        if(isset($_POST['IsNewPassword']))
        {
            $editPassword = true;
            $Form->setField('newPassword', 'Password',$_POST['newPassword']);
        }
            
        $Form->setField('Email', 'Email',$_POST['newEmail']);
        $Form->setField('Age', 'Age',$_POST['newAge']);
        
        
        //Check if characters allowed
        if($Form->IsValuesAllowed(array('Age')))
        {
            //Is gender and age are numbers
            $Form->IsNumber('Age');
            
            //Length of fields
            $Form->IsLengthBetween('Name', array('min' => 2,'max' => 20));
            $Form->IsLengthBetween('Password', array('min' => 8,'max' => 20));
            if($editPassword)
                $Form->IsLengthBetween('newPassword', array('min' => 8,'max' => 20));
            $Form->IsLengthBetween('Email', array('min' => 8,'max' => 40));
            
            //Is age and gender is between numbers
            $Form->IsNumberBetween('Age', array('min' => 1,'max' => 30));
            
            
            if($Form->NoErrors())
            {
                $Password = $_POST['myPassword'];
                if($GunZ->IfRowExists('Login',array(array('Password' => ':password'), array('AID' => $User->getUserField('AID'))), array(':password' => $Password)))
                {
                    $Email = $_POST['newEmail'];
                    if(!$GunZ->IfRowExists('Account',array(array('Email' => ':email'),array('AID <> '.$User->getUserField('AID'))), array(':email' => $Email)))
                    {
                        $UpdateArray = array('Name' => ':name','Email' => ':email','Age' => $_POST['newAge']);
                        $bind = array(':name' => $_POST['newName'],':email' => $_POST['newEmail']);
                        
                        $statement = array('AID' => $User->getUserField('AID'));
                        $GunZ->UpdateData('Account', $UpdateArray, $statement, $bind);
                        if($editPassword)
                            $GunZ->UpdateData('Login', array('Password' => ':password'), $statement, array(':password' => $_POST['newPassword']));
                        $Form->setMessage('System', 'The details has been updated.');
                        $User->setUserField('Name', $_POST['newName']);
                        $User->setUserField('Age', $_POST['newAge']);
                        $User->setUserField('Email', $_POST['newEmail']);
                    }
                    else
                        $Form->setMessage('Email', 'The email is already exists.');
                }
                else
                    $Form->setMessage('Password', 'The password is in coerrect.');
                
                
            }
        }
        //Get all edit messages
        $EditMessage = $Form->getSystemMessages();
    }
    
    
    $ProfileData = $User->getMultiUserFields('UserID','Name','Age','Email');
    $Navigate->setData(array('ProfileData' => $ProfileData,'IsCheck' => $editPassword,'EditMessage' => $EditMessage,'title' => SITE_TITLE.' - Edit Profile'));
}
$Navigate->setData(array('IsConnected' => $isAvailable));