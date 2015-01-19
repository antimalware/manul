function setCookie (name, value, expires, path, domain, secure) {
      document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}

function switchTo(lang) {
  var date = new Date();
  date.setTime(date.getTime() + 24 * 60 * 60 * 180);
  setCookie('lang', lang, date.toGMTString(), '/');
  window.location.reload();
}

$(document).ready(function(){  
    var widget = $('#passwordStrengthStatus');

    if (widget.text() == '') {
       widget.hide();
    } else {
       widget.show();
    }

    $('#sendPasswordButton').on('click', function() { 
            var field = $('#passwordTextBox'); 
            var password = field.val(); 
            widget.text('');
            widget.hide();

            if (!pass_set) {
               if (password.length < 8) {
                   widget.text(PT_STR_SHORT_PASSWORD);        
                   widget.show();
                   return false;
               } else {
                   var lowercase = !!password.match(/[a-z]/g);
                   var uppercase = !!password.match(/[A-Z]/g);
                   var special = !!password.match(/\W/g);
                   var number = !!password.match(/\d/g);
    
                   var passwordStrength = lowercase + uppercase + special + number;
             
                   if (passwordStrength > 2) { 
                      return true;
                   } else {
                      widget.text(PT_STR_WEAK_PASSWORD);
                      widget.show();
                      return false;
                   }       
                    
               }
            } 
            
       });
});
            
