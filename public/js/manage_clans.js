
MatesSelected = new Array();


function getMatesList()
{
    var listMates = '';
    for (var key in MatesSelected)
        listMates += MatesSelected[key]+',';
    listMates = listMates.substring(0, listMates.length-1);
    //alert(listMates.length);
    $('#MatesListR').empty();
    $('#MatesListR').append(listMates);
    return listMates.toString();
}

function getMatesGroup(clan_id,page)
{
    //alert($('#Clans[value='+clan_id+']').text());
    $('#NabigateMatePage').children().remove();
    $('.GroupMates').children().remove();
    $('.GroupMates').html('Refreshing the Data...');
    $.ajax({
            url: 'public/ajax/get_mates.php',
            type: 'POST',
            data: {'clan_id': clan_id, 'page': page},
            dataType: 'json',
            cache: false,
            success: function(data){
                //console.log(data);
                
                var Mates = '',Pages = '',
                    LeaderRadio = 'leaderRole',
                    AdminRadio = 'adminRole',
                    MemberRadio = 'memberRole',
                    KickRadio = 'noRole',
                    disabledVal = 'disabled',
                    styleVal = 'text-decoration: line-through;color: gray;';
                
                
                //alert(data.RolesState['Leader']+"\n"+data.RolesState['Administrator']+"\n"+data.RolesState['Member']+"\n"+data.RolesState['Kick']);
                
                if(data.RolesState['Leader'] === true)
                {
                    $('#'+LeaderRadio).removeAttr('disabled');
                    //$('label[for='+LeaderRadio+']')removeAttr('style').fadeIn(1000);
                    $('label[for='+LeaderRadio+']').animate({opacity:0},200,"linear",function(){
                      $(this).animate({opacity:1},200);//.css('color','black');
                    }).removeAttr('style');//.css('color','green').removeAttr('style');
                }
                else
                {
                    $('#'+LeaderRadio).attr('disabled',disabledVal);
                    //$('label[for='+LeaderRadio+']').attr('style',styleVal);
                    $('label[for='+LeaderRadio+']').animate({opacity:0},200,"linear",function(){
                      $(this).animate({opacity:1},200);//.css('color','black');
                    }).attr('style',styleVal);//.css('color','green').attr('style',styleVal);
                }
                
                if(data.RolesState['Administrator'] === true)
                {
                    $('#'+AdminRadio).removeAttr('disabled');
                    //$('label[for='+AdminRadio+']').removeAttr('style');
                    $('label[for='+AdminRadio+']').animate({opacity:0},200,"linear",function(){
                      $(this).animate({opacity:1},200);//.css('color','black');
                    }).removeAttr('style');//.css('color','green').removeAttr('style');
                }
                else
                {
                    $('#'+AdminRadio).attr('disabled',disabledVal);
                    //$('label[for='+AdminRadio+']').attr('style',styleVal);
                    $('label[for='+AdminRadio+']').animate({opacity:0},200,"linear",function(){
                      $(this).animate({opacity:1},200);//.css('color','black');
                    }).attr('style',styleVal);//.css('color','green').attr('style',styleVal);
                }
                //alert(data.RolesState['Member']);
                if(data.RolesState['Member'] === true)
                {
                    $('#'+MemberRadio).removeAttr('disabled');
                    //$('label[for='+MemberRadio+']').removeAttr('style');
                    $('label[for='+MemberRadio+']').animate({opacity:0},200,"linear",function(){
                      $(this).animate({opacity:1},200);//.css('color','black');
                    }).removeAttr('style');//.css('color','green').removeAttr('style');
                }
                else
                {
                    $('#'+MemberRadio).attr('disabled',disabledVal);
                    //$('label[for='+MemberRadio+']').attr('style',styleVal);
                    $('label[for='+MemberRadio+']').animate({opacity:0},200,"linear",function(){
                      $(this).animate({opacity:1},200);//.css('color','black');
                    }).attr('style',styleVal);//.css('color','green').attr('style',styleVal);
                }
                //alert(data.RolesState['Kick']);
                if(data.RolesState['Kick'] === true)
                {
                    $('#'+KickRadio).removeAttr('disabled');
                    //$('label[for='+KickRadio+']').removeAttr('style');
                    $('label[for='+KickRadio+']').animate({opacity:0},200,"linear",function(){
                      $(this).animate({opacity:1},200);//.css('color','black');
                    }).removeAttr('style');//.css('color','green').removeAttr('style');
                }
                else
                {
                    $('#'+KickRadio).attr('disabled',disabledVal);
                    //$('label[for='+KickRadio+']').attr('style',styleVal);
                    $('label[for='+KickRadio+']').animate({opacity:0},200,"linear",function(){
                      $(this).animate({opacity:1},200);//.css('color','black');
                    }).attr('style',styleVal);//.css('color','green').attr('style',styleVal);
                }
                
                if($('#'+LeaderRadio).is(':disabled') &&  $('#'+AdminRadio).is(':disabled') && $('#'+MemberRadio).is(':disabled') && $('#'+KickRadio).is(':disabled'))
                    $('#SubMates').remove();
                else if($('#SubMates').length <= 0)
                    $('#SetRoles').append('<input type="submit" name="EditClan" id="SubMates" value="Edit" />');
                    
                    
                
                
                $('#'+LeaderRadio).attr('checked',false);
                $('#'+AdminRadio).attr('checked',false);
                $('#'+MemberRadio).attr('checked',false);
                $('#'+KickRadio).attr('checked',false);
                MatesSelected = new Array();
                //$('input[name=foo]').attr('checked', false);

                
                
                for(i = 0;i < data.Mates.length;i++)
                {
                    //console.log(data.Mates[i]);
                    
                    Mates += '<div class="ClanMateData" title="Mate: '+data.Mates[i].Name['RegularName']+'" style="'+data.Mates[i].colorName+'">\n';
                    Mates += '<div class="MateName"><a class="player_link" href="'+BASE_PLAYER_PROFILE_PATH+data.Mates[i].Name['RegularName']+'">'+data.Mates[i].Name['ColorName']+'</a></div>\n';
                    Mates += '<div class="MateRole"><div class="MemberIcon" title="'+data.Mates[i].Role+' Icon"><img src="public/img/icons/'+data.Mates[i].Role+'.ico" alt="'+data.Mates[i].Role+' Icon" /></div><span>'+data.Mates[i].Role+'</span></div>\n';
                    Mates += '<div class="MarkMate">\n';
                    //Mates += '<div class="MemberIcon" title="'+data.Mates[i].Role+' Icon"><img src="public/img/icons/'+data.Mates[i].Role+'.ico" alt="'+data.Mates[i].Role+' Icon" /></div>\n';
                    if(data.Mates[i].RoleNum != -1)
                    {
                        Mates += '<input type="checkbox" id="MarkMate_'+data.Mates[i].CID+'" name="MarkMate[]" value="'+data.Mates[i].CID+'" />\n';
                        Mates += '<label for="MarkMate_'+data.Mates[i].CID+'"><span></span>Edit</label>\n';
                    }
                    Mates += '<br /></div>\n';
                    Mates += '</div>\n';
                    
                   
                }
                for(i = 1;i <= data.Pages;i++)
                {
                    Pages += '<div class="PageMateNum">'+i+'</div>';
                }
                $('#CurrentClan').html('<a class="clan_link" href="'+BASE_CLAN_PROFILE_PATH+data.ClanName.RegularName+'">'+data.ClanName.ColorName+'</a>');
                $('#NabigateMatePage').html(Pages);
                $('.GroupMates').remove();
                $('#ClanMates').append('<div class="GroupMates"></div>');
                $('.GroupMates').hide().append(Mates);
                $('.GroupMates').fadeIn(1000);
            }
    });
}



