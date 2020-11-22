


$(document).ready(function(){
    /*
    $("#Characters").multiselect({
        header: false,
        height: 115,
        selectedList: 4 // 0-based index
    });
    */
    
    
    /*$
    
    opt = $('<option />', {
			value: v,
			text: v
		});
    */
    
    $('#currUserID').focus();
    
	/*$('form[name=TransferAccount] #SubTransfer').easyconfirm({locale: { title: 'Transfer Account', text: 'Are you sure you want to transfer your account data?' , button: ['Cancel','Confirm']}}).submit(function(e) {
       
       $('form[name=TransferAccount]').submit();
        return true
	});*/
    
    
    
 /*   
$(function(){
	var el = $("select").multiselect(),
		disabled = $('#disabled'),
		selected = $('#selected'),
		newItem = $('#newItem');
	
	$("#add").click(function(){
		var v = newItem.val(), opt = $('<option />', {
			value: v,
			text: v
		});
		
		if(disabled.is(':checked')){
			opt.attr('disabled','disabled');
		}
		if(selected.is(':checked')){
			opt.attr('selected','selected');
		}
		
		opt.appendTo( el );
		
		el.multiselect('refresh');
	});
});
   */ 
    isDataCustomOpen = false;
    isCustomExists = false;
    isAllowedContinue = true;
    $('#currUserID,#currPassword').on('keyup',function(){
    
        
       $.post("public/ajax/get_transfer_options.php", { "UserID": $('#currUserID').val(),"UserPass": $('#currPassword').val() },
          function(data){
            //console.log(data.name); // John
            //console.log(data.time); //  2pm
            //alert(data.User.);
            //alert($('#currPassword').val());
            //console.log(data.Logged);
            if(data.Logged[0] === true)
            {
                isAllowedContinue = true;
                var userID = $('#currUserID'),userPass = $('#currPassword');
                
                userID.css('border','2px solid #8EE98E');
                userPass.css('border','2px solid #8EE98E');
                $('.TransferFieldsMsg div,.TransferFieldsMsg ul,#SystemMsg div').remove();
                //alert("Login!");
                //console.log( data.User[0].UGradeID );
                
                if(isCustomExists == false)
                {
                    var el,opt;
                    
                   
                    //$('#CustomCharacters').removeChild();
                    
                
                    var Characters = '<select id="Characters" name="Characters" multiple="multiple" size="2"></select>';
                    
                    $('#TransferContainer #CustomContainer #CustomCharacters').append(Characters);
                    
                    el = $("#Characters").multiselect({
                        header: false,
                        height: 115,
                        selectedList: 4 // 0-based index
                    });
                    
                    
                    for(key in data.User[0].Characters)
                    {
                        //alert(data.User[0].Characters[key]);
                        opt = $('<option />', {
                			value: key,
                			text: data.User[0].Characters[key]['RegularName']
                  		});
                        opt.attr('selected','selected');
                        opt.appendTo( el );
                    }
                    
                    /*for(i = 0;i < data.User[0].Characters.length;i++)
                    {
                        
                    }*/
                    
                    /*opt = $('<option />', {
                			value: "asd",
                			text: "23"
              		});
                    
                    opt.appendTo( el );*/
                    el.multiselect('refresh');
                    
                    
                    
                    
                    $('#CustomTransfer').show();
                    isCustomExists = true;
                    $('#newUserID').focus();
                }
                
            }   
            else
            {
                var userID = $('#currUserID'),userPass = $('#currPassword');
                
                userID.css('border','2px solid #F58F8F');
                userPass.css('border','2px solid #F58F8F');
                
                isAllowedContinue = false;
                //userID.focus();
                $('#Characters').remove();
                $('#CustomTransfer').hide();
                $('#CustomContainer').hide();
                isCustomExists = false;
                //alert("Not login");
                
            }
                
            
          }, "json");
        
    });
    
    
    $('#newUserID').focus(function(){
       if(!isAllowedContinue)
            $('#currUserID').focus();
    });
    
    $('#currUserID').blur(function(){
        $('#currPassword').focus();
    });
    
    $('#newPassword').focus(function(){
       if(!isAllowedContinue)
            $('#currUserID').focus();
    });
    
    
    $('#CustomTransfer').click(function(){
        //alert("asdsa");
        if($('#CustomContainer').is(':hidden'))
            $('#CustomContainer').slideDown("slow");
        else
            $('#CustomContainer').slideUp();
        
    });
    
    TransferUserFlagAllowed = true;
    $('#SubTransfer').click(function(){
       
       //alert($('#currUserID').val()+' '+$('#currPassword').val()+' '+$('#newUserID').val()+' '+$('#newPassword').val());
        //$_POST['UserID'],$_POST['UserPass'],$_POST['nUserID'],$_POST['nUserPass']
        
        //console.log($('#Characters').val());
        //alert()
        if(!isCustomExists)
            return false;
        
        $.ajax({
            url: 'public/ajax/transfer_user.php',
            type: 'POST',
            data: { 'UserID': $('#currUserID').val(), 'UserPass': $('#currPassword').val(), 'nUserID': $('#newUserID').val(), 'nUserPass': $('#newPassword').val(), 'Characters': $('#Characters').val().join(',') },
            dataType: 'json',
            cache: false,
            complete: function (dataGlob) {
                
                $('.TransferFieldsMsg div,.TransferFieldsMsg ul,#SystemMsg div').remove();
                var data = eval('(' + dataGlob.responseText + ')');
                console.log(data);
                //alert("asdas");
                if(data.IsLogged[0] === false)
                {
                    //alert("asd2");
                    var userID = $('#currUserID'),
                        userPass = $('#currPassword'),
                        newUserID = $('#newUserID'),
                        newUserPass = $('#newPassword');
                
                    //$('#currUserID_error div').remove();
                    var errorFlag = false;
                    if(data.Username !== undefined)
                    {
                        if(!errorFlag)
                        {
                            userID.focus();
                            errorFlag = true;
                        }
                        userID.css('border','2px solid #F58F8F');
                        $('#currUserID_error').append('<div>'+data.Username+'</div>').css('color','#7E3434');
                    }
                        
                    if(data.Password !== undefined)
                    {
                        if(!errorFlag)
                        {
                            userPass.focus();
                            errorFlag = true;
                        }
                        userPass.css('border','2px solid #F58F8F');
                        $('#currPassword_error').append('<div>'+data.Password+'</div>').css('color','#7E3434');
                    }
                    
                    if(data.dstUserID !== undefined)
                    {
                        if(!errorFlag)
                        {
                            newUserID.focus();
                            errorFlag = true;
                        }
                        newUserID.css('border','2px solid #F58F8F');
                        $('#newUserID_error').append('<div>'+data.dstUserID+'</div>').css('color','#7E3434');
                    }
                        
                    if(data.dstPassword !== undefined)
                    {
                        if(!errorFlag)
                        {
                            newUserPass.focus();
                            errorFlag = true;
                        }
                        newUserPass.css('border','2px solid #F58F8F');
                        $('#newPassword_error').append('<div>'+data.dstPassword+'</div>').css('color','#7E3434');
                    }
                    
                    if(data.System !== undefined)
                        $('#SystemMsg').append('<div>'+data.System+'</div>').css('color','#7E3434');
                    //userID.css('border','2px solid #F58F8F');
                    
                    
                    isAllowedContinue = false;
                    //userID.focus();
                    $('#Characters').remove();
                    $('#CustomTransfer').hide();
                    $('#CustomContainer').hide();
                    isCustomExists = false;
                }
                else
                {
                    if(data.IsTransferred[0] === false)
                    {
                        if(data.dstUserID !== undefined)
                        {
                            $('#newUserID').css('border','2px solid #F58F8F');
                            $('#newUserID_error').append('<div>'+data.dstUserID+'</div>').css('color','#7E3434');
                        }
                        else if(data.System !== undefined)
                            $('#SystemMsg').append('<div>'+data.System+'</div>').css('color','#7E3434');
                            
                    }
                    else
                    {
                        $('#newUserID').css('border','2px solid #8EE98E');
                        $('#newPassword').css('border','2px solid #8EE98E');
                        $('#SystemMsg').append('<div>'+data.System+'</div>').css('color','green');
                    }
                       
                    
                    //alert("asd");
                }
                
                //TransferUserFlagAllowed = true;
                //alert(data);
            }/*,
            beforeSend: function(jqXHR,setting){
                alert('asd');
                if(TransferUserFlagAllowed == false)
                {
                    jqXHR.abort();
                    return false;
                }
                    
                TransferUserFlagAllowed = false;
                return true;
            }*/
        });
        
    });
    
    
    /*
    $.post("public/ajax/get_transfer_options.php", { "UserID": $('#currUserID').val(),"UserPass": $('#currPassword').val() },
          function(data){
            //console.log(data.name); // John
            //console.log(data.time); //  2pm
            //alert(data.User.);
            //alert($('#currPassword').val());
            //console.log(data.Logged);
            if(data.Logged[0] === true)
            {
                
                
                
            }   
            else
            {
                
            }
          }, "json");
    */
    
});