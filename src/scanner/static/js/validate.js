function validateXML(txt) {
    var xmlDoc;

    // code for IE
    if (window.ActiveXObject) {
        xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
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
    } else if (document.implementation.createDocument) { // code for Mozilla, Firefox, Opera, etc.
        var parser = new DOMParser();
        xmlDoc = parser.parseFromString(txt, "text/xml");

        return xmlDoc.getElementsByTagName("parsererror").length <= 0;
    } else {
        alert(PT_STR_NO_XML_SUPPORT);
        return false;
    }
}

function validate_recipe(form) {
    var recipe = form.recipe.value;
    var valid_recipe = true;

    if (recipe.indexOf('<recipe>') == -1 || !validateXML(recipe)) {
        valid_recipe = false;
    }

    if (!valid_recipe) {
        alert(PT_STR_INVALID_XML);
        return valid_recipe;
    }
}