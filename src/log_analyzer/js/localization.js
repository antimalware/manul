var localization = {

    browser_language: 'en',
    chosen_language: 'en',

    init: function(language) {
        var browser_language = navigator.language;
        if (typeof navigator.language == "undefined")
            browser_language = navigator.systemLanguage; // Works for IE only

        //canonizing en-us ru-RU etc
        dash_pos = browser_language.indexOf('-');
        if (dash_pos !== -1) {
            browser_language = browser_language.substring(0, dash_pos);
        }

        if (language && this.locale_dicts.hasOwnProperty(language)) {
            browser_language = language;    
        }

        this.chosen_language = language || browser_language;
        $.i18n.load(this.locale_dicts[this.chosen_language]);
        this.localize();
    },       

    locale_dicts: {
        "ru": {
            "Common.Title": "Анализатор логов",
            "Common.LoadFile": "Загрузить файл",
            "FirstScreen.LogDescription": "Чтобы просмотреть отчет о проверке сайта, загрузите лог, созданный Манулом при сканировании. Загрузить лог можно как в виде архива, так и в виде распакованного xml.",
            "FirstScreen.LoadLog": "Загрузите лог для анализа",
            "Footer.Contact": "Обратная связь",
            "Footer.Help": "Помощь",
            "TableScreen.ShowServerEnvironment": "Информация о серверном окружении",
            "TableScreen.ShowServerEnvironment.Key": "Ключ",
            "TableScreen.ShowServerEnvironment.Value": "Значение",
            "TableScreen.File": "Файл",
            "TableScreen.Entries": "Записей",
            "TableScreen.Filtered": "Отфильтровано",
            "TableScreen.Header": "Manul: Анализатор логов",
            "TableScreen.HeaderDescription": "Для известных версий CMS автоматически применяется вайтлист, чтобы отфильтровать файлы из стандартной комплектации. С помощью кнопки “Фильтр/Сравнение” можно загрузить дополнительный вайтлист или предыдущий лог проверки, чтобы сравнить его с текущим. Чтобы взять файл на анализ, нажмите на кнопку “Карантин”. Чтобы удалить файл, нажмите на кнопку “Удалить”. Скрипт будет сформирован в поле “Предписание” в нижней части страницы. Скопируйте его в буфер обмена и выполните через вкладку “Лечение” в сканере.",
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
            "TableScreen.FilterMenu.LoadFilter": "Фильтр/Сравнение",
            "TableScreen.RecipeHint": "Скопируйте скрипт в буфер обмена и выполните через вкладку “Лечение” в Мануле"
        },
        "en": {
            "Common.Title": "Log analyzer",        
            "Common.LoadFile": "Load file",
            "FirstScreen.LogDescription": "Please upload the log file created by Manul during scanning to view the site scan report. You can upload it either as an archive, or as an unpacked xml file.",
            "FirstScreen.LoadLog": "Load log for analysis",
            "Footer.Contact": "Contact us",
            "Footer.Help": "Help",
            "TableScreen.ShowServerEnvironment": "Show server environment data",
            "TableScreen.ShowServerEnvironment.Key": "Key",
            "TableScreen.ShowServerEnvironment.Value": "Value",
            "TableScreen.File": "File",
            "TableScreen.Entries": "Entries",
            "TableScreen.Filtered": "Filtered",
            "TableScreen.Header": "Manul: Log analyzer",
            "TableScreen.HeaderDescription": "A whitelist is used to automatically filter out files included among the standard features of well-known CMS program versions. By using the Filter/Compare tool, you can upload and apply an additional whitelist, or upload an older scan log to compare it with the current one. Click on the Quarantine button to send a file for analysis. To delete a file, click on Delete. A script will appear in the Prescription field in the lower part of the page. Copy it to the clipboard and execute it via the Treatment tab in the scanner.",
            "TableScreen.FilterTable.Title": "Loaded filters:",
            "TableScreen.Copy": "Copy",
            "TableScreen.Recipe": "Recipe",
            "TableScreen.Quarantine": "Quarantine",
            "TableScreen.Delete": "Delete",
            "TableScreen.TableHeader.Flag": "Flag",
            "TableScreen.TableHeader.Name": "File name",
            "TableScreen.TableHeader.Size": "Size",
            "TableScreen.TableHeader.Ctime": "Created",
            "TableScreen.TableHeader.Mtime": "Modified",
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
            "TableScreen.FilterMenu.Filepath": "Path to file",
            "TableScreen.FilterMenu.TimePeriod": "Time interval",
            "TableScreen.FilterMenu.LoadFilter": "Filter/Compare",
            "TableScreen.RecipeHint": "Copy the script to the clipboard and execute it via the Treatment tab in Manul"
        },             
        "tr": {
            "Common.Title": "Kayıt denetleyicisi",
            "Common.LoadFile": "Dosyayı yükle",
            "FirstScreen.LogDescription": "Site kontrol raporunu görmek için Manul tarafından tarama işlemi sırasında oluşturulan kayıt dosyasını yükleyiniz. Anılan kayıt dosyasını ister bir arşiv olarak isterse açılmış bir XML dosyası olarak yükleyebilirsiniz.",
            "FirstScreen.LoadLog": "Denetlenecek kayıt dosyasını (log) yükleyin",
            "Footer.Contact": "Bize ulaşın",
            "Footer.Help": "Kullanıcı yardımı",
            "TableScreen.ShowServerEnvironment": "Show sever environment data",
            "TableScreen.ShowServerEnvironment.Key": "Anahtar",
            "TableScreen.ShowServerEnvironment.Value": "Değer",
            "TableScreen.File": "Dosya",
            "TableScreen.Entries": "Entries",
            "TableScreen.Filtered": "Filered",
            "TableScreen.Header": "Manul: Kayıt denetleyicisi",
            "TableScreen.HeaderDescription": "Standart donanımdaki dosyaları filtrelemek üzere bilinen CMS sürümleri için bekleme listesi otomatik uygulanır. 'Filtreleme/Karşılaştırma' butonu aracılığıyla geçerli bekleme listesi veya kayıt dosyasıyla karşılaştırılmak üzere ek bir bekleme listesi veya önceki kontrol işleminin kayıt dosyası yüklenebilir. Bir dosyayı denetlemeye göndermek için 'Karantina' butonunu tıklayınız. Dosyayı silmek için 'Dosyayı sil' butonunu tıklayınız. Komut dosyası (script), sayfanın alt kısmındaki 'Talimat' alanında oluşturulduktan sonra bunu kopyalayarak 'Virüslerden arındırma' sekmesi üzerinden çalıştırın.",
            "TableScreen.FilterTable.Title": "Loaded filters:",
            "TableScreen.Copy": "Kopyala",
            "TableScreen.Recipe": "Talimat",
            "TableScreen.Quarantine": "Karantina",
            "TableScreen.Delete": "Sil",
            "TableScreen.TableHeader.Flag": "Bayrak",
            "TableScreen.TableHeader.Name": "Dosya adı",
            "TableScreen.TableHeader.Size": "Boyutu",
            "TableScreen.TableHeader.Ctime": "Kurulma zamanı",
            "TableScreen.TableHeader.Mtime": "Değişiklik yapma zamanı",
            "TableScreen.TableHeader.Owner": "Sahibi",
            "TableScreen.TableHeader.Group": "Grubu",
            "TableScreen.TableHeader.Attributes": "Öznitelikler",
            "TableScreen.TableHeader.Action": "Eylem",
            "TableScreen.TableHeader.Hash": "Hash",
            "TableScreen.FilterMenu.Flag": "Bayrak",
            "TableScreen.FilterMenu.Flags.NothigFound": "Bulunamadı",
            "TableScreen.FilterMenu.Flags.Suspicious": "Şüpheli dosya",
            "TableScreen.FilterMenu.Flags.Malicious": "Kötü amaçlı dosya",
            "TableScreen.FilterMenu.Fields": "Tablo alanları",
            "TableScreen.FilterMenu.Filepath": "Dosyaya ulaşım yolu",
            "TableScreen.FilterMenu.TimePeriod": "Zaman aralığı",
            "TableScreen.FilterMenu.LoadFilter": "Filtre/Karşılaştırma",
            "TableScreen.RecipeHint": "Komut dosyasını kopyalayarak 'Virüslerden arındırma' sekmesi üzerinden çalıştırın."
        },            
        "ua": {
            "Common.Title": "Аналізатор логів",
            "Common.LoadFile": "Завантажити файл",
            "FirstScreen.LogDescription": "Щоб переглянути звіт про перевірку сайту, завантажте лог, створений Манулом під час сканування. Завантажити лог можна як архівом, так і розпакованим xml.",
            "FirstScreen.LoadLog": "Завантажте лог для аналізу",
            "Footer.Contact": "Зворотний зв’язок",
            "Footer.Help": "Допомога",
            "TableScreen.ShowServerEnvironment": "Информация о серверном окружении",
            "TableScreen.ShowServerEnvironment.Key": "Ключ",
            "TableScreen.ShowServerEnvironment.Value": "Значення",
            "TableScreen.File": "Файл",
            "TableScreen.Entries": "Записей",
            "TableScreen.Filtered": "Отфильтровано",
            "TableScreen.Header": "Manul: Аналізатор логів",
            "TableScreen.HeaderDescription": "Для відомих версій CMS автоматично застосовується вайтлист, щоб відфільтрувати файли зі стандартної комплектації. За допомогою кнопки «Фільтр/Порівняння» можна завантажити додатковий вайтлист або попередній лог перевірки, щоб порівняти його з поточним. Щоб взяти файл на аналіз, натисніть кнопку «Карантин». Щоб видалити файл, натисніть кнопку «Видалити». Скрипт буде сформовано в полі «Припис» у нижній частині сторінки. Скопіюйте його в буфер обміну і виконайте через вкладку «Лікування» у сканері.",
            "TableScreen.FilterTable.Title": "Подключенные файлы-фильтры:",
            "TableScreen.Copy": "Копіювати",
            "TableScreen.Recipe": "Припис",
            "TableScreen.Quarantine": "Карантин",
            "TableScreen.Delete": "Видалити",
            "TableScreen.TableHeader.Flag": "Прапор",
            "TableScreen.TableHeader.Name": "Ім'я файлу",
            "TableScreen.TableHeader.Size": "Розмір",
            "TableScreen.TableHeader.Ctime": "Створено",
            "TableScreen.TableHeader.Mtime": "Змінено",
            "TableScreen.TableHeader.Owner": "Власник",
            "TableScreen.TableHeader.Group": "Група",
            "TableScreen.TableHeader.Attributes": "Атрибути",
            "TableScreen.TableHeader.Action": "Дія",
            "TableScreen.TableHeader.Hash": "Хэш",
            "TableScreen.FilterMenu.Flag": "Прапор",
            "TableScreen.FilterMenu.Flags.Clean": "Не знайдено",
            "TableScreen.FilterMenu.Flags.Suspicious": "Підозрілий",
            "TableScreen.FilterMenu.Flags.Malicious": "Шкідливий",
            "TableScreen.FilterMenu.Fields": "Поля таблиці",
            "TableScreen.FilterMenu.Filepath": "Шлях до файлу",
            "TableScreen.FilterMenu.TimePeriod": "Часовий інтервал",
            "TableScreen.FilterMenu.LoadFilter": "Фільтр/Порівняння",
            "TableScreen.RecipeHint": "Скопіюйте скрипт у буфер обміну та виконайте через вкладку «Лікування» у сканері"
        },                        
        
    },
                    
    localize: function() {

        $('label.load_file_button')._t('Common.LoadFile');
        $('title')._t('Common.Title');

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
        $($('table#server_environment_table th')[0])._t('TableScreen.ShowServerEnvironment.Key');
        $($('table#server_environment_table th')[1])._t('TableScreen.ShowServerEnvironment.Value');

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
        $('.filter_path').attr('placeholder', this.locale_dicts[this.chosen_language]['TableScreen.FilterMenu.Filepath']);
        $('.filter_timeperiod')._t('TableScreen.FilterMenu.TimePeriod');
        $('.filter_loadfilter')._t('TableScreen.FilterMenu.LoadFilter');
        $('.visible_fields_menu')._t('TableScreen.FilterMenu.Fields');        
    },

    switchLanguage: function(element) {
        lang = $(element).attr('language');        
        console.log(lang);
        this.init(lang);
        this.localize();
        
        $('.lang_switcher_active').toggleClass('lang_switcher_active');
        $("a[language='"+lang+"']").toggleClass('lang_switcher_active');
    }
}

$(document).ready(function() {
    localization.init();
    $('.lang_switcher').bind('click', function(){localization.switchLanguage(this)});
});

