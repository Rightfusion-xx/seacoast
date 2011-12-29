<?php
   
 class wp_postmeta extends ActiveRecord\Model
 {
     static $table_name='wp_postmeta';
     static $primary_key='meta_id';
     //'conditions'=>array('post_status=?'=> array('publish')));
     //static $foreign_key='post_id';
     
     
     
 }
?>
