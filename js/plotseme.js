
var $spe=500;
var $na=document.getElementsByClassName('blink');

var $swi=1;
var $sho="hidden";

if ($na) blinkFunction();

// GET HTTP
function getHTTPObject() {
	var xmlhttp;
	xmlhttp = false;
    // branch for native XMLHttpRequest object
    if(window.XMLHttpRequest && !(window.ActiveXObject)) {
    	try {
			xmlhttp = new XMLHttpRequest();
        } catch(e) {
			xmlhttp = false;
        }
		// branch for IE/Windows ActiveX version
    } else if(window.ActiveXObject) {
       //	try {
        //	xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
      	//} catch(e) {
        	try {
          		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        	} catch(e) {
				alert ("Ajax not supported");
          		xmlhttp = false;
        	}
		//}
    }
	return xmlhttp;
}


// LOGIN _______________
function loginFunction()
{
	var xmlHttp = null;
	
	var $user= '?user='+document.getElementById('user').value;
	var $pass= '&password='+document.getElementById('password').value;
	
	if (document.getElementById('admin')){
		var $edit= '&admin='+document.getElementById('admin').value;
		
	} else if (document.getElementById('refer')) {
		var $edit= '&edit='+document.getElementById('refer').value;
		
	} else {
		var $edit= '&browse='+document.getElementById('refer').value;
	}
	
	
	if (!$edit){
		$edit = '';
	}
	
	var $url = 'index.php'; // default index.php
	
	//alert($url+$user+$pass+$edit);
	
	xmlHttp = getHTTPObject();
	xmlHttp.open("GET",$url+encodeURI($user+$pass+$edit+"&ajax"),true);
    xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
	xmlHttp.setRequestHeader("Cache-Control", "no-cache");
    xmlHttp.send(null);
    
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{
			document.body.innerHTML = xmlHttp.responseText;
        }
	}
}
// ADMIN _______________
function adminFunction()
{
	var xmlHttp = null;
	var $page_name = document.title;
	
	
	var $url = 'index.php'; // default index.php
	var $param = '?admin=';
	
	xmlHttp = getHTTPObject();
	xmlHttp.open("GET",$url+encodeURI($param+$page_name+"&ajax"),true);
    xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT"); // IE Ajax Cache Hack
	xmlHttp.setRequestHeader("Cache-Control", "no-cache"); // idem
    xmlHttp.send(null);
    
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{
			document.body.innerHTML = xmlHttp.responseText;
        }
	}
}

function newUserFunction()
{
	var xmlHttp;
	var $page_name = document.title;
	var $name = document.getElementById('user_name').value;
	var $login = document.getElementById('user_login').value;
	var $pass = document.getElementById('user_password').value;
	var $pass_confirm = document.getElementById('user_password_confirm').value;
	var $email = document.getElementById('user_email').value;
	var $description = document.getElementById('user_description').value;
	var newUserForm = document.getElementById('new_user_form');
	
	var $privilege = 0;
	
	var count= newUserForm.elements.length -1;
	
	for (var i = 0; i < count; ++i) { 
		if (newUserForm.elements[i].checked == true)
			{
			
				$privilege=newUserForm.elements[i].value;
			}
    }

	if ($login==''){
		alert("Login must be defined");
		return;
	}
	
	if ($pass==''){
		alert("Password must be defined");
		return;
	}
	
	
	if ($pass!=$pass_confirm){
		alert("Password confirmation doesn't match");
		return;
	}
	
	var $url = 'index.php'; // default index.php
	var $param = "admin="+$page_name+"&new_user=true&user_name="+$name+"&user_login="+$login+"&user_pass="+$pass+"&user_email="+$email+"&user_description="+$description+"&user_privilege="+$privilege;
	
	//alert($param);
	xmlHttp = getHTTPObject();
	xmlHttp.open("POST", $url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//xmlHttp.setRequestHeader("Content-length", $param.length);
	xmlHttp.setRequestHeader("Connection", "close");
	xmlHttp.send($param+"&ajax");
	
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState == 4) 
		{
			document.body.innerHTML = xmlHttp.responseText;
		}
	}
}


