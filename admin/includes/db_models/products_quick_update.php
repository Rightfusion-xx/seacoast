<?php
  class products_quick_update extends ActiveRecord\Model
  {
      static $table_name='products';
      static $primary_key='products_id';
      
      static $alias_attribute=array('created_at'=>'products_date_added','updated_at'=>'products_last_modified');
      static $has_one=array(
                            array('manufacturer','primary_key'=>'manufacturers_id','foreign_key'=>'manufacturers_id','class_name'=>'manufacturer'),
                            array('product_description','primary_key'=>'products_id','foreign_key'=>'products_id','class_name'=>'product_description')
                            );
                            
      static $delegate=array(                                
                            array('manufacturers_name', 'to'=>'manufacturer'),
                            array('products_name', 'to'=>'product_description')
                            );
      
      public function set_products_name($name)
      {
          $this->product_description->assign_attribute('products_name',$name);
          echo 'made it!'; exit();
      }
                                                      
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
