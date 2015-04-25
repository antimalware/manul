var logLoaded = false;
var filesDataTable = null;
var numberItemsInFilter;

var data = "";
var new_data = "";
var filters = {};

function displayContents(xmlStr) {
    if (logLoaded) {
        //loading filters from files		               
        new_json = "";
        signature = xmlStr.substring(0, 16).replace(/\s/, '');
        //if xmlStr is json file
        if (signature.indexOf('":{"') >= 0)                
            new_json = JSON.parse(xmlStr);
        else {
            new_json = parseXMLstrToJSON(xmlStr);		        
        }
        getFilter(new_json);							
    } else {
        //loading log from file
        logLoaded = true;	
        var json = parseXMLstrToJSON(xmlStr);
        if (json ==null) {
           showProgress(false);
           alert('Invalid xml file');

           window.location.reload();
           return;
        }

        data = getFileInfoArray(json);
        buildTable(data);      				
        tryDownloadWhitelist();
    }					
};


function parseWebsiteLog(fileDictDict) {
    var fileInfoArrayArray = new Array;
    for(var fileDictName in fileDictDict) {
        var fileInfoArray = new Array;
        var fileDict = fileDictDict[fileDictName];
        fileInfoArray.push(fileDict['_detected']);
        fileInfoArray.push(fileDict['path']);
        fileInfoArray.push(fileDict['size']);
        fileInfoArray.push(fileDict.ctime);
        fileInfoArray.push(fileDict.mtime);
        fileInfoArray.push(fileDict['owner']);
        fileInfoArray.push(fileDict['group']);
        fileInfoArray.push(fileDict['access']);
        
        if (!fileDict._pos) fileDict._pos = '0';
        if (!fileDict.md5) fileDict.md5 = '';
        if (!fileDict.path) fileDict.path = '';        
        
        extraData = {'md5': fileDict.md5, 'pos': fileDict._pos, 'path': fileDict.path}
        if (fileDict._snippet) {
            extraData['snippet'] = normalizeSnippet(atob(fileDict._snippet));
        } else {
            extraData['snippet'] = '-';
        }

        fileInfoArray.push(extraData);
        fileInfoArray.push(fileDict.md5);
        fileInfoArrayArray.push(fileInfoArray);
    }
    return fileInfoArrayArray;             
}

function normalizeSnippet(str) {
  return str.replace(/@_MARKER_@/gi, "<font color=#FF00FF><b>|</b></font>");
}

function parseWhitelist(fileDictDict) {
    whitelist = {};
    for(var fileDictName in fileDictDict) {
        fileDict = fileDictDict[fileDictName];
        if (fileDict.md5 && fileDict.md5.length > 1) {
            whitelist[fileDict.md5] = fileDict.path;
        }
    }			
    console.log(Object.keys(whitelist).length + ' items in filter');
    return whitelist;             
}

var cmsDictList;
function getFileInfoArray(json) {
    var fileDictDict;
    if (json.hasOwnProperty('website_info')) {                 
        cmsDictList = json.website_info.cms_list;    			
        buildEnvTable(json.website_info.server_environment);

        //website log
        fileDictDict = json.website_info.files.file;
        return parseWebsiteLog(fileDictDict);
    }
}

function getFilter(json) {		    
    if (json.hasOwnProperty('website_info')) {                 
        //another log
        log_name = 'websitelog';
        try {
            scan_date = json.website_info.server_environment.time.split(' ')[0].replace(/\./g,'-');
            host_name = json.website_info.server_environment.http_host;
            log_name = localization.locale_dict['TableScreen.Log'] + ': ' + host_name + ' ' + scan_date; 
        } catch (ex) {
            console.log(ex);
        }
        var website_log_filter = parseWhitelist(json.website_info.files.file);
        applyFilter(log_name, website_log_filter);
        return true;
    } else if (json.hasOwnProperty('whitelist')) {
        //whitelist file
        var whitelist_name = json.whitelist.meta.name + "_" + json.whitelist.meta.version;
        console.log('Whitelist ' + whitelist_name + ' was loaded.');
        var whitelist_filter = parseWhitelist(json.whitelist.files);
        applyFilter(localization.locale_dict['TableScreen.Whitelist'] + ': ' + whitelist_name, whitelist_filter)
        return true;
    } 
}

