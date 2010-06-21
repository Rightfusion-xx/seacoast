<?php

// create a mapping of objects to represent products in the DB

class Product extends ActiveRecord\Model
{
  // correct the explicit table name
  static $table_name='products';
  
  // correct the explicit PK
  static $primary_key='products_id';
  
  // explicit db name (JIC)
  static $db='seacoast';

}

class ProductDescription extends ActiveRecord\Model
{
  
  static $table_name='products_description';
  static $primary_key='products_id';
  static $db='seacoast';
  
}

?>