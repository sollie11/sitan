console.log('app2.js');
var JSall = {
iActiveMenu: 0,

//==========================
ajax: function(oRequest, fCallback){
	var oXHR, sResponse, sURL, sCsrfToken;
	oXHR = new XMLHttpRequest();
	oXHR.onreadystatechange = function(){
		if (oXHR.readyState == XMLHttpRequest.DONE){ 
			if (oXHR.status == 200) { 
				sResponse = oXHR.responseText;
				if (fCallback){
					fCallback(oXHR.responseText);
				}
			} else if (oXHR.status == 400){
				console.log('AJAX error' + oXHR.status);
			}
		}
	}
	sURL = window.location.href;
	if (oRequest.sURL){
		sURL += oRequest.sURL;
	}
	sCsrfToken = JSall.csrf();
	if (!sCsrfToken){
		return;
	}
	oXHR.open("post", sURL);
	oXHR.setRequestHeader("Access-Control-Allow-Origin", 
		sURL + ":80");
    oXHR.setRequestHeader("Access-Control-Allow-Headers", 
    	"Access-Control-Allow-Origin, X-CSRF-TOKEN, Origin, " + 
    	"X-Requested-With, Content-Type, " + 
    	"Accept, Access-Control-Request-Method, Authorization");
	oXHR.setRequestHeader("Content-type", 
		"application/x-www-form-urlencoded; charset=UTF-8");
	oXHR.setRequestHeader("Access-Control-Allow-Methods", 
		"GET, POST");//, OPTIONS, PUT, DELETE");
	oXHR.setRequestHeader("X-CSRF-TOKEN", sCsrfToken);
	oXHR.send("a=" + btoa(JSON.stringify(oRequest)));
},

clickwindow: function(oEvent) {
	var eTarget, eNav;
	eTarget = oEvent.target
  if (!eTarget.matches('.w3dbtn')) {
  var eNav = document.getElementById("nav_" + JSall.iActiveMenu);
    if (eNav.classList.contains('w3show')) {
      eNav.classList.remove('w3show');
    }
  }
},


cookiedelete: function(name) {   
    document.cookie = name+"=; Max-Age=-99999999;";  
},



cookieget: function(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==" ") c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
},



cookiereset: function(){
	PPJS.cookieset("sagepp_token", 0);
	PPJS.cookieset("sagepp_places", 0);
	PPJS.cookieset("sagepp_rates", 0);
	PPJS.cookieset("sagepp_place", 0);
	PPJS.cookieset("sagepp_rate", 0);
	PPJS.cookieset("sagepp_ratevalue", 0);
},

cookieset: function(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
},


csrf: function(){
	var aMeta, sCsrfToken, iI, oRec, oA;
	aMeta = document.getElementsByTagName('meta');
	iI = 0;
	sCsrfToken = "";
	while (aMeta[iI]){
		oRec = aMeta[iI];
		oA = oRec.attributes
		if ((oA.name) && (oA.name.nodeValue == 'csrf-token')){
			sCsrfToken = oRec.getAttribute("content");
		}
		iI++;
	}
	return sCsrfToken;
},

//==========================
deleterecord: function(sAction, sURL, iID){
	var iAnswer, eX, eY;
	iAnswer = confirm("Click OK to delete this " + sAction);
	if (iAnswer) {
		eX = JSall.dg("formdelete");
		if (!eX){
			return;
		}
		eX = JSall.ele(eX,"","form");
		eX.action= "/" + sURL + "/delete/save";
		eX.method = "post";
		eY = JSall.ele(eX, "", "input");
		eY.type = "hidden";
		eY.id = "id";
		eY.name = "id";
		eY.value = iID;
		eY = JSall.ele(eX, "", "input");
		eY.type = "hidden";
		eY.name = "_token"
		eY.value = JSall.csrf();
		
		eY = JSall.ele(eX,"","input");
		eY.type = "submit";
		eY.click()
		
//		window.location.href = "/" + sURL + "/delete/" + iID;
	}
},


