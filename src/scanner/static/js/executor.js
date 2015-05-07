$(document).ready(function(){  

        if ($('#quarantineLink').attr('href') == '') {                  
            $('#quarantineLink').hide(); 
        }

        if ($('#executorLog').is(':empty')) {
            $('#executorForm').show();
        } else {
            $('#resultDiv').show();             
        }        
        
        $(".check_executor[name=all]").change(function() {
            if(this.checked) {
                $(".check_executor[name!=all]").prop('checked', true);
            } else {
                $(".check_executor[name!=all]").removeAttr('checked');
            }
        });

});

