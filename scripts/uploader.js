var http=createRequestObject();
var uploader="";
var uploadDir="";
var dirname="";
var filename="";
var timeInterval="";
var idname="";
var uploaderId="";

function createRequestObject() {
    var obj;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
    	return new ActiveXObject("Microsoft.XMLHTTP");
    }
    else{
    	return new XMLHttpRequest();
    }   
}
function traceUpload() {
   http.onreadystatechange = handleResponse;

   http.open("GET", 'php/file_upload.php?dirname='+dirname+'&filename='+filename+'&uploader='+uploader); 
   http.send(null);   
}

function handleResponse() {
				
	if(http.readyState == 4){
		var response=http.responseText; 
		if(response.indexOf("upload_done") != -1){
			clearInterval(timeInterval);
			//document.getElementById("process_upload_list").innerHTML = response;
			document.getElementById("process_upload_animation").innerHTML="";
			document.getElementById("process_upload_button").disabled = false;
			fileBrowseFunction(dirname,"name");
			
		} else {
			document.getElementById("process_upload_animation").innerHTML=response;
			document.getElementById("process_upload_button").disabled = true; 
		}
			
    }
    else {
    	
		//document.getElementById(uploaderId).innerHTML="Uploading File. Please wait...";
    }
}
function uploadFile(obj, dname) {
	uploadDir=obj.value;
	idname=obj.name;
	dirname=dname;
	filename=uploadDir.substr(uploadDir.lastIndexOf('\\')+1);
	uploaderId = 'uploader'+obj.name;
	uploader = obj.name;
	document.getElementById('formName'+obj.name).submit();
	timeInterval=setInterval("traceUpload()", 1500);
}

function fileBrowseFunction(path,sort_by)
{
	var xmlHttp;
	
	xmlHttp = getHTTPObject();
	xmlHttp.open("GET",'index.php?file_browse='+encodeURIComponent(path)+'&sort_by='+sort_by,true);
    xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
	xmlHttp.setRequestHeader("Cache-Control", "no-cache");
    xmlHttp.send(null);
    
    //document.getElementById('file_browser').innerHTML="Loading. Please wait...";
    
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{
			document.getElementById('file_browser').innerHTML = xmlHttp.responseText;
        }
	}
}