
SaveCurrPlayersData = '';
timeout = null;

function playersAjaxSearch()
{
    var name = $('#searchData').val();
    $.ajax({
            url: 'public/ajax/get_search_player.php',
            type: 'POST',
            data: {'search_name': name, 'page': 1},
            dataType: 'json',
            cache: true,
            success: function(data){
                //console.log(data);
                
                var Players = '',Pages = '';
                
                $('#players').append('<thead><tr></tr></thead>');
                $('#players thead tr').append('<th>#</th>');
                $('#players thead tr').append('<th id="playerFieldName">Name</th>');
                $('#players thead tr').append('<th id="playerFieldLevel">Lv.</th>');
                $('#players thead tr').append('<th id="playerFieldEXP">Exp</th>');
                $('#players thead tr').append('<th id="playerFieldKILLS">Kills</th>');
                $('#players thead tr').append('<th id="playerFieldDEATHS">Deaths</th>');
                $('#players thead tr').append('<th id="playerFieldCLAN">Clan</th>');
                $('#players').append('<tbody></tbody>');
                
                for(i = 0;i < data.Players.length;i++)
                {
                    //console.log(data.Mates[i]);
                    
                    Players = '<tr class="player">\n';
                    Players += '<td>'+(i+1)+'</td>\n';
                    Players += '<td><a class="player_link" href="player/'+data.Players[i].Name['RegularName']+'">'+data.Players[i].Name['ColorName']+'</a></td>\n';
                    Players += '<td>'+data.Players[i].Level+'</td>\n';
                    Players += '<td>'+data.Players[i].XP+'</td>\n';
                    Players += '<td>'+data.Players[i].KillCount+'</td>\n';
                    Players += '<td>'+data.Players[i].DeathCount+'</td>\n';
                    Players += '<td>';
                    if(data.Players[i].ClanName != 0)
                        Players += '<a class="clan_link" href="clan/'+data.Players[i].ClanName['RegularName']+'">'+data.Players[i].ClanName['ColorName']+'</a>';
                    else
                        Players += '--';
                    Players += '</td>\n</tr>\n';
                    
                     $('#players tbody').append(Players);
                   
                }
                
                
                /*
                var PlayersFinal = '<thead>\n<tr>\n';
                PlayersFinal += '<th>#</th>\n';
                PlayersFinal += '<th id="playerFieldName">Name</th>\n';
                PlayersFinal += '<th id="playerFieldLevel">Lv.</th>\n';
                PlayersFinal += '<th id="playerFieldEXP">Exp</th>\n';
                PlayersFinal += '<th id="playerFieldKILLS">Kills</th>\n';
                PlayersFinal += '<th id="playerFieldDEATHS">Deaths</th>\n';
                PlayersFinal += '<th id="playerFieldCLAN">Clan</th>\n';
                PlayersFinal += '<tr>\n</thead>\n';
                PlayersFinal += '<tbody>\n'+Players+'\n</tbody>\n';
                
                
                
                for(i = 1;i <= data.Pages;i++)
                {
                    Pages += '<div class="PageMateNum">'+i+'</div>';
                }
                
                $('#players').html(PlayersFinal);
                */
                /*   
                $('#NabigateMatePage').html(Pages);
                $('.GroupMates').remove();
                $('#ClanMates').append('<div class="GroupMates"></div>');
                $('.GroupMates').hide().append(Mates);
                $('.GroupMates').fadeIn(1000);
                */
            }
            
        });
}

function UpdatePlayerSearch(name,page)
{
    if(name != '')
    {
        if(SaveCurrPlayersData == '')
            SaveCurrPlayersData = $('#players').html();
            
         if(timeout != null) {
            clearTimeout(timeout);
         }
         timeout = setTimeout(playersAjaxSearch, 200);
            
            
        $('#players').html('');
        
    }
    /*else if(SaveCurrPlayersData != '')
        $('#players').html(SaveCurrPlayersData);*/
}




$(document).ready(function(){
   
   /*$("#players .player").hover(
        function(){
            $(this).children('td').addClass('selectedPlayer');
        },
        function(){
            $(this).children('td').removeClass('selectedPlayer');
        }
    );*/
    
    
    
    
    $('#searchData').bind('click',function(){
       
        UpdatePlayerSearch($(this).val(),1);
    });
    
    $('#searchData').keyup(function(){
        
        UpdatePlayerSearch($(this).val(),1);
        
       //alert($('#players').html()); 
    });
});