$(document).ready(function(){

    /*$('form[name=EditClanMates]').click(function(e){
        e.preventDefault();
       $(this).easyconfirm({locale: { title: 'Edit Mates', text: 'Are you sure you want to edit the roles of the following mates:' , button: ['Cancel','Confirm']}});
        return true;
    });*/

    
    $('form[name=EditClanMates] #SubMates').easyconfirm({locale: { title: 'Edit Mates', text: 'Are you sure you want to edit the roles of the following mates:'+$('#MatesListR').val() , button: ['Cancel','Confirm']}}).submit(function(e) {
       alert("asdas");
        return true
	});

    $('.ClanMateData .MarkMate input[type=checkbox]').live('click', function(){
        var valueThis = $(this).val();
        
        //alert(MatesSelected.valueThis);
        //alert(getMatesList);
       if($(this).is(':checked'))
           MatesSelected[valueThis] = $(this).parent().parent().children('.MateName').text();
       else
            delete MatesSelected[valueThis];
       console.log(getMatesList());
    });


    $('#Clans').change(function(){
        /*$(this).animate(
        {   left: 0 }, 
        {
            queue:false,
            duration: 500
        });*/
        
        
        //console.log($(this).val());
        
        getMatesGroup($(this).val(),1);
        
        //$(this).hide('slide', {direction: 'left'}, 1000);
        //'slide', {direction: 'right'}, 1000
        
        //$(this).css('display','none');
    });

    $('.PageMateNum').live('click',function(){
        /*$(this).animate(
        {   left: 0 }, 
        {
            queue:false,
            duration: 500
        });*/
        //$('.GroupMates').children().remove();
        //$('.GroupMates').html('Refreshing the Data...');
        
        //alert($(this).text());
        
        getMatesGroup($('#Clans').val(),$(this).text());
        
        //$(this).hide('slide', {direction: 'left'}, 1000);
        //'slide', {direction: 'right'}, 1000
        
        //$(this).css('display','none');
    });
    
    /*
    $('.GroupMates').dblclick(function(){
        $(this).animate(
        { left: -200 },
        {
            queue:false,
            duration: 500
        });
    });
    */
/*
$('.MateName').toggle(function(){
    $(this).parent().addClass('SelectedMate')
},function(){
    $(this).parent().removeClass('SelectedMate');
})
*/
/*
  $('.MateName').mouseover(function(){
    $(this).parent().addClass('SelectedMate');
});

$('.MateName').mouseout(function(){
    $(this).parent().removeClass('SelectedMate');
});
*/
});