//==========================
dg: function(sName){
	return document.getElementById(sName);
},

ele: function(eParent, sClass, sType, sID){
	var eEle;
	if (!sType){
		sType = "div";
	}
	eEle = document.createElement(sType);
	if (sClass){
		eEle.className = sClass;
	}
	if (sID){
		eEle.id = sID;
	}
	if (eParent){
		eParent.appendChild(eEle);
	}
	return eEle;
},

//==========================
gcn: function(sName){
	var aEles, aEles1, iI;
	aEles = [];
	iI = 0;
	aEles1 = document.getElementsByClassName(sName);
	while (aEles1[iI]){
		aEles.push(aEles1[iI]);
		iI++;
	}
	return aEles;
},


initall: function(){
	JSall.init();
	JSgrid.init();
//	JSclient.init();
	window.onclick = JSall.clickwindow;
},

init: function(){
	var aE, aF, eG, eH, sA, sB, iI, iJ;
	aE = JSall.gcn("w3ddown-content");
	aE.forEach(function(eG){
		aF = eG.children;
		iI = 0;
		while (aF[iI]){
			eH = aF[iI];
			sA = (eH.innerHTML).replace(/ /g, "").toLowerCase();
			if (eH.attributes["menu"]){
				sA = eH.attributes["menu"].nodeValue;
			}
			sB = eH.parentNode.attributes['menu'].nodeValue;
			eH.onclick = JSall.menu(sB, sA);
			iI++;
		}
	});
	aE = JSall.gcn("w3dbtn");
	iI = 0;
	aE.forEach(function(eG){
		eG.onclick = JSall.menu('nav_' + iI);
		iI++;
	});
},

menu: function(sCat, sAction) {
	return function(){
		var eDD, aL, aM, iI, iJ, sMenu, bDone;		
		if (sAction){
			bDone = 0;
			console.log(sCat + " / " + sAction);
			switch (sAction){
				case "logout":
					event.preventDefault();
				    document.getElementById("logout-form").submit();
				    window.location.href = "http://p.php82/logout";
				    bDone = 1;	
				break;
			}
			if (!bDone){
				window.location.href = "/" + sCat + "/" + sAction;
			}
			return;
		}
		sID = sCat;
		eDD = document.getElementById(sID);
		JSall.iActiveMenu = parseInt(sID.split("_")[1]);
	    if (eDD.classList.contains('client')) {
	    	sMenu = eDD.innerText;
	    	if (eDD.attributes["menu"]){
	    		sMenu = eDD.attributes["menu"].nodeValue;
	    	}
	    }
	    if (sMenu){
	    	iI = parseInt(JSall.cookieget("sitan_pageno"));
	    	iJ = 0;
	    	switch (sMenu){
		    	case "client_formprevious":
		    		iJ = 1;
		    		iI--;
		    		if (iI < iCatFirst){
		    			iI = iCatLast;
		    		}
		    	break;
		    	case "client_formnext":
		    		iJ = 1;
		    		iI++;
		    		if (iI > iCatLast){
		    			iI = iCatFirst;
		    		}
		    	break;
		    	case "client_results":
		    		window.location.href = "/form/results";
		    	break;
		    	case "client_home":
		    		window.location.href = "/home";
		    	break;
	    	}
	    	if (iJ){
		    	JSall.cookieset("sitan_pageno", iI);
		    	window.location.href = "/form/page/" + iI;	    		
	    	}
	    } else {
			aM = document.getElementsByClassName("w3show");
			if (aM[0]){
				iI =0;
				while (aM[iI]){
					aL = aM[iI].classList;
					aL.toggle("w3show");
					iI++;
				}
			}
			aL = eDD.classList;
			aL.toggle("w3show");
	    }
	}
},


};

var JSclient = {

init: function(){
},

};


