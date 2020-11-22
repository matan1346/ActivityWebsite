//$.noConflict(true);
$(document).ready(function() {
    
    /*
        if (!$.isFunction(window.live)) {
            //execute it
            //somenoobfunction();
            $('.player_link,.clan_link').live('mouseover', function(){
            $(this).attr('target','_blank').attr('title', $(this).text()+'`s Profile');
            });
            
            $('.player_link,.clan_link').live('mouseout', function(){
                $(this).removeAttr('title').removeAttr('target');
            });
        }
        
    */
    
 });
 /*
 function updateToColor(nameToColor)
{
    var i;
    var previewStr = "";
    var closeSpan = "";
    for(i=0; i<nameToColor.length; i++)
    {
        if(nameToColor.charAt(i) == '^' && (i+1)<nameToColor.length)
        {
            switch(nameToColor.charAt(i+1))
            {
                {% for key,color in  ColorsName %}
                case "{{key}}":
                    previewStr += '<span style="color: #{{color}}">';
                    closeSpan += '</span>';
                    break;
                    
                {% endfor %}default:
                    previewStr += nameToColor.charAt(i);
                    i--;
                    break;
            }
            i++;
        }
        else
        {
            previewStr += nameToColor.charAt(i);
        }
    }
    previewStr += closeSpan;
    
    return previewStr;
}*/