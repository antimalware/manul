<?php

$lang = "ru";

if ($lang == "ru") {
    define('PS_SCANNING_FILE', 'Сканируем');
    define('PS_SCANNING_OF', 'из');
    define('PS_SCANNING_FOUND', '');
	define('PS_SEND_REPORT_BUTTON', 'Отправить отчет');
	define('PS_ERR_NO_TEMP_FOLDER', "Невозможно создать временные файлы, так как временный системный каталог и текущий каталог скрипта не доступны на запись");
	define('PS_ERR_UPLOADING_XML', 'Ошибка загрузки XML файла. Проверьте ваши настройки php (upload_max_filesize)');
	define('PS_ERR_BROKEN_XML', 'XML отчет поврежден');
	define('PS_ERR_ARCHIVE_OPENING', 'Ошибка открытия архива');
	define('PS_ERR_ARCHIVE_CREATION', 'Ошибка создания архива: %s');
	define('PS_ERR_WRONG_ARCHIVE_MODE', 'Недопустимый режим архива. Возможные: r,w,a');
	define('PS_ERR_ARCHIVE_WRITE_INCORRECT_MODE', 'Ошибка записи. Архив был открыт для чтения');
	define('PS_ERR_NO_SUCH_FILE', 'Нет такого файла.');
	define('PS_ERR_TEMPLATE_DOESNT_EXISTS', 'Шаблон %s не найден.');
	define('PS_ERR_XML_VALIDATION_FAILED', 'В функции <b>DOMDocument::schemaValidate() возникла ошибка!</b>');
	define('PS_ERR_XML_VALIDATION_WARNING', '<b>Предупреждение %s</b>: ');
	define('PS_ERR_XML_VALIDATION_ERROR', '<b>Ошибка %s</b>: ');
	define('PS_ERR_XML_VALIDATION_FATAL_ERROR', '<b>Критическая ошибка %s</b>: ');
	define('PS_ERR_XML_FILE_SPEC', ' в <b>%s</b>');
	define('PS_ERR_XML_LINE_SPEC', ' в строке <b>%d</b>' . "\n");
	define('PS_ERR_SCRIPT_WRONG_LAUNCH_MODE', '%s could be launched in php-cli mode, from command line.'); // keep it in English for command line
	define('PS_ERR_UNPACK_ARCHIVE', 'Ошибка открытия архива');

	define('PS_ERR_BROKEN_XML_FILE', 'xml файл поврежден');
	define('PS_ERR_EXCEPTION_OCCURED', 'Возник exception: %s\n');
	define('PS_ERR_QUARANTINE_NOT_EXISTS', '<p class="err">Ошибка размещения в карантин: файл %s не существует</p>' . PHP_EOL);
	define('PS_ERR_DELETE_NOT_EXISTS', '<p class="err">Ошибка удаления файла: файл %s не существует</p>' . PHP_EOL);
	define('PS_ERR_MALWARE_DB_BROKEN', 'malware_db.xml поврежден');

	define('PS_WAS_DELETED', '<p>Файл %s удален</p>' . PHP_EOL);
	define('PS_WAS_QUARANTINED', '<p>Файл %s помещен в карантин</p>' . PHP_EOL);
	define('PS_ENTER_PASSWORD', 'Введите пароль');
	define('PS_DELETE_ARCHIVE', 'Архив уже существует. Удаляем %s');
	define('PS_PASS_OK', 'OK');
	define('PS_CHOOSE_STRONG_PASS', 'Придумайте сложный пароль для доступа к скриптам');

	define('PS_ERR_SHORT_PASSWORD', 'Ошибка. Пароль слишком короткий');
	define('PS_ERR_WEAK_PASSWORD', 'Ошибка. Пароль должен состоять из трех групп символов: заглавных, маленьких букв, цифр и спецсимволов.');
	define('PS_ERR_INVALID_PASSWORD', 'Ошибка. Неверный пароль. Для сброса удалите config.php в каталоге /tmp/.');

	define('PS_ERR_NO_QUARANTINE_FILE', 'Файл карантина не найден. Пожалуйста, выполните "Лечение" еще раз.');
	define('PS_ERR_NO_DOWNLOAD_LOG_FILE', 'Файл xml log не найден. Пожалуйста, выполните сканирование еще раз.');

	define('PS_RECIPE_FILENAME', 'Имя файла');
	define('PS_RECIPE_ACTION', 'Действие');
	define('PS_RECIPE_ACTION_DEL', 'Удалить');
	define('PS_RECIPE_ACTION_QUARANTINE', 'В карантин');
	
	define('PS_SIGN_IN', 'Вход');
	define('PS_PASSWORD', 'Пароль');
	define('PS_OK', 'OK');
	define('PS_TITLE_SCANNER', 'Сканер');
	define('PS_TITLE_EXECUTOR', 'Лечение');
	define('PS_HELP', 'Помощь');
	define('PS_MAIN_TITLE', 'PAT Tool');
	define('PS_INSERT_RECIPE', 'Вставьте предписание:');
	define('PS_CHECK_RECIPE', 'Действия будут применены только к выбранным файлам:');
	define('PS_EXECUTE', 'Исполнить');
	define('PS_RESULT', 'Журнал операций:');
	define('PS_EXECUTED_RESULT', 'Результат выполнения предписания:');
	define('PS_DOWNLOAD_QUARANTINE', 'Скачать архив с файлами в карантине');
	define('PS_DOWNLOAD_LOG', 'Скачать архив с отчетом');
	define('PS_PRETEXT', 'Давайте найдем вредоносный код!');
	define('PS_PROGRESS', 'Ход исполнения:');
	define('PS_TOTAL_PROGRESS', 'Всего найдено <span id="count">0</span> файлов');
	define('PS_BODYTEXT', 'Для начала сканирования сайта нажмите кнопку "Начать сканирование", дождитесь окончания сканирования, скачайте файл отчета и передайте его на анализ специалисту. Подробную справку см. на странице "<a href="static/html/help.html">Помощь</a>".');
	define('PS_INTERVAL', 'Интервал запросов:');
	define('PS_SEC', 'сек');
	define('PS_SCAN_LABEL', 'Искать вредоносный код:');
	define('PS_SETTINGS', 'Настройки');
	define('PS_FURTHER_INSTRUCTIONS', 'Теперь вы можете скачать отчет и передать его на анализ вирусному аналитику.');
	define('PS_START_SCAN', 'Начать сканирование');
    define('PS_ERR_DUMMY_FOLDER', 'Имя каталога, где размещен скрипт PAT Tool, должен быть уникальным и содержать хотя бы 5 символов. Например, "pat_%s". Переименуйте текущий каталог "%s".');
    define('PS_ERR_CANNOT_CREATE_FILE', 'Ошибка при создании файла %s<br>Текст ошибки: %s');
    define('PS_OLD_PHP', 'Для работы необходим PHP версии 5.2.0 или выше.');
    define('PS_NO_ZIP_MODULE', 'Для работы необходим PHP модуль zip.');
    define('PS_NO_DOMDOCUMENT_MODULE', 'Для работы необходим PHP модуль dom.');
    define('PS_ERR_NO_FILES_IN_WEB_ROOT', 'Нет файлов для проверки в корневом каталоге веб-сайта.');


	/* JS */
	define('PT_STR_DONE', "Готово!");
	define('PT_STR_ERR_OCCURED', "Ошибка!");
	define('PT_STR_BUTTON_CANCEL', "Отменить сканирование");
	define('PT_STR_SEARCH_AGAIN_BUTTON', "Сканировать снова");
	define('PT_STR_NO_ERROR_FOUND', "Ошибок не обнаружено");
	define('PT_STR_NO_XML_SUPPORT', 'Ваш браузер не поддерживает проверку XML');
	define('PT_STR_INVALID_XML','XML предписание ошибочно');
	define('PT_STR_DELETE_APPROVAL', 'Некоторые ваши файлы будут удалены. Подтверждаете?');

  	define('PS_DELETE_TOOL_BUTTON_TITLE', 'Удалить инструмент');

  	define('PS_CHECKER_TITLE', 'Проверка конфигурации сервера');
  	define('PS_CHECKER_PASSED', 'Да');
  	define('PS_CHECKER_FAILED', 'Нет');
  	define('PS_CHECKER_PHPVERSION', 'Версия PHP >= 5.2');
  	define('PS_CHECKER_ZIP', 'Установлен модуль php Zip');
  	define('PS_CHECKER_DOM', 'Установлен модуль php XML DOM');
  	define('PS_CHECKER_PERM', 'Каталог temp доступен на запись');
  	define('PS_CHECKER_FIX', 'Как исправить');
  	define('PS_CHECKER_MESSAGE', 'Пожалуйста, исправьте указанные проблемы и выполните повторный запуск скрипта.');

} else {

    define('PS_SCANNING_FILE', 'Scanning');
    define('PS_SCANNING_OF', 'file of');
    define('PS_SCANNING_FOUND', 'found');
	define('PS_SEND_REPORT_BUTTON', 'Send report');
	define('PS_ERR_NO_TEMP_FOLDER', "Cannot find folder for temporary files. Please ensure that either php temporary folder is configured properly or PAT tool folder is writable");
	define('PS_ERR_UPLOADING_XML', 'Error in uploading xml report. Check your php settings (upload_max_filesize)');
	define('PS_ERR_BROKEN_XML', 'xml report is broken');
	define('PS_ERR_ARCHIVE_OPENING', 'Archive opening error');
	define('PS_ERR_ARCHIVE_CREATION', 'Archive creation/write error: %s');
	define('PS_ERR_WRONG_ARCHIVE_MODE', 'Wrong archive mode. Available: r,w,a');
	define('PS_ERR_ARCHIVE_WRITE_INCORRECT_MODE', 'Write error: archive was opened for reading.');
	define('PS_ERR_NO_SUCH_FILE', 'no such file.');
	define('PS_ERR_TEMPLATE_DOESNT_EXISTS', 'Template %s does not exist.');
	define('PS_ERR_XML_VALIDATION_FAILED', '<b>DOMDocument::schemaValidate() errors occured!</b>');
	define('PS_ERR_XML_VALIDATION_WARNING', '<b>Warning %s</b>: ');
	define('PS_ERR_XML_VALIDATION_ERROR', '<b>Error %s</b>: ');
	define('PS_ERR_XML_VALIDATION_FATAL_ERROR', '<b>Fatal Error %s</b>: ');
	define('PS_ERR_XML_FILE_SPEC', ' in <b>%s</b>');
	define('PS_ERR_XML_LINE_SPEC', ' on line <b>%d</b>' . "\n");
	define('PS_ERR_SCRIPT_WRONG_LAUNCH_MODE', '%s could be launched in php-cli mode, from command line.');
	define('PS_ERR_UNPACK_ARCHIVE', 'Archive opening error');

	define('PS_ERR_BROKEN_XML_FILE', 'xml is broken');
	define('PS_ERR_EXCEPTION_OCCURED', 'An exception has occured: %s\n');
	define('PS_ERR_QUARANTINE_NOT_EXISTS', 'quarantine file error: file %s doesn\'t exist' . PHP_EOL);
	define('PS_ERR_DELETE_NOT_EXISTS', 'delete file error: file %s doesn\'t exist' . PHP_EOL);
	define('PS_ERR_MALWARE_DB_BROKEN', 'malware_db.xml is broken');

	define('PS_WAS_DELETED', 'file %s was deleted' . PHP_EOL);
	define('PS_WAS_QUARANTINED', 'file %s was quarantined' . PHP_EOL);
	define('PS_ENTER_PASSWORD', 'Enter password');
	define('PS_DELETE_ARCHIVE', 'Archive already exists. Deleting %s');
	define('PS_PASS_OK', 'OK');
	define('PS_CHOOSE_STRONG_PASS', 'Choose strong password for script access');

	define('PS_ERR_SHORT_PASSWORD', 'Error: Password is too short.');
	define('PS_ERR_WEAK_PASSWORD', 'Error: Password doesn\'t contain at least three groups of symbols: uppercase, lowercase, digits, special.');
	define('PS_ERR_INVALID_PASSWORD', 'Error. Invalid password. To reset the password delete config.php in /tmp/ directory.');

	define('PS_ERR_NO_QUARANTINE_FILE', 'There is no quarantine file. You have to run executor again.');
	define('PS_ERR_NO_DOWNLOAD_LOG_FILE', 'There is no xml log. You have to run scan again.');

	define('PS_RECIPE_FILENAME', 'Filename');
	define('PS_RECIPE_ACTION', 'Action');
	define('PS_RECIPE_ACTION_DEL', 'Delete');
	define('PS_RECIPE_ACTION_QUARANTINE', 'Quarantine');
	
	define('PS_SIGN_IN', 'Sign In');
	define('PS_PASSWORD', 'Password');
	define('PS_OK', 'OK');
	define('PS_TITLE_SCANNER', 'Scanner');
	define('PS_TITLE_EXECUTOR', 'Executor');
	define('PS_HELP', 'Help');
	define('PS_MAIN_TITLE', 'PAT Tool');
	define('PS_INSERT_RECIPE', 'Insert the recipe:');
	define('PS_CHECK_RECIPE', 'Changes will be applied to the selected files:');
	define('PS_EXECUTE', 'Execute');
	define('PS_RESULT', 'Result:');
	define('PS_EXECUTED_RESULT', 'Recipe execution result:');
	define('PS_DOWNLOAD_QUARANTINE', 'Download quarantine archive');
	define('PS_DOWNLOAD_LOG', 'Download report archive');
	define('PS_PRETEXT', 'Let\'s find some malware!');
	define('PS_PROGRESS', 'Progress:');
	define('PS_TOTAL_PROGRESS', 'Total found <span id="count">0</span> files');
	define('PS_BODYTEXT', 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.');
	define('PS_INTERVAL', 'Request Interval:');
	define('PS_SEC', 'sec');
	define('PS_SCAN_LABEL', 'Scan for Malware:');
	define('PS_SETTINGS', 'Settings');
	define('PS_FURTHER_INSTRUCTIONS', 'Now you can download the report and send it to malware analyst:');
	define('PS_START_SCAN', 'Start Scanning');
	define('PS_ERR_DUMMY_FOLDER', 'PAT Tool folder must be unique and at least 5 character length. For example, "pat_%s". Please, rename current folder "%s"');
	define('PS_ERR_CANNOT_CREATE_FILE', 'Cannot create file %s<br>Message: %s');
    define('PS_OLD_PHP', 'PHP version 5.2.0 or newer required.');
    define('PS_NO_ZIP_MODULE', 'PHP extension "zip" required.');
    define('PS_NO_DOMDOCUMENT_MODULE', 'PHP extension "dom" required.');
    define('PS_ERR_NO_FILES_IN_WEB_ROOT', 'No files to check in web root dir.');

  	define('PS_DELETE_TOOL_BUTTON_TITLE', 'Uninstall tool');
	
  	define('PS_CHECKER_TITLE', 'Configuration Check');
  	define('PS_CHECKER_PASSED', 'OK');
  	define('PS_CHECKER_FAILED', 'Failed');
  	define('PS_CHECKER_PHPVERSION', 'PHP Version >= 5.2');
  	define('PS_CHECKER_ZIP', 'PHP Zip Installed');
  	define('PS_CHECKER_DOM', 'PHP XML DOM Installed');
  	define('PS_CHECKER_PERM', 'temp folder is writeable');
  	define('PS_CHECKER_FIX', 'How to fix');
  	define('PS_CHECKER_MESSAGE', 'Please, fix listed problems and launch this script once again.');
	

	/* JS */
	define('PT_STR_DONE', "Done!");
	define('PT_STR_ERR_OCCURED', "Error occured!");
	define('PT_STR_BUTTON_CANCEL', "Cancel Scanning");
	define('PT_STR_SEARCH_AGAIN_BUTTON', "Search Again");
	define('PT_STR_NO_ERROR_FOUND', "No errors found");
	define('PT_STR_NO_XML_SUPPORT', 'Your browser cannot handle XML validation');
	define('PT_STR_INVALID_XML','XML recipe is not valid');
	define('PT_STR_DELETE_APPROVAL', 'Some of your files will be deleted. Are you sure?');
	
}