var JSform = {

iPageNo: 1,//iCatFirst,

init: function(){
	var iPageNo;
	iPageNo = JSall.cookieget("sitan_pageno");
	if (!iPageNo){
		iPageNo = JSform.iPageNo;
		JSall.cookieset("sitan_pageno", iPageNo);
	}
	console.log("JSform: page " + JSform.iPageNo);
},

formoptionA: function(oData){
	console.log(oData);
},

formoption: function(oEvent){
	var iCat, iQuestion, iOption, aID, eX;
	eX = event.target;
	aID = (eX.id).split("_");
	iOpt  = parseInt(eX.children[eX.selectedIndex].value);
	iCat = parseInt(aID[1]);
	iQuestion = parseInt(aID[2]);
	JSall.ajax({
		sAction: "formoption",
		iCat: iCat,
		iQuestion: iQuestion,
		iOption: iOpt,
	}, JSform.formoptionA);
	console.log("formoption"+iOpt)

},

};

var JSgrid = {
		
		oGet: {},


//==========================
action: function(sAction, sURL){
	var iAnswer, iID;
	iID = JSall.dg("id").value;
	sURL = "/" + sURL + "/" + iID + "/" +sAction;
	console.log(sURL)
	window.location.href = sURL;
},

//==========================
editdelete: function(sName, sURL){
	var iAnswer, iID;
	iAnswer = confirm("Click OK to delete this " + sName);
	if (iAnswer == true){
		iID = JSall.dg("id").value;
		window.location.href = "delete/" + iID;
	} else {
	}
},

//==========================
init: function(){
	var aEles, iI, aGet, aParam, aID, that;
	aEles = JSall.gcn("gridtable");
	if (aEles[0]){
		aEles[0].onclick = JSgrid.clickrow;
		aGet = window.location.href.split("grid=");
		if (aGet[1]){
			aGet = atob(aGet[1]);
			aGet = aGet.split("&");
			aGet.forEach(function(oRec){
				aParam = oRec.split("=");
				JSgrid.oGet[aParam[0]] = aParam[1];
			});
		}
	}
},

//======================
clickrow: function(oEvent){
	var eEle, eEle1, eCheck, aID, sDate, oParm;
	eEle = oEvent.target;
	eEle1 = eEle;
	while ((eEle) && (eEle.className != "trgrid")){
		eEle = eEle.parentNode;
	}
	if (!eEle){
		eEle = oEvent.target;
		while ((eEle) && (eEle.className != "gridheading")){
			eEle = eEle.parentNode;
		}
		if (!eEle){
			return;
		}
		JSgrid.sort(parseInt(eEle.id.split("_")[1]));
	}
	aID = eEle.id.split("_");
	iID = parseInt(aID[1]);
	switch (aID[0]){
	case "aqq":
		eCheck = eEle.childNodes[0].childNodes[0];
		if (eCheck == eEle1){
			sUrl = window.location.href.split("/");
			sUrl = sUrl[(sUrl.length - 2)];
			oParm = { iQID: iID, iQNID: parseInt(sUrl), };
			if (eCheck.checked){
				oParm.bCheck = 1;
			} else {
				oParm.bCheck = 0;
			}
			JSform.ajax(JSON.stringify(oParm), 0, "questionquestionnaire");
		}
	break;
	case "prog":
		window.location.href = "programmes/" + iID;
	break;
	case "qcat":
		window.location.href = "questioncategories/" + iID;
	break;
	case "quen":
		window.location.href = "/questions/questionnaires/" + iID;
	break;
	case "quess":
		window.location.href = "questions/" + iID;
	break;
	case "option":
		window.location.href = "questionsoptions/" + iID;
	break;
	case "users":
		window.location.href = "users/" + iID;
	break;
	case "emailforms":
		window.location.href = "emailforms/" + iID;
	break;
	case "client":
			window.location.href = "clients/" + iID;
	break;
	case "newcl":
		window.location.href = "newclients/" + iID;
	break;
	case "newqs":
		window.location.href = "/questions/new/" + iID;
	break;
	}
},

//==========================
page: function(sAction, iPages){
	if (!JSgrid.oGet.page){
		JSgrid.oGet.page = 1;
	}
	switch (sAction){
		case "first":
			JSgrid.oGet.page = 1;
		break;
		case "last":
			JSgrid.oGet.page = iPages;
		break;
		case "next":
			JSgrid.oGet.page++;
			if (JSgrid.oGet.page > iPages){
				JSgrid.oGet.page = iPages;
			}
		break;
		case "previous":
			JSgrid.oGet.page--;
			if (JSgrid.oGet.page < 1){
				JSgrid.oGet.page = 1;
			}
		break;
	};
	JSgrid.url();
},

//==========================
search: function(){
	if (event.keyCode == 13){
		sText = document.getElementById("gridfilter").value;
		JSgrid.oGet.search = encodeURI(sText);
		JSgrid.oGet.page = 1;
		JSgrid.url();
	}
},

//==========================
searchclear: function(){
	var eEle;
	eEle = document.getElementById("gridfilter");
	eEle.value = "";
	JSgrid.oGet.search = "";
	JSgrid.oGet.page = 1;
	JSgrid.url();
},

//==========================
sort: function(iColumn){
//	return function(){
		if (JSgrid.oGet.sort == iColumn + 1){
			if (JSgrid.oGet.dir == 2){
				JSgrid.oGet.dir = 1;
			} else {
				JSgrid.oGet.dir = 2;
			}
		} else {
			JSgrid.oGet.sort = iColumn + 1;
			JSgrid.oGet.dir = 2;
		}
		
		JSgrid.url();
//	}
},

//==========================
url: function(){
	var sURL;
	sURL = "";
	if (JSgrid.oGet.sort){
		sURL += "sort=" + JSgrid.oGet.sort + "&";
	}
	if (JSgrid.oGet.dir){
		sURL += "dir=" + JSgrid.oGet.dir + "&";
	}
	if (JSgrid.oGet.page){
		sURL += "page=" + JSgrid.oGet.page + "&";
	}
	if (JSgrid.oGet.search){
		sURL += "search=" + JSgrid.oGet.search + "&";
	}
	if (sURL){
		sURL = sURL.substr(0, sURL.length - 1);	
	}
	window.location.href = "?grid=" + btoa(sURL);
},

};



