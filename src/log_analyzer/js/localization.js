var localization = {

    browser_language: 'en',

    init: function(language) {
        var browser_language = navigator.language;
        if (typeof navigator.language == "undefined")
            browser_language = navigator.systemLanguage; // Works for IE only

        if (language && this.locale_dicts.hasOwnProperty(language)) {
            browser_language = language;    
        }

        window.locale_dict = this.locale_dicts[browser_language];
        $.i18n.load(locale_dict);
    },       

    locale_dicts: {
        "ru": {
            "Common.LoadFile": "Загрузить файл",
            "FirstScreen.LogDescription": "Лог создается при сканировании сайта инструментом и содержит информацию об окружении сайта (данные о вебсервере, интерпретаторе, системах контроля версий) а также файлах. Загружать лог можно как в виде архива, так и в виде распакованного xml.",
            "FirstScreen.LoadLog": "Загрузите лог для анализа",
            "Footer.Contact": "Обратная связь",
            "Footer.Help": "Помощь",
            "TableScreen.ShowServerEnvironment": "Информация о серверном окружении",
            "TableScreen.File": "Файл",
            "TableScreen.Entries": "Записей",
            "TableScreen.Filtered": "Отфильтровано",
            "TableScreen.Header": "Manul: Анализатор логов",
            "TableScreen.HeaderDescription": "Анализатор логов Manul. Для эффективного поиска вредоносных файлов следует добавить вайтлисты или полученные ранее логи. В качестве фильтров можно задать маску имени файлов и дату создания.",
            "TableScreen.FilterTable.Title": "Подключенные файлы-фильтры:",
            "TableScreen.Copy": "Копировать",
            "TableScreen.Recipe": "Предписание",
            "TableScreen.Quarantine": "Карантин",
            "TableScreen.Delete": "Удалить",
            "TableScreen.TableHeader.Flag": "Флаг",
            "TableScreen.TableHeader.Name": "Имя файла",
            "TableScreen.TableHeader.Size": "Размер",
            "TableScreen.TableHeader.Ctime": "Создан",
            "TableScreen.TableHeader.Mtime": "Изменен",
            "TableScreen.TableHeader.Owner": "Владелец",
            "TableScreen.TableHeader.Group": "Группа",
            "TableScreen.TableHeader.Attributes": "Атрибуты",
            "TableScreen.TableHeader.Action": "Действие",
            "TableScreen.TableHeader.Hash": "Хэш",
            "TableScreen.FilterMenu.Flag": "Флаг",
            "TableScreen.FilterMenu.Flags.Clean": "Не найдено",
            "TableScreen.FilterMenu.Flags.Suspicious": "Подозрительный",
            "TableScreen.FilterMenu.Flags.Malicious": "Вредоносный",
            "TableScreen.FilterMenu.Fields": "Поля таблицы",
            "TableScreen.FilterMenu.Filepath": "Путь к файлу",
            "TableScreen.FilterMenu.TimePeriod": "Временной интервал",
            "TableScreen.FilterMenu.LoadFilter": "Фильтр из файла",
            "TableScreen.RecipeHint": "Созданное предписание можно запустить в выполняторе предписаний Manul"
        },
        "en": {
            "Common.LoadFile": "Load a file",
            "FirstScreen.LogDescription": "Log created when you scan the site tool and contains information about the environment of the site (data on web server, interpreter, version control systems) as well as files. You can upload the log in an archive, or as uncompressed xml.",
            "FirstScreen.LoadLog": "Load a log for analysis",
            "Footer.Contact": "Contact",
            "Footer.Help": "Help",
            "TableScreen.ShowServerEnvironment": "Show sever environment data",
            "TableScreen.File": "File",
            "TableScreen.Entries": "Entries",
            "TableScreen.Filtered": "Filered",
            "TableScreen.Header": "Manul: Log analyzer",
            "TableScreen.HeaderDescription": "Log analyzer Manul. For effective search of malicious files should be added to the previously obtained vaytlisty or logs. As a filter, you can specify a file name mask, and creation date.",
            "TableScreen.FilterTable.Title": "Loaded filters:",
            "TableScreen.Copy": "Copy",
            "TableScreen.Recipe": "Recipe",
            "TableScreen.Quarantine": "Quarantine",
            "TableScreen.Delete": "Delete",
            "TableScreen.TableHeader.Flag": "Flag",
            "TableScreen.TableHeader.Name": "Name",
            "TableScreen.TableHeader.Size": "Size",
            "TableScreen.TableHeader.Ctime": "Ctime",
            "TableScreen.TableHeader.Mtime": "Mtime",
            "TableScreen.TableHeader.Owner": "Owner",
            "TableScreen.TableHeader.Group": "Group",
            "TableScreen.TableHeader.Attributes": "Attributes",
            "TableScreen.TableHeader.Action": "Action",
            "TableScreen.TableHeader.Hash": "Hash",
            "TableScreen.FilterMenu.Flag": "Flag",
            "TableScreen.FilterMenu.Flags.NothigFound": "Nothing found",
            "TableScreen.FilterMenu.Flags.Suspicious": "Suspicious",
            "TableScreen.FilterMenu.Flags.Malicious": "Malicious",
            "TableScreen.FilterMenu.Fields": "Visible fields",
            "TableScreen.FilterMenu.Filepath": "File path",
            "TableScreen.FilterMenu.TimePeriod": "Time period",
            "TableScreen.FilterMenu.LoadFilter": "Load filter",
            "TableScreen.RecipeHint": "Generated recipe can be executed in Manul recipe executor"
        },             
        "tr": {
            "Common.LoadFile": "Load a file",
            "FirstScreen.LogDescription": "Log created when you scan the site tool and contains information about the environment of the site (data on web server, interpreter, version control systems) as well as files. You can upload the log in an archive, or as uncompressed xml.",
            "FirstScreen.LoadLog": "Load a log for analysis",
            "Footer.Contact": "Contact",
            "Footer.Help": "Help",
            "TableScreen.ShowServerEnvironment": "Show sever environment data",
            "TableScreen.File": "File",
            "TableScreen.Entries": "Entries",
            "TableScreen.Filtered": "Filered",
            "TableScreen.Header": "Manul: Log analyzer",
            "TableScreen.HeaderDescription": "Log analyzer Manul. For effective search of malicious files should be added to the previously obtained vaytlisty or logs. As a filter, you can specify a file name mask, and creation date.",
            "TableScreen.FilterTable.Title": "Loaded filters:",
            "TableScreen.Copy": "Copy",
            "TableScreen.Recipe": "Recipe",
            "TableScreen.Quarantine": "Quarantine",
            "TableScreen.Delete": "Delete",
            "TableScreen.TableHeader.Flag": "Flag",
            "TableScreen.TableHeader.Name": "Name",
            "TableScreen.TableHeader.Size": "Size",
            "TableScreen.TableHeader.Ctime": "Ctime",
            "TableScreen.TableHeader.Mtime": "Mtime",
            "TableScreen.TableHeader.Owner": "Owner",
            "TableScreen.TableHeader.Group": "Group",
            "TableScreen.TableHeader.Attributes": "Attributes",
            "TableScreen.TableHeader.Action": "Action",
            "TableScreen.TableHeader.Hash": "Hash",
            "TableScreen.FilterMenu.Flag": "Flag",
            "TableScreen.FilterMenu.Flags.NothigFound": "Nothing found",
            "TableScreen.FilterMenu.Flags.Suspicious": "Suspicious",
            "TableScreen.FilterMenu.Flags.Malicious": "Malicious",
            "TableScreen.FilterMenu.Fields": "Visible fields",
            "TableScreen.FilterMenu.Filepath": "File path",
            "TableScreen.FilterMenu.TimePeriod": "Time period",
            "TableScreen.FilterMenu.LoadFilter": "Load filter",
            "TableScreen.RecipeHint": "Generated recipe can be executed in Manul recipe executor"
        },            
        "ua": {
            "Common.LoadFile": "Load a file",
            "FirstScreen.LogDescription": "Log created when you scan the site tool and contains information about the environment of the site (data on web server, interpreter, version control systems) as well as files. You can upload the log in an archive, or as uncompressed xml.",
            "FirstScreen.LoadLog": "Load a log for analysis",
            "Footer.Contact": "Contact",
            "Footer.Help": "Help",
            "TableScreen.ShowServerEnvironment": "Show sever environment data",
            "TableScreen.File": "File",
            "TableScreen.Entries": "Entries",
            "TableScreen.Filtered": "Filered",
            "TableScreen.Header": "Manul: Log analyzer",
            "TableScreen.HeaderDescription": "Log analyzer Manul. For effective search of malicious files should be added to the previously obtained vaytlisty or logs. As a filter, you can specify a file name mask, and creation date.",
            "TableScreen.FilterTable.Title": "Loaded filters:",
            "TableScreen.Copy": "Copy",
            "TableScreen.Recipe": "Recipe",
            "TableScreen.Quarantine": "Quarantine",
            "TableScreen.Delete": "Delete",
            "TableScreen.TableHeader.Flag": "Flag",
            "TableScreen.TableHeader.Name": "Name",
            "TableScreen.TableHeader.Size": "Size",
            "TableScreen.TableHeader.Ctime": "Ctime",
            "TableScreen.TableHeader.Mtime": "Mtime",
            "TableScreen.TableHeader.Owner": "Owner",
            "TableScreen.TableHeader.Group": "Group",
            "TableScreen.TableHeader.Attributes": "Attributes",
            "TableScreen.TableHeader.Action": "Action",
            "TableScreen.TableHeader.Hash": "Hash",
            "TableScreen.FilterMenu.Flag": "Flag",
            "TableScreen.FilterMenu.Flags.NothigFound": "Nothing found",
            "TableScreen.FilterMenu.Flags.Suspicious": "Suspicious",
            "TableScreen.FilterMenu.Flags.Malicious": "Malicious",
            "TableScreen.FilterMenu.Fields": "Visible fields",
            "TableScreen.FilterMenu.Filepath": "File path",
            "TableScreen.FilterMenu.TimePeriod": "Time period",
            "TableScreen.FilterMenu.LoadFilter": "Load filter",
            "TableScreen.RecipeHint": "Generated recipe can be executed in Manul recipe executor"
        },                        
        
    },
                    
    localize: function() {

        $('.button.button_theme_action.i-bem.button_js_inited.button_hovered')._t('Common.LoadFile');
        $('.sub-header.sub-header_align_left')._t('FirstScreen.LogDescription');
        $('h2.header')._t('FirstScreen.LoadLog');
        $('a.b-link.footer__item.contact')._t('Footer.Contact');
        $('a.b-link.footer__item.help')._t('Footer.Help');
        $('.header.header_type_main')._t('TableScreen.Header');
        $('.head__description')._t('TableScreen.HeaderDescription');
        $('div.filter_list h4')._t('TableScreen.FilterTable.Title');
        $($('#filter_file_list th')[0])._t('TableScreen.File');
        $($('#filter_file_list th')[1])._t('TableScreen.Entries');
        $($('#filter_file_list th')[2])._t('TableScreen.Filtered');
        $('#showServerEnvTable')._t('TableScreen.ShowServerEnvironment');
        $('div.body__content.body__content_display_block p')._t('TableScreen.RecipeHint');
        $('#copyRecipeButton')._t('TableScreen.Copy');
        $('#tableScreen div div.body__content.body__content_display_block form h3')._t('TableScreen.Recipe');

        $('#tableHeaderFlagSpan')._t('TableScreen.TableHeader.Flag');
        $('#tableHeaderFileName')._t('TableScreen.TableHeader.Name');
        $('#tableHeaderFileSize')._t('TableScreen.TableHeader.Size');
        $('#tableHeaderFileCtime')._t('TableScreen.TableHeader.Ctime');
        $('#tableHeaderFileMtime')._t('TableScreen.TableHeader.Mtime');
        $('#tableHeaderFileOwner')._t('TableScreen.TableHeader.Owner');
        $('#tableHeaderFileGroup')._t('TableScreen.TableHeader.Group');
        $('#tableHeaderFileAttributes')._t('TableScreen.TableHeader.Attributes');
        $('#tableHeaderFileAction')._t('TableScreen.TableHeader.Action');
        $('#tableHeaderFileHash')._t('TableScreen.TableHeader.Hash');

        $('.field_flag')._t('TableScreen.TableHeader.Flag');
        $('.field_path')._t('TableScreen.TableHeader.Name');
        $('.field_size')._t('TableScreen.TableHeader.Size');
        $('.field_ctime')._t('TableScreen.TableHeader.Ctime');
        $('.field_mtime')._t('TableScreen.TableHeader.Mtime');
        $('.field_owner')._t('TableScreen.TableHeader.Owner');
        $('.field_group')._t('TableScreen.TableHeader.Group');
        $('.field_attributes')._t('TableScreen.TableHeader.Attributes');

        $('.filter__text-flag')._t('TableScreen.FilterMenu.Flag');
        $('.flag_notfound')._t('TableScreen.FilterMenu.Flags.NothigFound');
        $('.flag_suspicious')._t('TableScreen.FilterMenu.Flags.Suspicious');
        $('.flag_malicious')._t('TableScreen.FilterMenu.Flags.Malicious');
        $('.filter_path').attr('placeholder', locale_dict['TableScreen.FilterMenu.Filepath']);
        $('.filter_timeperiod')._t('TableScreen.FilterMenu.TimePeriod');
        $('.filter_loadfilter')._t('TableScreen.FilterMenu.LoadFilter');
        $('.visible_fields_menu')._t('TableScreen.FilterMenu.Fields');        
    }
}
