<?php
   
 class Wppost extends ActiveRecord\Model
 {
     static $table_name='wp_posts';
     static $primary_key='ID';
     //'conditions'=>array('post_status=?'=> array('publish')));
     static $foreign_key='post_id';
     
     
     
 }
?>