function downloadWhitelist(cms_name, cms_version) {
    //FIXME
    url = "http://manul.ml/downloads/get_wl.php?name=" + cms_name.toLowerCase() + "&version=" + cms_version + "&format=json";
    console.log('Fetching whitelist from ' + url);
    $.ajax({ type: "GET",   
             url: url,   
             async: false,
             success : function(text)
             {					     
                 whitelist_json = JSON.parse(text);
                 getFilter(whitelist_json);						 
                 console.log('Whitelist for ' + cms_name + ' ' + cms_version + ' was downloaded');
             }
    });									
}

function tryDownloadWhitelist() {
    for(var index in cmsDictList) {
        cms = cmsDictList[index];
        console.log('Trying to download whitelist');
        console.log(cms._name + ' ' + cms._version);
        downloadWhitelist(cms._name, cms._version);
    }
}
//datatable filtering by timeslot and flags
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var minDateStr = $('#dateMin').val();
        var maxDateStr = $('#dateMax').val();

        var flags = [];
        $('.popup_name_flag').find('.list__line_checked_yes').each(function(){flags.push(this.getAttribute('val'))});

        var minDate = new Date(minDateStr);
        var maxDate = new Date(maxDateStr);
        var date = new Date(data[4]); // use data for the mtime column

        var flag = data[0];

        if (flags.indexOf(flag) == -1) {
            return false;
        } 
         
        if ( ( minDateStr == '' && '' == maxDateStr ) ||
             ( minDateStr == '' && date <= maxDate ) ||
             ( minDate <= date && '' == maxDateStr ) ||
             ( minDate <= date && date <= maxDate ) )
        {      
            return true;
        }
        return false;
    }
);			

function escapeHtml(text) {
  return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
}

function base64_decode(data) {
  if (!data)
     return "";

  //  discuss at: http://phpjs.org/functions/base64_decode/
  // original by: Tyler Akins (http://rumkin.com)
  // improved by: Thunder.m
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //    input by: Aman Gupta
  //    input by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Onno Marsman
  // bugfixed by: Pellentesque Malesuada
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //   example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
  //   returns 1: 'Kevin van Zonneveld'
  //   example 2: base64_decode('YQ===');
  //   returns 2: 'a'

  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    dec = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  data += '';

  do { // unpack four hexets into three octets using index points in b64
    h1 = b64.indexOf(data.charAt(i++));
    h2 = b64.indexOf(data.charAt(i++));
    h3 = b64.indexOf(data.charAt(i++));
    h4 = b64.indexOf(data.charAt(i++));

    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

    o1 = bits >> 16 & 0xff;
    o2 = bits >> 8 & 0xff;
    o3 = bits & 0xff;

    if (h3 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1);
    } else if (h4 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1, o2);
    } else {
      tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
    }
  } while (i < data.length);

  dec = tmp_arr.join('');

  return dec.replace(/\0+$/, '');
}

function base64_encode( data ) {	// Encodes data with MIME base64
	// 
	// +   original by: Tyler Akins (http://rumkin.com)
	// +   improved by: Bayron Guevara

	var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';

	do { // pack three octets into four hexets
		o1 = data.charCodeAt(i++);
		o2 = data.charCodeAt(i++);
		o3 = data.charCodeAt(i++);

		bits = o1<<16 | o2<<8 | o3;

		h1 = bits>>18 & 0x3f;
		h2 = bits>>12 & 0x3f;
		h3 = bits>>6 & 0x3f;
		h4 = bits & 0x3f;

		// use hexets to index into b64, and append result to encoded string
		enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
	} while (i < data.length);

	switch( data.length % 3 ){
		case 1:
			enc = enc.slice(0, -2) + '==';
		break;
		case 2:
			enc = enc.slice(0, -1) + '=';
		break;
	}

	return enc;
}

