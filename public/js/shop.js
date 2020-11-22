

Coins = 0;


function UpdateTimeAndStatus(valueName)
{
    valueArray = valueName.split(' - ');
        
    //alert(valueArray[0]);
    //$('#Data .buttons button').remove();
    var CostV = parseInt(valueArray[1].replace(',',''));
    
    $('#Duration').text(valueArray[0]);
    
    $("#Duration").animate({opacity:0},200,"linear",function(){
      $(this).animate({opacity:1},200).css('color','black');
    }).css('color','green');
   //alert($(this[value=$(this).val()]).text()); 
   
   
   if(Coins >= CostV)
   {
    //alert("sads");
        $('#Data .buttons .negative').css('display','none');
        $('#Data .buttons .positive').css('display','block');
   }
   else
   {
    //alert("sads2");
        $('#Data .buttons .positive').css('display','none');
        $('#Data .buttons .negative').css('display','block');
        //$('#Data .buttons').append('<button type="button" class="negative">\n<img src="public/img/cross.png" alt=""/>\nNO COINS\n</button>');
   }
}


function UpdateVIPPreviewColor(selectedPlayer, SeletedColor)
{
    $('#PreviwColor').css({
        'color' : '#'+SeletedColor,
       'background-color' : '#'+SeletedColor
    });
    $('#VIPPreview').html(selectedPlayer).css({
       'color' : '#'+SeletedColor
    });
    
}


$(document).ready(function(){
    
    
    $('#ItemOption').change( function(){
        var valueName = $(this[value=$(this).val()]).text();
        
        UpdateTimeAndStatus(valueName);
        
       
    });
    
    
    $('#CharactersVipSelect').change(function(){
       
        UpdateVIPPreviewColor($(this).val(), $('#myColor').val());
        
    });
    
    $('#myColor').keyup(function(){
       
       
        var myText = $('#PreviewRow').find('#CharactersVipSelect').val();
        if(myText === undefined)
            myText = 'Text';
       
        var myColor = $(this).val();
        if(myColor === undefined || myColor == '')
            myColor = '000';
       
        UpdateVIPPreviewColor(myText, myColor);
        
    });
    
    
    
	$('form[name=BuyItem] #SubItem').easyconfirm({locale: { title: 'Purchase Item', text: 'Are you sure you want to purchase the following item:<br />`<span style="color: #008FFF;">'+$('#ItemName').text()+'</span>`?' , button: ['Cancel','Confirm']}}).submit(function(e) {
       /*
       display: block;
        position: fixed;
        overflow: hidden;
        z-index: 1002;
        outline: 0px;
        height: auto;
        width: 340px;
        top: 40%;
        left: 419.5px;
       */
        return true
	});
    
    $('#ColorNameTable #CharacterColorSelect').change(function(){
        $('#ColorNameTable #CharacterColor').val($(this).val());
        updatePreview($(this).val());
        updatePrice($(this).val());
    });
    
    $('#ColorNameTable #TimeSelect').change(function(){
        //alert($(this).val());
        updatePrice($('#ColorNameTable #CharacterColor').val());
    });
    
    
    $('#ColorNameTable #CharacterColor').keyup(function(){
        updatePreview($(this).val());
        updatePrice($(this).val());
    });
    
    
});


function AreUSureUWantToBuy()
{
    var ItemName = document.getElementById('ItemName').value;
    var c = confirm("Are you sure you want to purchase the item `"+ItemName+"`?");
    if(c)
        return true;
    return false;
}