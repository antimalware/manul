<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{PS_SIGN_IN}</title>
    <meta name="viewport" content="width=device-width">
    <style type="text/css" src="static/css/scanner.css"></style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="static/js/localization.{PS_LANG}.js"></script>
    <script src="static/js/auth.js"></script>

    <script language="javascript">
      var pass_set = {PS_PASS_SET};
    </script>
    <link href="static/css/common.css" type="text/css" rel="stylesheet" />
  </head>
<body>
  <body class="page">
    <div class="body">
      <div class="head">
           <h1 class="header header_type_main">{PS_MAIN_TITLE}</h1>
           <div class="head__menu">
                <span class="head__menu-item">{PS_TITLE_SCANNER}</span>
                <span class="head__menu-item">{PS_TITLE_EXECUTOR}</span>
           </div>
      </div>
      <div class="body__content">
       <!-- /container -->
         <div class="container">
           <div class="jumbotron">
               <div class="jumbotron">
                   <div id="executorForm">
                      <form class="form-signin" method="POST" action="{PS_AUTH_FORM_TARGET}">
                      <h2 class="form-signin-heading">{PS_AUTH_FORM_MESSAGE}</h2>
                      <div><div id="passwordStrengthStatus" class="form__error">{PS_AUTH_FORM_ERROR}</div></div>
                      <div class="textarea">
                        <input type="password" id="passwordTextBox" name="password" class="textarea__input" placeholder="{PS_PASSWORD}">
                      </div>
                      <button id="sendPasswordButton" class="button_action" type="submit">{PS_OK}</button>
                      </form>
                     </div>
                     <div class="language_pane">
                       <a class="lang_switcher_{PS_ACTIVE_RU}" href="#" onclick="switchTo('ru');"><img src="static/img/ru.png" border=0></a>
                       <a class="lang_switcher_{PS_ACTIVE_EN}" href="#" onclick="switchTo('en');"><img src="static/img/uk.png" border=0></a>
                       <a class="lang_switcher_{PS_ACTIVE_UA}" href="#" onclick="switchTo('uk');"><img src="static/img/ua.png" border=0></a>
                       <a class="lang_switcher_{PS_ACTIVE_TR}" href="#" onclick="switchTo('tr');"><img src="static/img/tr.png" border=0></a>
                     </div>
                     <div class="body__spacer"></div>
                   </div>
               </div>
           </div>
         </div>
     <div class="body__spacer"></div>
     {PS_FOOTER}
    </div>
  </body>
</html>