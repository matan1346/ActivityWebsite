<?php
require_once 'includes/functions.php';
require_once 'includes/classes/form.class.php';
require_once 'includes/classes/register.class.php';
IsAccessAllowed();


$message = '';
$RegisterMessage = array();
$RegisterUserDataPost = $Register_SaveValues;

if(isset($_POST['SubRegister']))
{
    //Check Captcha Validation
    require 'includes/recaptcha/recaptchalib.php';
    $resp = recaptcha_check_answer (reCAPTCHA_private_key,
                            $_SERVER["REMOTE_ADDR"],
                            $_POST["recaptcha_challenge_field"],
                            $_POST["recaptcha_response_field"]);
    $captchaFlag = true;
    if (!$resp->is_valid)
        $captchaFlag = false;
    
    
    //Create a new register object
    $Register = new Register($GunZ);
   
    //Add register fields
    $Register->setField('Username', 'Username',$_POST['usernameR']);
    $Register->setField('Name', 'Name',$_POST['nameR']);
    $Register->setField('Password', 'Password',$_POST['passwordR']);
    $Register->setField('rePassword', 'Password',$_POST['re_passwordR']);
    $Register->setField('Email', 'Email',$_POST['emailR']);
    $Register->setField('Gender', 'Gender',$_POST['genderR']);
    $Register->setField('Age', 'Age',$_POST['ageR']);
    $Register->setField('Secret_Question', 'Secret_Question',$_POST['secret_questionR']);
    $Register->setField('Secret_Answer', 'Secret_Answer',$_POST['secret_answerR']);
    
    
    //Check if fields are not empty.
    $Register->IsNotEmpty(array('Gender'));
    
    
    //Check if characters allowed
    if($Register->IsValuesAllowed(array('Gender','Age')))
    {
        
        //Is gender and age are numbers
        $Register->IsNumber('Gender');
        $Register->IsNumber('Age');
        
        //Length of fields
        $Register->IsLengthBetween('Username', array('min' => 4,'max' => 20));
        $Register->IsLengthBetween('Name', array('min' => 2,'max' => 20));
        $Register->IsLengthBetween('Password', array('min' => 8,'max' => 20));
        $Register->IsLengthBetween('Email', array('min' => 8,'max' => 40));
        $Register->IsLengthBetween('Secret_Question', array('min' => 8,'max' => 40));
        $Register->IsLengthBetween('Secret_Answer', array('min' => 1,'max' => 15));
        
        //Is password equals to each other (CASE-INTENSIVE)
        $Register->IsEquals('Password','rePassword');
        
        //Is age and gender is between numbers
        $Register->IsNumberBetween('Gender', array('min' => 0,'max' => 1));
        $Register->IsNumberBetween('Age', array('min' => 1,'max' => 30));
        
        
        if($Register->NoErrors())
        {
            $Register->IsExists('Username');
            $Register->IsExists('Email');
            
            
            if($Register->NoErrors() && $captchaFlag)
            {
                $Register->Register();
            }
        }
    }
    
    
    
    //Get originl user values.
    $RegisterUserDataPost = $Register->getFieldsValues();
    
    //Get all register messages
    $RegisterMessage = $Register->getSystemMessages();
       
    if(!$captchaFlag)
        $RegisterMessage['Captcha'][] = 'The reCAPTCHA wasn\'t entered correctly.';
    
}


$captcha = array('server' => 'google.com', 'public_key' => reCAPTCHA_public_key);

$Navigate->SetData(array('captcha' => $captcha,'title' => SITE_TITLE.' - Register','RegisterMessage' => $RegisterMessage,'RegisterUserValues' => $RegisterUserDataPost));