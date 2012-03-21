<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>oscommerce</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="mymenu.css" type="text/css">
<? 

   $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
	
        $rows_count = tep_db_num_rows($categories_query);
		
		if ($rows_count > 0) 
		 {
		     		 	    
		    while ($categories = tep_db_fetch_array($categories_query)) 
			  {
			      
				 $cat_id[]=$categories[categories_id];
				 $cat_name[]=$categories[categories_name];
				  
				   
				}
		}
		       
	   $k=count($cat_id);	
	 	 			 
		for($j=0;$j<=$k;$j++)
		{
		?>			 				 		  
			     
				 <script type="text/javascript">									
									
					var n<? echo $j+1 ?>=new Array()
				</script>
				<?
									
				$sql="select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id ='" .$cat_id[$j] ."' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name";
				
				 $categories_query1 = tep_db_query($sql);
				 
				        $p=0;				
					    while ($categories2 = tep_db_fetch_array($categories_query1)) 
						  {
						     ?>
							 
									
									<script type="text/javascript">					
									
									<? echo "n" .($j+1) ?>[<? echo $p ?>]='&nbsp;&nbsp;<a href="index.php?cPath=<? echo $cat_id[$j] ."_" .$categories2[categories_id]?> "><font color="#ffffff"><? echo $categories2[categories_name]?></font></a><br>'
									
									
									
									</script>						  
<?							 $p++;
							 }
							 
							 
				}			 
				            
?>


<script type="text/javascript">
var menuwidth='250px' //default menu width
var menubgcolor='FFFFFF'  //menu bgcolor
var disappeardelay=250  //menu disappear speed onMouseout (in miliseconds)
var hidemenu_onclick="yes" //hide menu when user clicks within menu?

/////No further editting needed

var ie4=document.all
var ns6=document.getElementById&&!document.all

if (ie4||ns6)
document.write('<div id="dropmenudiv" style="visibility:hidden;width:'+menuwidth+';background-color:'+menubgcolor+'" onMouseover="clearhidemenu()" onMouseout="dynamichide(event)"></div>')

function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}


function showhide(obj, e, visible, hidden, menuwidth){
if (ie4||ns6)
dropmenuobj.style.left=dropmenuobj.style.top=-500
if (menuwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=menuwidth
}
if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover")
obj.visibility=visible
else if (e.type=="click")
obj.visibility=hidden
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=0
if (whichedge=="rightedge"){
var windowedge=ie4 && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth
}
else{
var windowedge=ie4 && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
}
return edgeoffset
}

function populatemenu(what){
if (ie4||ns6)
dropmenuobj.innerHTML=what.join("")
}


function dropdownmenu(obj, e, menucontents, menuwidth){
if (window.event) event.cancelBubble=true
else if (e.stopPropagation) e.stopPropagation()
clearhidemenu()
dropmenuobj=document.getElementById? document.getElementById("dropmenudiv") : dropmenudiv
populatemenu(menucontents)

if (ie4||ns6){
showhide(dropmenuobj.style, e, "visible", "hidden", menuwidth)
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+"px"
}

return clickreturnvalue()
}

function clickreturnvalue(){
if (ie4||ns6) return false
else return true
}

function contains_ns6(a, b) {
while (b.parentNode)
if ((b = b.parentNode) == a)
return true;
return false;
}

function dynamichide(e){
if (ie4&&!dropmenuobj.contains(e.toElement))
delayhidemenu()
else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6(e.currentTarget, e.relatedTarget))
delayhidemenu()
}

function hidemenu(e){
if (typeof dropmenuobj!="undefined"){
if (ie4||ns6)
dropmenuobj.style.visibility="hidden"
}
}

function delayhidemenu(){
if (ie4||ns6)
delayhide=setTimeout("hidemenu()",disappeardelay)
}

function clearhidemenu(){
if (typeof delayhide!="undefined")
clearTimeout(delayhide)
}

if (hidemenu_onclick=="yes")
document.onclick=hidemenu

</script>
</head>
<body>

<table width="760" border="0" align="center" cellspacing="0" cellpadding="2">  
  <tr>
    <td height="0" colspan="3">	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#336699
">
		<tr>
	      <td align="center"> <img src="images/arrow.gif" width="10" height="10">&nbsp;<a style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold ;color: #ffffff" href="index.php">Home</a></td>

<? 
  $temp=count($cat_name);
  
  for($t=0;$t<$temp;$t++)
  {
    
?>   
	      <td align="center"> <img src="images/arrow.gif" width="10" height="10">&nbsp;<a style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold ;color: #ffffff" href="index.php?cPath=<? echo $cat_id[$t] ?> "  onMouseover="dropdownmenu(this, event, <? echo "n" .($t+1) ?> , '180px')" onMouseout="delayhidemenu()">
            <? echo $cat_name[$t] ?>
            </a></td>
		<?
		}
?>
			
	      <td align="center"> <img src="images/arrow.gif" width="10" height="10">&nbsp;<a style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold ;color: #ffffff" href="sitemap.php">Sitemap</a></td>
	</tr>
</table>	
	</td>
  </tr>  
</table>
</body>
</html>