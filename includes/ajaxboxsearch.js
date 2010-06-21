<script language="javascript"><!--

var req, name; 

function loadBoxXMLDoc(key,field) {
  
   name = field;
   var url="quickbox.php?keywords="+key;

   // Internet Explorer
   try { req = new ActiveXObject("Msxml2.XMLHTTP"); } 
   catch(e) { 
      try { req = new ActiveXObject("Microsoft.XMLHTTP"); } 
      catch(oc) { req = null; } 
   } 

   // Mozailla/Safari 
   if (!req && typeof XMLHttpRequest != "undefined") { req = new XMLHttpRequest(); } 

   // Call the processChange() function when the page has loaded 
   if (req != null) {
      req.onreadystatechange = processBoxChange; 
      req.open("GET", url, true); 
      req.send(null); 
   } 
} 

function processBoxChange() {
   if (req.readyState == 4 && req.status == 200) 
      getObject(name).innerHTML = req.responseText; 
} 
//--></script>