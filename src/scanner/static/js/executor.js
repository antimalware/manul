$(document).ready(function(){  

        if ($('#quarantineLink').attr('href') == '') {                  
            $('#quarantineLink').hide(); 
        }

        if ($('#executorLog').is(':empty')) {
            $('#executorForm').show();
        } else {
            $('#resultDiv').show();             
        }        

});

