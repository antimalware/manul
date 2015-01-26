zip.workerScriptsPath = "js/";

function readZip(blob, callback) {

	// use a BlobReader to read the zip from a Blob object
	zip.createReader(new zip.BlobReader(blob), function(reader) {

	  // get all entries from the zip
	  reader.getEntries(function(entries) {

		if (entries.length) {

		  // get first entry content as text
		  entries[0].getData(new zip.TextWriter(), function(text) {
			// text contains the entry data as a String

			callback(text);

			// close the zip reader
			reader.close(function() {
			  // onclose callback
			});

		  }, function(current, total) {
			// onprogress callback
		  });
		}
	  });
	}, function(error) {
	  // onerror callback
	});
	
}



function readText(fileInput) {
		var file = fileInput.files[0];

		var onsuccess = displayContents;
		console.log('Trying to load file ' + file.name);
		if (file.name.indexOf('.zip') != -1 ) {
			readZip(file, onsuccess);                
		} else {
			reader = new FileReader();
			reader.onload = function (e) {               
				onsuccess(e.target.result);
			};
			reader.readAsText(file);               
		}
}                     

function getFileInfoArray(json) {
	var fileInfoArrayArray = new Array;
	var fileDictDict = json.website_info.files.file;
	for(var fileDictName in fileDictDict) {
		var fileInfoArray = new Array;
		var fileDict = fileDictDict[fileDictName];

		fileInfoArray.push(fileDict['_detected']);
		fileInfoArray.push(fileDict['path']);
		fileInfoArray.push(fileDict['size']);
		fileInfoArray.push(fileDict.ctime);
		fileInfoArray.push(fileDict.mtime);
		//fileInfoArray.push($.format.date(new Date(fileDict.ctime * 1000), "yyyy-MM-dd HH:mm:ss"));
		//fileInfoArray.push($.format.date(new Date(fileDict.mtime * 1000), "yyyy-MM-dd HH:mm:ss"));
		fileInfoArray.push(fileDict['owner']);
		fileInfoArray.push(fileDict['group']);
		fileInfoArray.push(fileDict['access']);
		fileInfoArray.push({'md5': fileDict.md5, 'pos': fileDict._pos, 'snippet': fileDict._snippet, 'path': fileDict.path});
		fileInfoArray.push(fileDict.md5);

		fileInfoArrayArray.push(fileInfoArray);
	}
	return fileInfoArrayArray;             
}

function checkFileAPI() {
	if (window.File && window.FileReader && window.FileList && window.Blob) {
		var reader = new FileReader();
		return true; 
	} else {
		alert('Браузер не поддерживает FileAPI. Рекомендуется обновить браузер.');
		return false;
	}
}

function parseXMLstrToJSON(XMLstr) {
  	var x2js = new X2JS();
    var json = "";
    json = x2js.xml_str2json(XMLstr);
    return json;
}

function loadjscssfile(filename, filetype){
 if (filetype=="js"){ //if filename is a external JavaScript file
  var fileref=document.createElement('script')
  fileref.setAttribute("type","text/javascript")
  fileref.setAttribute("src", filename)
 }
 else if (filetype=="css"){ //if filename is an external CSS file
  var fileref=document.createElement("link")
  fileref.setAttribute("rel", "stylesheet")
  fileref.setAttribute("type", "text/css")
  fileref.setAttribute("href", filename)
 }
 if (typeof fileref!="undefined")
  document.getElementsByTagName("head")[0].appendChild(fileref)
}


function turnOnTableScreen() {
    $('#uploadScreen').hide();		
    $('#tableScreen').show();

    loadjscssfile("js/analyzer.table.js", "js");

    $('.popup_name_flag').find('.list_js_inited').change(filterByFlags);
    $('.popup_name_columns').find('.list_js_inited').change(filterColumns);
    $('#dateMin').change( function() { console.log(this.value); filesDataTable.DataTable().draw(); } );
    $('#dateMax').change( function() { console.log(this.value); filesDataTable.DataTable().draw(); } );
    $('#filePathSearchFilter').keyup(function(){filesDataTable.fnFilter($('#filePathSearchFilter').val(), 1);}); 
}


