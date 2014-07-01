<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{PS_SIGN_IN}</title>
    <meta name="viewport" content="width=device-width">
    <style type="text/css" src="static/css/scanner.css"></style>
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
        <h2 class="form-signin-heading">{PS_AUTH_FORM_MESSAGE}</h2>

        <div><div id="passwordStrengthStatus" class="form__error"></div></div>
        <div class="textarea">
          <input type="password" id="passwordTextBox" name="password" class="textarea__input" placeholder="{PS_PASSWORD}">
        </div>
        <button id="sendPasswordButton" class="button_action" type="submit">{PS_OK}</button>
        </form>
       </div>
       <div class="body__spacer"></div>
    </div> 
  </body>
</html>