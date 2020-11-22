<?php
require_once 'includes/functions.php';
require_once 'includes/classes/shop.class.php';
require_once 'includes/classes/item.class.php';

IsAccessAllowed();


//$CoinsBefore = $User->getUserField('Coins');
//if($User->isUserConnected())
//{
    $ShopNavTo = 'Menu';
    if(isset($_GET['name']))
        $ShopNavTo = $_GET['name'];
    
    $Shop = new Shop($GunZ, $ShopNavTo);
    //$NavigteShop = $Shop->getNavCategory();
    
    $AddToShop = '';
    
    if($ShopNavTo == 'Items' && isset($_GET['page_num']))
    {
        if($Session->IsConnected())
        {
            $Item = new Item($GunZ, intval($_GET['page_num']));
        
            $ItemMessage = $Item->HandleForm();
            $ItemData = $Item->getData();
            
            $ItemName = 'NotExists';
            $Exists = false;
            if($Item->isItemExists())
            {
                $ItemName = $ItemData['Name'];
                $Exists = true;
            }
            
            $AddToShop = ' - '.$ItemName;
            
            //print_r($ItemData);
            //die();
            //$isAvailable = $Session->IsConnected();
            $Navigate->SetData(array('shop_Type' => 'Item','ItemDetails' => $ItemData,'ItemMessage' => $ItemMessage,'IsItemExists' => $Exists));
            $isAvailable = true;
        }
        else
            $isAvailable = false;
        
    }
    else
    {
        $Message = $Shop->CheckListItems();
        $isAvailable = $Shop->getListItems();
        $Vars = $Shop->getVars();
        $Navigate->SetData($Vars);
    }
    
    
    
        
    
    $Coins = $User->getUserField($DataBase_Fields['Coins']);
    //echo ('Before: '.$CoinsBefore.'<br />After: '.$CoinsAfter);
    $isVip = false;
    if($isAvailable)
        $isVip = $User->isVIP();
        //'ShopNavigate' => $NavigteShop,
    $title = SITE_TITLE.' - Shop - '.$Shop->getTypeName().$AddToShop;
    $Navigate->SetData(array('ItemData' => $isAvailable,'isVIP' => $isVip,'Coins' => $Coins,'SystemMessage' => $Message,'title' => $title));
//}
//else
//{
    //$Navigate->SetData(array('ItemData' => false));
//}
    
    
