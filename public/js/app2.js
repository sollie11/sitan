console.log('app2.js');
var JSall = {
iActiveMenu: 0,

alerttimeout: function(){
	window.setTimeout(function(){
		var eA = document.getElementsByClassName("alert")[0]; 
		eA.title = eA.children[0].innerHTML; eA.innerHTML=".&nbsp;";
	}, 3000);
},

clickmenu: function(sCat, sAction){
	return function(){
		var bDone;
		bDone = 0;
		console.log(sCat + " / " + sAction);
		switch (sAction){
			case "logout":
				event.preventDefault();
			    document.getElementById("logout-form").submit();
			    bDone = 1;	
			break;
		}
		if (!bDone){
			window.location.href = "/" + sCat + "/" + sAction;
		}
	}
},

clickwindow: function(oEvent) {
	var eTarget, eNav;
	eTarget = oEvent.target
  if (!eTarget.matches('.w3dbtn')) {
  var eNav = document.getElementById("nav_" + iActiveMenu);
    if (eNav.classList.contains('w3show')) {
      eNav.classList.remove('w3show');
    }
  }
},

initall: function(){
	JSall.init();
	JSgrid.init();
	window.onclick = JSall.clickwindow;
},

init: function(){
	var aE, aF, eG, eH, sA, sB, iI, iJ;
	aE = JSgrid.gcn("w3ddown-content");
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
			eH.onclick = JSall.clickmenu(sB, sA);
			iI++;
		}
	});
	aE = JSgrid.gcn("w3dbtn");
	iI = 0;
	aE.forEach(function(eG){
		eG.onclick = JSall.menu('nav_' + iI);
		iI++;
	});
},

menu: function(sID) {
	return function(){
		var eDD, aL, aM, iI;
		eDD = document.getElementById(sID);
		iActiveMenu = parseInt(sID.split("_")[1]);
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
},


};

var JSgrid = {
		
		oGet: {},


//==========================
action: function(sAction, sURL){
	var iAnswer, iID;
	iID = JSgrid.dg("id").value;
	sURL = "/" + sURL + "/" + iID + "/" +sAction;
	console.log(sURL)
	window.location.href = sURL;
},

//==========================
editdelete: function(sName, sURL){
	var iAnswer, iID;
	iAnswer = confirm("Click OK to delete this " + sName);
	if (iAnswer == true){
		iID = JSgrid.dg("id").value;
		window.location.href = "delete/" + iID;
	} else {
	}
},

//==========================
init: function(){
	var aEles, iI, aGet, aParam, aID, that;
	aEles = JSgrid.gcn("gridtable");
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
	case "ques":
		window.location.href = "questionnaires/" + iID;
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
/*		sDate = eEle.childNodes[5].textContent;
		if (sDate){ */
			window.location.href = "clients/" + iID;
	//	}		
	break;
	}
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

//==========================
dg: function(sName){
	return document.getElementById(sName);
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
	eForm = JSupload.ele(eBody, "", "form");
	eForm.enctype ="multipart/form-data";
	eForm.method="post";
	eForm.action="../uploading.php";
	eX = JSupload.ele(eBody, "upload", "table");
	eY = JSupload.ele(eX, "", "tr");
	eZ = JSupload.ele(eY, "", "td");
	eW = JSupload.ele(eZ);
	eZ.innerHTML = sHeading;
	eY = JSupload.ele(eX, "", "tr");
	eZ = JSupload.ele(eY, "", "td");
	eV = JSupload.ele(eZ, "upload-btn-wrapper");
	eW = JSupload.ele(eV, "upload-btn", "button");
	eW.innerHTML = "Select file";
	eBrowse = JSupload.ele(eV, "", "input");
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
	eY = JSupload.ele(eX, "", "tr");
	eZ = JSupload.ele(eY, "", "td");
	eFilename = JSupload.ele(eZ);
	eY = JSupload.ele(eX, "", "tr");
	eZ = JSupload.ele(eY, "", "td");
	eZ.style.height = "34px";
	eW = JSupload.ele(eZ);
	eW.style.width = "100%";
	eW.style.height = "100%";
	eProgress = JSupload.ele(eW, "upload", "progress");
	eProgress.max = "100";
	eFilesize = JSupload.ele(eW, "uploadfilesize");
	eY = JSupload.ele(eX, "", "tr");
	eZ = JSupload.ele(eY, "", "td");
	eStart = JSupload.ele(eZ, "", "button");
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


};


window.onload = JSall.initall;

