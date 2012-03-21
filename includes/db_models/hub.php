<?php
  class hub extends ActiveRecord\Model
  {
      static $table_name='wp_posts';
      static $primary_key='ID';
      static $foreign_key='post_id';
      static $has_one=array(
                            array('wp_postmeta','conditions'=>array('meta_key=?',array('hub')),'class_name'=>'wp_postmeta'),
                            
                            );

      
  }
/* 
 
  class hub extends ActiveRecord\Model
  {
      static $table_name='wp_postmeta';
      static $primary_key='meta_id';
      static $foreign_key='post_id';
      static $has_many=array(
                            array('wp_post','conditions'=>array('post_status=?',array('publish')),'class_name'=>'wppost','primary_key'=>'ID'),
                            
                            );

      
  }    */
 
?>
