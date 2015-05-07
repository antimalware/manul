$(document).ready(function () {

    function validateXML(txt) {
        // code for IE
        if (window.ActiveXObject) {
            var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
            xmlDoc.async = "false";
            xmlDoc.loadXML(document.all(txt).value);

            if (xmlDoc.parseError.errorCode != 0) {
                txt = "Error Code: " + xmlDoc.parseError.errorCode + "\n";
                txt = txt + "Error Reason: " + xmlDoc.parseError.reason;
                txt = txt + "Error Line: " + xmlDoc.parseError.line;
                alert(txt);
            } else {
                alert(PT_STR_NO_ERROR_FOUND);
            }
        }
        // code for Mozilla, Firefox, Opera, etc.
        else if (document.implementation.createDocument) {
            var parser = new DOMParser();
            var xmlDoc = parser.parseFromString(txt, "text/xml");

            if (xmlDoc.getElementsByTagName("parsererror").length > 0) {
                //checkErrorXML(xmlDoc.getElementsByTagName("parsererror")[0]);
                return false;
            } else {
                return true;
            }
        } else {
            alert(PT_STR_NO_XML_SUPPORT);
            return false;
        }
    }

    var url = '{PS_QUARANTINE_URL}';
    if (url == '') {
        $('#quarantineLink').hide();
    }

    function validate_recipe2(f) {
        var dn = Number(f.total_d.value);
        var qn = Number(f.total_q.value);
        var action_num = 0;

        for (i = 0; i < dn; i++) {
            if (f.elements['d_' + i].checked) {
                action_num++;
            }
        }

        for (i = 0; i < qn; i++) {
            if (f.elements['q_' + i].checked) {
                action_num++;
            }
        }

        if (action_num < 1) {
            alert('Select at least one item');
            return false;
        } else {
            return true;
        }
    }

    function validate_recipe(form) {

        var recipe = form.recipe.value;
        var deletes = false;
        var valid_recipe = true;

        if (recipe.indexOf('<recipe>') == -1 || !validateXML(recipe)) {
            valid_recipe = false;
        }

        if (!valid_recipe) {
            alert(PT_STR_INVALID_XML);
            return valid_recipe;
        }
    }

    if ($('#quarantineLink').attr('href') == '') {
        $('#quarantineLink').hide();
    }

    if ($('#executorLog').is(':empty')) {
        $('#executorForm').show();
    } else {
        $('#resultDiv').show();
    }

    $(".check_executor[name=all]").change(function () {
        if (this.checked) {
            $(".check_executor[name!=all]").prop('checked', true);
        } else {
            $(".check_executor[name!=all]").removeAttr('checked');
        }
    });
});

