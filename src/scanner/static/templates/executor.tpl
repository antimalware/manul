<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>{PS_TITLE_SCANNER}</title>
    <meta name="viewport" content="width=device-width">

    <link href="static/css/executor.css" type="text/css" rel="stylesheet" />
    <link href="static/css/common.css" type="text/css" rel="stylesheet" />

    <script src="static/js/localization.ru.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="static/js/bootbox.js"></script>
    <script type="text/javascript" src="static/js/common.js"></script>
    <script type="text/javascript" src="static/js/validate.js"></script>
</head>
<body>
  <body class="page">
    <div class="body">
      <div class="head">
           <h1 class="header header_type_main">{PS_MAIN_TITLE}</h1>
           <div class="head__uninstall">
                <button class="button small_button_font" id="deleteButton">{PS_DELETE_TOOL_BUTTON_TITLE}</button>
           </div>
           <div class="head__menu">
                <a class="head__menu-item" href="index.php?controller=scanner">{PS_TITLE_SCANNER}</span>
                <a class="head__menu-item head__menu-item_active_yes" href="index.php?controller=executor">{PS_TITLE_EXECUTOR}</a>
           </div>
      </div>
      <div class="body__content">
       <!-- /container -->
         <div class="container">
           <div class="jumbotron">
                   <div id="executorForm">
                       <h2>{PS_INSERT_RECIPE}</h2>
                       <form action="index.php?controller=executor" method="POST" onsubmit="return validate_recipe(this);">
                       <textarea rows="15" cols=70 name="recipe" class="executor_area"></textarea>   
                       <p><input type="submit" class="button_action" value="{PS_EXECUTE}"/></p>
                       </form>
                   </div>
           </div>
         </div>
       <!-- /container -->
    </div>
     <div class="body__spacer"></div>
     {PS_FOOTER}
    </div>
  </body>
</html>

