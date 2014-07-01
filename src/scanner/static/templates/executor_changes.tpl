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
</head>
<body>
  <body class="page">
    <div class="body">
      <div class="head">
           <h1 class="header header_type_main">PHP Antimalware Tool</h1>
           <div class="head__uninstall">
                <button class="button small_button_font" id="deleteButton">{PS_DELETE_TOOL_BUTTON_TITLE}</button>
           </div>
           <div class="head__menu">
                <a class="head__menu-item" href="index.php?controller=scanner">{PS_TITLE_SCANNER}</span>
                <a class="head__menu-item head__menu-item_active_yes" href="index.php?controller=executor">{PS_TITLE_EXECUTOR}</a>
           </div>
      </div>

    <div class="body__content executor_changes">
       <!-- /container -->
       <div>
           <div id="executorForm">
               <h2>{PS_CHECK_RECIPE}</h2>
               <form action="index.php?controller=executor" method="POST" onsubmit="return validate_recipe(this);">
               <input type="hidden" name="total_d" value="{PS_EXECUTE_TOTAL_D}">
               <input type="hidden" name="total_q" value="{PS_EXECUTE_TOTAL_Q}">
               <input type="hidden" name="a" value="apply"> 
               
               <div class="recipe_pane">
               <table cellspacing=0 cellpading=0 border=0 width="100%">
                  <tr align=left class="executor_header"><th><input class="check_executor" name="all" readonly type="checkbox" checked></th><th><b>{PS_RECIPE_FILENAME}</b></th><th><b>{PS_RECIPE_ACTION}</b></th></tr>
                  {PS_EXECUTE_LIST}
                  <tr><td>&nbsp;</td><td>&nbsp;</td><td><input type="submit" class="button_action execute_button" value="{PS_EXECUTE}"/></td></tr>
               </table>  
               </div>
               </form>
           </div>
       </div>
       <!-- /container -->
      </div>
     <div class="body__spacer"></div>
     <div class="footer"><a class="b-link footer__item" href="#"></a><a class="b-link footer__item" href="static/html/help.html">{PS_HELP}</a><div class="footer__item footer__item_type_copyright">&copy; 2013&mdash;2014</div></div>
    </div>
  </body>
</html>