function delUserFunction()
{
	var xmlHttp;
	var $page_name = document.title;
	
	var delUserForm = document.getElementById('user_list_form');
	
	var count= delUserForm.elements.length -1;
	var idArray = new Array();;
	
	for (var i = 0; i < count; ++i) { 
		if (delUserForm.elements[i].checked == true)
			{
			
				idArray.push(delUserForm.elements[i].value);
			}
    }
	
	if (idArray.length <1){
		alert('No user selected !');
		return;
	}
	
	var answer = confirm("Please confirm deletion of the selected users");
	
	if (answer){
		
		var $url = 'index.php'; // default index.php
		var $param = "admin="+$page_name+"&del_user=true&user_id="+idArray.toString();
	
		//alert($param);
		xmlHttp = getHTTPObject();
		xmlHttp.open("POST", $url,true);
		xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		//xmlHttp.setRequestHeader("Content-length", $param.length);
		xmlHttp.setRequestHeader("Connection", "close");
		xmlHttp.send($param+"&ajax");
	
		xmlHttp.onreadystatechange=function()
		{
			if (xmlHttp.readyState == 4) 
			{
				document.body.innerHTML = xmlHttp.responseText;
			}
		}

	}
	
}

function dumpDBFunction()
{
	var xmlHttp;
	var $page_name = document.title;
	
	
	
	var $url = 'index.php'; // default index.php
	var $param = "admin="+$page_name+"&dump_db=true";

	//alert($param);
	xmlHttp = getHTTPObject();
	xmlHttp.open("POST", $url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//xmlHttp.setRequestHeader("Content-length", $param.length);
	xmlHttp.setRequestHeader("Connection", "close");
	xmlHttp.send($param+"&ajax");

	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState == 4) 
		{
			document.body.innerHTML = xmlHttp.responseText;
		}
	}

	
	
}


// EDIT _______________
function editFunction($page_name)
{
	var xmlHttp = null;
	//var $page_name = document.title;
	
	var $url = 'index.php'; // default index.php
	var $param = '?edit=';
	
	xmlHttp = getHTTPObject();
	xmlHttp.open("GET",$url+encodeURI($param+$page_name+"&ajax="),true);
	xmlHttp.setRequestHeader("Accept","text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5"); // IE Header Hack
	xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT"); // IE Cache Hack
	xmlHttp.setRequestHeader("Cache-Control", "no-cache"); // idem
    xmlHttp.send(null);
    
	//alert(xmlHttp==true);
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState == 4)
		{
			document.body.innerHTML = xmlHttp.responseText;
        } else {
			//alert (xmlHttp.readyState);
		}
	}
}


// SAVE _______________

function saveFunction()
{

	var xmlHttp;
	var $name = document.getElementById('name').value;
	var $author = document.getElementById('author_field').value;
	var $keywords = document.getElementById('keywords_field').value;
	var $template = document.getElementById('template_field').value;
	
	var $url = 'index.php'; // default index.php
	var $param = "content="+escape(encodeURI(document.getElementById('edit_field').value))+ "&save="+$name+ "&author="+$author+ "&keywords="+$keywords+ "&template="+$template;
	//alert($param);
	xmlHttp = getHTTPObject();
	xmlHttp.open("POST", $url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	//xmlHttp.setRequestHeader("Content-length", $param.length);
	xmlHttp.setRequestHeader("Connection", "close");
	xmlHttp.send($param+"&ajax");
	
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState == 4) 
		{
			document.getElementById('save_statut').innerHTML = xmlHttp.responseText;
		}
	}

}
function saveAndCloseFunction()
{
	var xmlHttp;
	var $name = document.getElementById('name').value;
	var $author = document.getElementById('author_field').value;
	var $keywords = document.getElementById('keywords_field').value;
	var $template = document.getElementById('template_field').value;
	
	var $url = 'index.php'; // default index.php
	var $param = "content="+escape(encodeURI(document.getElementById('edit_field').value))+ "&saveandclose="+$name+ "&author="+$author+ "&keywords="+$keywords+ "&template="+$template;
	//alert($param);
	xmlHttp = getHTTPObject();
	xmlHttp.open("POST", $url,true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
	//xmlHttp.setRequestHeader("Content-length", $param.length);
	xmlHttp.setRequestHeader("Connection", "close");
	xmlHttp.send($param+"&ajax");
	
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState == 4) 
		{
			document.body.innerHTML = xmlHttp.responseText;
		}
	}
}

