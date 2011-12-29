<?php
  class products extends ActiveRecord\Model
  {
      static $table_name='products';
      static $has_one=array(array('productsdescription'));
      
      static $belongs_to=array(array('manufacturer','foreign_key'=>'manufacturers_id','primary_key'=>'manufacturers_id'));
                        
      static $primary_key='products_id';
      static $foreign_key='products_id';
      
      
  }
  
  
  
?>
