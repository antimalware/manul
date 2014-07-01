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