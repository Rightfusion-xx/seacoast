<?php

  require('../includes/application_top.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>Seacoast Commander</title>
</head>

<script>

    function loadpage()
    {
        try
        {//top.commander_seacoast.document.getElementById("content").style.border="solid 1px red";
        }
            catch(e){}
        top.commander_title_bar.document.getElementById("heading").innerHTML=top.commander_seacoast.document.title;
        top.document.title=top.commander_seacoast.document.title;
        
        //add stylesheet
        var headID = top.commander_seacoast.document.getElementsByTagName("head")[0];         
        var cssNode = top.commander_seacoast.document.createElement('link');
        cssNode.type = 'text/css';
        cssNode.rel = 'stylesheet';
        cssNode.href = '/admin/commander/commander_seacoast.css';
        cssNode.media = 'screen';
        headID.appendChild(cssNode);
        
        
    }

</script>


<frameset rows="10%,90%">
    <frame src="commander_title_bar.php" name="commander_title_bar"/>
    <frame src="<?php echo HTTP_SERVER; ?>" id="admin_seacoast" name="commander_seacoast" onload="javascript:loadpage();"/>


</frameset>
</html>
