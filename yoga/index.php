<?php


  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>

  <title>Yoga Asanas, Poses & Postures</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="Keywords" content="yoga, asanas, poses, yoga postures, yoga health"/>
<meta name="Description" content="Lookup hundreds of yoga asanas, poses & postures for strength, breathing & meditation."/>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

 

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD VALIGN="top" rowspan="2">

	  </TD>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->

    <td width="100%" valign="top">
    <div id="content">
    
    <h1>Yoga Asanas, Poses & Postures</h1>
    <p>
        Select from yoga asanas, poses & postures from numerous schools of yoga. Featured sequences take you from warm-ups to sitting & standing positions
        with emphasis on recovery poses, meditation and breathing. 
    </p>
    
    <h2>Yoga Sequences & Routines</h2>
    <p>Practice begins with an individuals openess and discipline. Warm up with the following poses, or 
    master strength and dexterity. Center yourself with meditation, relaxation poses, and breathing.</p>
    <ol>
    <?php 
        $mcd_query=tep_db_query("select count(*) as num, sequence from 
                    yoga.poses p 
                    group by sequence
                    order by num desc, sequence asc
                    LIMIT 0,50");
       while ($drug = tep_db_fetch_array($mcd_query))
       {
            echo '<li><a href="/yoga/routine.php?sequence='.strtolower(urlencode($drug['sequence'])).'">'.ucwords(strtolower($drug['sequence'])).'</a></li>';
       }
                    
        
    ?>
    </ol>
    
  
    <h2>Types of Poses</h2>
    <p>Poses come in all types of variety from novist asanas to teacher and master ability. Use the following 
    skill ranked groups to locate the yoga that most pertains to your level of expertise and desire for 
    extending your boundaries.</p>
    <ol>
    <?php 
        $mcd_query=tep_db_query("select count(*) as num, difficulty from 
                    yoga.poses p 
                    group by difficulty
                    order by num desc, sequence asc
                    LIMIT 0,50");
       while ($drug = tep_db_fetch_array($mcd_query))
       {
            echo '<li><a href="/yoga/skill.php?difficulty='.strtolower(urlencode($drug['difficulty'])).'">'.ucwords(strtolower($drug['difficulty'])).'</a></li>';
       }
                    
        
    ?>        
        </ol>


    <h2/>Most Popular Benefits</h2>
    <p>
        Indications for Yoga poses are wide ranging and have the ability to target specific components of your
        health and vitality. The following categories allow you to select asanas and postures that
        can transform your routine and provide an amazing and robust skillset to your yoga prowess.
    </p>
    <p><ul>
    <?php
          $yoga_query=tep_db_query('SELECT left(poses.use,instr(poses.use,",")-1) as benefit FROM yoga.poses group by benefit order by benefit asc limit 0,10;');

          while($yoga_info=tep_db_fetch_array($yoga_query))
          {
               ?><li><a href="/yoga/benefits.php?use=<?php echo urlencode(strtolower($yoga_info['benefit']))?>">Yoga for <?php echo ucwords($yoga_info['benefit']); ?></a></li> 
                <?php
          }
        
    
    ?></ul>
    
    </p>
    
    <h2>Health Focused Postures</h2>
    <p>Top ranking healthy lifestyle yoga postures allow you to target health and wellbeing. Focus
    on strength, stretches, agility, aerobics, breathing and many more practices for health.</p>
    <p>
    <ul>
    <?php
          $yoga_query=tep_db_query('SELECT translation, posename, poseid, school, difficulty FROM yoga.poses order by length("use") asc limit 0,10;');

          while($yoga_info=tep_db_fetch_array($yoga_query))
          {
               ?>               <li><a href="/yoga/pose.php?posture=<?php echo $yoga_info['poseid']?>"><?php echo $yoga_info['posename']; ?> <b><?php echo $yoga_info['translation']; ?></b> <?php echo $yoga_info['school']; ?></a> - <?php echo $yoga_info['difficulty']; ?></li> 
                <?php
          }
        
    
    ?></ul>
    </p>
    <h2/>Schools of Yoga</h2>
    <p>Define your passion and progression with specific schools of yogic thought that provide the 
    optimal routine and sequences for your practice. The following schools are methods of yoga postures
    that are ideologically integrated.</p>
    <p><ul>
    <?php
          $yoga_query=tep_db_query('SELECT school FROM yoga.poses group by school order by school asc;');

          while($yoga_info=tep_db_fetch_array($yoga_query))
          {
               ?><li><a href="/yoga/method.php?school=<?php echo urlencode(strtolower($yoga_info['school']))?>"><?php echo $yoga_info['school']; ?> Method</li> 
                <?php
          }
        
    
    ?></ul>
    
    </p>
    </div>
</td>
      
<!-- body_text_eof //-->
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- right_navigation //-->
<?php require($_SERVER['DOCUMENT_ROOT'].'/yoga/includes/column_right.php'); ?>
<!-- right_navigation_eof //-->
     </TABLE></TD></TR></TABLE>
<!-- body_eof //-->

<!-- footer //-->
<?php require($_SERVER['DOCUMENT_ROOT'].'/yoga/includes/footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
