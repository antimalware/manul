<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{PS_CHECKER_TITLE}</title>
    <meta name="viewport" content="width=device-width">
    <style type="text/css" src="static/css/common.css"></style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="static/js/localization.ru.js"></script>
    <script src="static/js/auth.js"></script>

    <link href="static/css/common.css" type="text/css" rel="stylesheet" />
  </head>
  <body class="page">
    <div class="body">
      <div class="body__content">
        <form class="form-signin" method="POST" action="{PS_AUTH_FORM_TARGET}">
        <h2 class="form-signin-heading">{PS_CHECKER_TITLE}</h2>
        <div>
            <table cellspacing=0 cellpadding=5 border=0>
               <tr align=left><td>{PS_CHECKER_ROOT_READABLE}</td><td><span class="{PS_CHECKER_ROOT_READABLE_STYLE}">{PS_CHECKER_ROOT_READABLE_STATUS}</span></td><td><div style="display: {PS_CHECKER_ROOT_READABLE_FIX}"><a href="{PS_CHECKER_ROOT_READABLE_LINK}" target=_blank class="fix_link">{PS_CHECKER_FIX}</a></div></td></tr>
               <tr align=left><td>{PS_CHECKER_PHPVERSION}</td><td><span class="{PS_CHECKER_PHPVERSION_STYLE}">{PS_CHECKER_PHPVERSION_STATUS}</span></td><td><div style="display: {PS_CHECKER_PHPVERSION_FIX}"><a href="{PS_CHECKER_PHPVERSION_LINK}" target=_blank class="fix_link">{PS_CHECKER_FIX}</a></div></td></tr>
               <tr align=left><td>{PS_CHECKER_ZIP}</td><td><span class="{PS_CHECKER_ZIP_STYLE}">{PS_CHECKER_ZIP_STATUS}</span></td><td><div style="display: {PS_CHECKER_ZIP_FIX}"><a href="{PS_CHECKER_ZIP_LINK}" target=_blank class="fix_link">{PS_CHECKER_FIX}</a></div></td></tr>
               <tr align=left><td>{PS_CHECKER_DOM}</td><td><span class="{PS_CHECKER_DOM_STYLE}">{PS_CHECKER_DOM_STATUS}</span></td><td><div style="display: {PS_CHECKER_DOM_FIX}"><a href="{PS_CHECKER_DOM_LINK}" target=_blank class="fix_link">{PS_CHECKER_FIX}</a></div></td></tr>
               <tr align=left><td>{PS_CHECKER_PERM}</td><td><span class="{PS_CHECKER_PERM_STYLE}">{PS_CHECKER_PERM_STATUS}</span></td><td><div style="display: {PS_CHECKER_PERM_FIX}"><a href="{PS_CHECKER_PERM_LINK}" target=_blank class="fix_link">{PS_CHECKER_FIX}</a></div></td></tr>
            </table>

        <p>{PS_CHECKER_MESSAGE}</p>
        </div>

       <div class="body__spacer"></div>
    </div> 
  </body>
</html>