function buildTable(data) {
    window.filesDataTable = $('#filesTable').dataTable({
       "order": [[ 0, "desc" ]],

       "aLengthMenu": [[100, 10, 500, -1], [100, 10, 500, "All"]],

       "fnInitComplete": function(oSettings, json) {
           showProgress(false);
           turnOnTableScreen(); 
       },

       "iDisplayLength": 50,

       //Localization
       "oLanguage": {
            "sLengthMenu": "Отображать по _MENU_ записей",
            "sZeroRecords": "Ничего не найдено",
            "sInfo": "Отображается c _START_ по _END_ из _TOTAL_ файлов",
            "sInfoEmpty": "Нет файлов",
            "sInfoFiltered": "(всего записей _MAX_)",
            "sSearch":       "Поиск:",
            "sUrl":          "",
            "oPaginate": {
                "sFirst": "&nbsp;&nbsp;&nbsp;",
                "sPrevious": "&nbsp;&nbsp;&nbsp;",
                "sNext": "&nbsp;&nbsp;&nbsp;",
                "sLast": "&nbsp;&nbsp;&nbsp;"
            },
            "oAria": {
                "sSortAscending":  ": активировать для сортировки столбца по возрастанию",
                "sSortDescending": ": активировать для сортировки столбцов по убыванию"			
            }
        },
        

        //Data source
        "aaData": data,

        //Data preprocessing for rendering
        "aoColumnDefs": [

            //Detection flag field preprocessing        
            {
                "aTargets": [0],
                "bAutoWidth": false,
                "sWidth" : "60px",
                "sType": "html",
                "sClass": "table__item",
                //FIXME - profomance issue with tag stripping
                //http://datatables.net/forums/discussion/3896/how-to-apply-sorting-on-hidden-column
                "mRender": function (flag, type, full) {
                    if (flag == 'c') {
                        return '<span class="table__flag table__flag_color_red"><span style="display:none">3<span></span>';								
                    } else if (flag == 'w') {
                        return '<span class="table__flag table__flag_color_yellow"><span style="display:none">2</span></span>';
                    }
                    return '<span class="table__flag table__flag_color_green"><span style="display:none">1</span></span>';							
                }

            },

            //File size field preprocessing
            {
                "aTargets": [1],
                "bAutoWidth": false,
                "sWidth" : "500px",
            },

            //File size field preprocessing
            {
                "aTargets": [2],
                "bAutoWidth": false,
                "sWidth" : "100px",
                "sClass": "table__item",
                "mRender": function (size, type, full) {
                    if (size > -1) {
                       return size;
                    } else {
                       return '';
                    }
                }
            },

            //Ctime and mtime fields preprocessing        
            {
                "aTargets": [3, 4],
                "bAutoWidth": false,
                "sWidth" : "250px",
                "sClass": "table__item",
                //"sType": "date",
                "mRender": function (timestamp, type, full) {
                    return $.format.date(new Date(timestamp * 1000), "yyyy-MM-dd HH:mm:ss");
                }
            },
            {
                "aTargets": [1],
                "sWidth" : "100px",
                "sClass": "table__item",
            },
            {
                "aTargets": [5, 6, 7],
                "sClass": "table__item",
                "visible": false
            },
            {
                "aTargets": [8],
                "sWidth" : "100px",
                "bAutoWidth": false,
                "sClass": "table__item",
                "mRender": function (detectionInfo, type, full) {   
                    var buttons = "<div class=\"button-group i-bem button-group_js_inited\" hash='" + base64_encode(detectionInfo.path) + "'><div class=\"popup popup_visibility_hidden i-bem popup_js_inited\" data-bem=\"{&quot;popup&quot;:{&quot;0&quot;:&quot;t&quot;,&quot;1&quot;:&quot;r&quot;,&quot;2&quot;:&quot;u&quot;,&quot;3&quot;:&quot;e&quot;}}\"><div class=\"popup__close\"></div><div class=\"popup__content\"><table class=\"table\"><tbody><tr class=\"table__line\"><td class=\"table__item table__item_bold_yes\"> Hash </td><td class=\"table__item\">" + base64_encode(detectionInfo.path) + "</td></tr></tbody><tr class=\"table__line\"><td class=\"table__item table__item_bold_yes\"> Snippet </td><td class=\"table__item\">" + normalizeSnippet(base64_decode(detectionInfo.snippet)) + "</td></tr></table></div></div><button class=\"button_more_yes button i-bem\" data-bem=\"{&quot;button&quot;:{}}\" role=\"button\" type=\"button\"><div class=\"button__arrow\"></div></button><button id=\"q_" + base64_encode(detectionInfo.path) + "\" class=\"button button_size_s i-bem\" data-bem=\"{&quot;button&quot;:{}}\" role=\"button\" onclick=\"return add_quarantine('" + base64_encode(detectionInfo.path) + "', '" + detectionInfo.path + "')\"><span class=\"button__text quarantine\">" + localization.locale_dicts[localization.chosen_language]["TableScreen.Quarantine" ] + "</span></button><button id=\"d_" + base64_encode(detectionInfo.path) + "\" class=\"button button_size_s i-bem\" data-bem=\"{&quot;button&quot;:{}}\" role=\"button\" onclick=\"return add_delete('" + base64_encode(detectionInfo.path) + "', '" + detectionInfo.path + "')\"><span class=\"button__text delete\">" + localization.locale_dicts[localization.chosen_language]["TableScreen.Delete"] + "</span></button></div>";
                    return buttons;
                }
            },
            //md5 hash as a hidden column
            {
                "aTargets": [9],
                "sClass": "table__item",
                "visible": false
            }									 
        ]
    });		


}


