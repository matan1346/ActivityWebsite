
$(document).ready(function(){

    $('#IsNewPassword').click(function(){
       
        if($(this).attr('checked'))
            $('#newPassword').show();
        else
            $('#newPassword').hide();
        
    });
    
    $('#SubEditProfile').easyconfirm({locale: { title: 'Edit Profile', text: 'Are you sure you want to edit the profile?' , button: ['Cancel','Confirm']}}).submit(function(e) {
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

});