// CANCEL _______________
function cancelFunction()
{
	var xmlHttp;
	var $page_name = document.title;
	
	var $url = 'index.php'; // default index.php
	var $param = '?browse=';
	
	xmlHttp = getHTTPObject();
	xmlHttp.open("GET",$url+encodeURI($param+$page_name+"&ajax"),true);
    xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT"); // IE Cach hack
	xmlHttp.setRequestHeader("Cache-Control", "no-cache"); // idem
    xmlHttp.send(null);
    
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{
			document.body.innerHTML = xmlHttp.responseText;
        }
	}
}

// BROWSE _______________
function fileBrowseFunction(path,sort_by)
{
	var xmlHttp;
	var $url = 'index.php';
	
	xmlHttp = getHTTPObject();
	xmlHttp.open("GET",$url+'?file_browse='+path+'&sort_by='+sort_by,true);
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

function fileDeleteFunction(path, name, sort_by)
{
	
	//alert(path+" -> "+name);
	
	var xmlHttp;
	var $url = 'index.php';
	
	xmlHttp = getHTTPObject();
	xmlHttp.open("GET",$url+'?file_delete='+name+'&file_browse='+path+'&sort_by='+sort_by,true);
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

function fileNewFolderFunction(path, name, sort_by)
{
	//alert(path+" -> "+name);
	
	var xmlHttp;
	var $url = 'index.php';
	
	xmlHttp = getHTTPObject();
	xmlHttp.open("GET",$url+'?file_new_folder='+name+'&file_browse='+path+'&sort_by='+sort_by,true);
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

// INSERT TAG _______________
function insertTag(openTag, closeTag) {
        var formName = document.getElementById('edit_field');
        
        if(document.selection) {
		if (navigator.userAgent.indexOf('msie') != -1) {
			if(formName.style.display != 'none' && formName.style.visibility != 'hidden') {
				formName.focus();
				sel = document.selection.createRange();
				sel.text = openTag + sel.text + closeTag;
			} else {
				formName.value += openTag + closeTag;
			}
		}//if ie
		else {
			formName.focus();
			sel = document.selection.createRange();
			sel.text = openTag + sel.text + closeTag;
		}
	}
	else if(formName.selectionStart || formName.selectionStart == '0') {
		var startPos = formName.selectionStart;
		var endPos = formName.selectionEnd;
		cursorPos = startPos + openTag.length;
		formName.value = formName.value.substring(0, startPos) + openTag + formName.value.substring(startPos, endPos) + closeTag + formName.value.substring(endPos, formName.value.length);
		formName.focus();
		formName.setSelectionRange(cursorPos, cursorPos);
	} else {
		formName.value += openTag + closeTag;
	}
  }
  
function searchFunction() {
	var xmlHttp;
	var $page_name = document.title;
	
	var $url = 'index.php'; // default index.php
	var $param = '?search=';
	
	var $search_string = document.getElementById('search_text').value;
	
	xmlHttp = getHTTPObject();
	xmlHttp.open("GET",$url+encodeURI($param+$search_string+"&ajax"),true);
    xmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");  // IE Cach hack
	xmlHttp.setRequestHeader("Cache-Control", "no-cache"); // idem
    xmlHttp.send(null);
    
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{
			document.body.innerHTML = xmlHttp.responseText;
        }
	}
}

function addToKeywordsFieldFunction($aValue){
	var field = document.getElementById('keywords_field');
	//alert (field.value);
	if (field.value !=""){
		field.value = field.value + "," + $aValue;
	} else {
		field.value = $aValue;
	}
}

function blinkFunction() {
	
	if ($swi == 1) {
		$sho="visible";
		$swi=0;
	}
	else {
		$sho="hidden";
		$swi=1;
	}
	
	for(i=0;i<$na.length;i++) {
		$na[i].style.visibility=$sho;
	}
	
	setTimeout("blinkFunction()", $spe);
}

function addslashes(str) {
	str=str.replace(/\'/g,'\\\'');
	str=str.replace(/\"/g,'\\"');
					str=str.replace(/\\/g,'\\\\');
					str=str.replace(/\0/g,'\\0');
	return str;
}