function filterColumns() {
    $('.popup_name_columns').find('.list__line').each(function(){
        var column = filesDataTable.DataTable().column( this.getAttribute('val') );
        column.visible( $(this).hasClass('list__line_checked_yes') );				
        console.log(this.getAttribute('val'));
    });
}


function applyFilter(filter_name, filter) {
    filters[filter_name] = filter;
    filter_stat[filter_name] = {'total': Object.keys(filters[filter_name]).length, 'filtered': 0}

    //reset counter for each filter because stats is calculated 
    //for all filters again every time new filter is added
    $.each(filter_stat, function(name, stat) {
       filter_stat[name]['filtered'] = 0;
    });

    remove_control = '<a onclick="console.log($(this).parent().parent());" href="#">X</a>'
    row = '<tr><td>' + filter_name + '</td><td>' + filter_stat[filter_name]['total'] + '</td>';
    row += '<td id="fb_' + filter_name +'">' + filter_stat[filter_name]['filtered'] + '</td><td><input type="button" value="X" class="filter_list_button_remove"/></td></tr>';
    $('#filter_file_list tr:last').after(row);
    $('.filter_list').css('visibility', 'visible');
    
    //redraw the table with newly added filter				
    $("#filesTable").dataTable().fnDraw();

    //TODO: jquery selector doesn't work here
    $(document.getElementById('fb_' + filter_name)).text(filter_stat[filter_name]['filtered']);
}


$('#filter_file_list').on('click', 'input[type="button"]', function(e){
    row = $(this).closest('tr');
    row.remove();
    filename = row.children().first().text();            

    //remove filename from filter_list
    delete filters[filename];
    delete filter_stat[filename];

    if (Object.keys(filters).length <= 0) {
        $('.filter_list').css('visibility', 'hidden');
    }
    //redraw table
    $("#filesTable").dataTable().fnDraw();      
});

//whitelist filtering
var filter_stat = {}
$.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex )
        {
            var row = oSettings.aoData[iDataIndex].nTr;
            filepath = $(row).children().eq(1).text();
            hash = $(row).children().last().children().first().attr('hash');
            var is_whitelisted = false;
            $.each(filters, function(filter_name, filter) {
                if (filter.hasOwnProperty(hash)) {
                    filter_stat[filter_name]['filtered'] += 1;                            
                    is_whitelisted = true;
                }                        
            });                    
            return !is_whitelisted;
        }
    ); 			
