$(document).ready(function(){  
    var widget = $('#passwordStrengthStatus'); 
    widget.hide();

    $("#sendPasswordButton").hide();
    $('#passwordTextBox').on('change keypress paste focus textInput input', function() { 
            var field = $('#passwordTextBox'); 
            $("#sendPasswordButton").hide();
            var password = field.val(); 
            if (password.length < 8) {
                widget.text(PT_STR_SHORT_PASSWORD);        
                widget.show();
            } else {
                widget.text('');
                widget.hide();

                var lowercase = !!password.match(/[a-z]/g);
                var uppercase = !!password.match(/[A-Z]/g);
                var special = !!password.match(/\W/g);
                var number = !!password.match(/\d/g);
 
                var passwordStrength = lowercase + uppercase + special + number;
          
                if (passwordStrength > 2) { 
                    $("#sendPasswordButton").show();
                } else {
                    widget.text(PT_STR_WEAK_PASSWORD);
                    widget.show();
                }       
                 
            }

            
       });
});
            