var JSupload = {
oEles: {
	eBody: 0, eForm: 0, eBrowse: 0, eStart: 0, eProgress: 0, eFilesize: 0,
},
uploadformreset: function(sResponse, sColor, eBrowse, eFilename, eFilesize, eStart, eProgress){
	var iDelay;
	if (!sResponse){
		iDelay = 0;
	} else {
		iDelay = 1500;
	}
	eFilename.parentNode.style.background = sColor;
	eFilesize.textContent = sResponse;
	window.setTimeout(function(){
		if (eBrowse.files.length){
			eBrowse.value = "";
		}
		eFilename.innerHTML = "&nbsp;";
		eFilename.parentNode.style.background = "none";
		eFilesize.textContent = "";
		eStart.innerHTML = "Upload";
		eStart.disabled = "disabled";
		eProgress.value = "0";
		eProgress.style.display = "none";
	}, iDelay);
},

uploadform: function(sBodyClass, fCallback, sHeading, sPage, sExts){
	var eV, eW, eX, eY, eZ, eBody, eForm, eBrowse, eStart, eProgress, eFilesize,
		bBusy, oFD, oXHR, iPerc;
	eBody = document.getElementsByClassName(sBodyClass)[0];
	eForm = JSall.ele(eBody, "", "form");
	eForm.enctype ="multipart/form-data";
	eForm.method="post";
	eForm.action="../uploading.php";
	eX = JSall.ele(eBody, "upload", "table");
	eY = JSall.ele(eX, "", "tr");
	eZ = JSall.ele(eY, "", "td");
	eW = JSall.ele(eZ);
	eZ.innerHTML = sHeading;
	eY = JSall.ele(eX, "", "tr");
	eZ = JSall.ele(eY, "", "td");
	eV = JSall.ele(eZ, "upload-btn-wrapper");
	eW = JSall.ele(eV, "upload-btn", "button");
	eW.innerHTML = "Select file";
	eBrowse = JSall.ele(eV, "", "input");
	eBrowse.type = "file";
	eBrowse.name = "filetoupload";
	eBrowse.onchange = function(){
		bBusy = 0;
		var oFile, iFileSize, iH, sExt, sFilename, bError;
		oFile = eBrowse.files[0];
		if (oFile) {
			bError = 0;
			eProgress.style.display = "block";
			iH = parseInt(eProgress.clientHeight);
			eFilesize.style.marginTop = (12 - iH) + "px";
			sFilename = oFile.name;
			aExts = sExts.split(",");
			sExt = oFile.name.split('.').pop().toLowerCase();
			if (aExts.indexOf(sExt) == -1){
				bError = 1;
				sFilename = "Invalid file extension"
				JSupload.uploadformreset("Error", "#ff0000", eBrowse,
					eFilename, eFilesize, eStart, eProgress);
			}
			if (!bError){
				eStart.disabled = "";
			}
			iFileSize = oFile.size;
			if (iFileSize > 1024 * 1024){
				iFileSize = (Math.round(iFileSize * 100 /
					(1024 * 1024)) / 100).toString() + 'MB';
			} else {
				iFileSize = (Math.round(iFileSize * 100 /
					1024) / 100).toString() + 'KB';
			}
			eFilename.textContent = sFilename;
			eFilesize.textContent = iFileSize;
		}
	};
	eY = JSall.ele(eX, "", "tr");
	eZ = JSall.ele(eY, "", "td");
	eFilename = JSall.ele(eZ);
	eY = JSall.ele(eX, "", "tr");
	eZ = JSall.ele(eY, "", "td");
	eZ.style.height = "34px";
	eW = JSall.ele(eZ);
	eW.style.width = "100%";
	eW.style.height = "100%";
	eProgress = JSall.ele(eW, "upload", "progress");
	eProgress.max = "100";
	eFilesize = JSall.ele(eW, "uploadfilesize");
	eY = JSall.ele(eX, "", "tr");
	eZ = JSall.ele(eY, "", "td");
	eStart = JSall.ele(eZ, "", "button");
	eStart.type = "button";
	eStart.style.width = "120px";
	eStart.style.height= "27px";
	eStart.onclick = function(){
		if (bBusy){
			oXHR.abort();
			return;
		}
		bBusy = 1;
		eStart.innerHTML = "Cancel";
		oFD = new FormData();
		oFD.append("filetoupload", eBrowse.files[0]);
		oFD.append("page", sPage);
		oXHR = new XMLHttpRequest();
		oXHR.upload.onprogress = function(oEvent) {
			if (oEvent.lengthComputable) {
				iPerc = Math.round(oEvent.loaded * 100 / oEvent.total);
				eProgress.value = iPerc.toString();
			}
		};
		oXHR.onload = function(oEvent){
			var oResponse, sMessage, sColor;
			oResponse = oEvent.target.responseText;
			if (oResponse[0] != "<"){
				oResponse = JSON.parse(atob(oResponse));
				JSupload.uploadformreset(oResponse.sMessage, oResponse.sColor,
					eBrowse, eFilename, eFilesize, eStart, eProgress);
				if (oResponse.bSuccess){
					fCallback(oEvent.target.responseText);
				}
			} else {
				document.write(oResponse);					
			}
		}
		oXHR.onerror = function(oEvent) {
			JSupload.uploadformreset("Error", "#ff0000", eBrowse, eFilename, eFilesize, eStart, eProgress);
		}
		oXHR.onabort = function(oEvent) {
			JSupload.uploadformreset("Cancel", "#ff0000", eBrowse, eFilename, eFilesize, eStart, eProgress);
		}
		oXHR.open("POST", "../uploading.php");
		oXHR.send(oFD);
	};
	JSupload.uploadformreset("", "#ffffff", eBrowse, eFilename, eFilesize, eStart, eProgress);
},

};


window.onload = JSall.initall;

