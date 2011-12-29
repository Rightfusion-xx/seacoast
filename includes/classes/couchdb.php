<?php
  class CouchDB
  {
      private $_curl, $_arr_connection;
      
      public function myCouchDB($arr_connection=array('url'=>COUCH_DB_ADDRESS))
      {
          //constructor
          _openConnection($arr_connection);
          $this->_arr_connection=$arr_connection;
          
      }
      
      private function _openConnection($arr_connection)
      {
          $this->_curl=curl_init();
          
          curl_setopt($this->_curl,CURLOPT_URL,$arr_connection['url']);   
          curl_setopt($this->_curl, CURLOPT_PORT, 5984) ;   
          
      }
      
      private function _sendQuery($data)
      {
          curl_exec($this->_curl);
          
      }
                                
      public function createDoc($path,$data)
      {
          // Use Post
          return(true); 
      }
      
      public function updateDoc($path, $data)
      {
          //Use Put
          
          
      }
      
      public function getDoc($path, $data)
      {
          
          
      }
      
      public function getDocs($path, $data)
      {
          curl_setopt($this->_curl,CURLOPT_POST, true);
          curl_setopt($this->_curl, CURLOPT_URL, $this->_arr_connection['url'].$path);
          $this->_sendQuery($data);
          
      }
      
      
  }
  
?>
