<?php

/* HTTPBase.php
 *
 * Copyright (C) 2004 MaxMind LLC
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$server = array("www.maxmind.com", "www2.maxmind.com");
$numservers = 2;
$API_VERSION = 'PHP/1.2';
class HTTPBase{
  var $server;
  var $numservers;
  var $url;
  var $queries;
  var $allowed_fields;
  var $num_allowed_fields;
  var $outputstr;
  var $isSecure;
  var $timeout;
  var $debug;
  function HTTPBase() {
    $isSecure = 0;
    $debug = 0;
    $timeout = 0;
  }

  // this function sets the allowed fields
  function set_allowed_fields($i) {
    $this->allowed_fields = $i;
    $this->num_allowed_fields = count($i);
  }

  //this function queries the servers
  function query() {
    //query every server in the list
    for ($i = 0; $i < $GLOBALS['numservers']; $i++ ) {
      $result = $this->querySingleServer($GLOBALS['server'][$i]);
      if ($this->debug == 1) {
        print "server: " . $GLOBALS['server'][$i] . "\nresult: " . $result . "\n";
      }
      if ($result) {
        return $result;
      }
    }
    return 0;
  }

  //this function takes a input hash and stores it in the hash named queries
  function input($vars) {
    $numinputkeys = count($vars);  // get the number of keys in the input hash
    $inputkeys = array_keys($vars);   // get a array of keys in the input hash
    for ($i = 0; $i < $numinputkeys; $i++) {
      $key = $inputkeys[$i];
      if ($this->allowed_fields[$key] == 1) {
        //if key is a allowed field then store it in 
        //the hash named queries
        if ($this->debug == 1) {
	  print "input $key = " . $vars[$key] . "\n";
	}
        $this->queries[$key] = urlencode($vars[$key]);
      } else {
        print "invalid input $key - perhaps misspelled field?";
	return 0;
      }
    }
    $this->queries["clientAPI"] = $GLOBALS['API_VERSION'];
  }

  //this function returns the output from the server
  function output() {
    return $this->outputstr; 
  }

  //this function query a single server
  function querySingleServer($server) {
    //check if we using the Secure HTTPS proctol
    if ($this->isSecure == 1) {
      $scheme = "https://";  //Secure HTTPS proctol
    } else {
      $scheme = "http://";   //Regular HTTP proctol
    }

    //build a query string from the hash called queries
    $numquerieskeys = count($this->queries);//get the number of keys in the hash called queries
    $querieskeys = array_keys($this->queries);//get a array of keys in the hash called queries
    if ($this->debug == 1) {
      print "number of query keys " + $numquerieskeys + "\n";
    }
    for ($i = 0; $i < $numquerieskeys; $i++) {
      //for each element in the hash called queries 
      //append the key and value of the element to the query string
      $key = $querieskeys[$i];
      $value = $this->queries[$key];
      //encode the key and value before adding it to the string
      //$key = urlencode($key);
      //$value = urlencode($value);
      if ($this->debug == 1) {
        print " query key " . $key . " query value " . $value . "\n";
      }
      $query_string = $query_string . $key . "=" . $value;
      if ($i < $numquerieskeys - 1) {
        $query_string = $query_string . "&";
      }
    }

    $content = "";

    //check if the curl module exists
    if (extension_loaded('curl')) {
      //use curl
      if ($this->debug == 1) {
        print "using curl\n";
      }

      //open curl
      $ch = curl_init();

      $url = $scheme . $server . "/" . $this->url;

      //set curl options
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_TIMEOUT, 7);

      //this option lets you store the result in a string 
      curl_setopt($ch, CURLOPT_POST,          1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,    $query_string);

      //get the content
      @$content = curl_exec($ch);

      // For some reason curl_errno returns an error even when function works
      // Until we figure this out, will ignore curl errors - (not good i know)
//      $e = curl_errno($ch);//get error or sucess

      if (($e == 1) & ($this->isSecure == 1)) {
        // HTTPS does not work print error message
          print "error: this version of curl does not support HTTPS try build curl with SSL or specify \$ccfs->isSecure = 0\n";
      }
      if ($e > 0) {
        //we get a error msg print it
        print "Received error message $e from curl: " . curl_error($ch) . "\n";
	return 0;
      }
      //close curl
      curl_close($ch);
    } else {
      //curl does not exist
      //use the fsockopen function, 
      //the fgets function and the fclose function
      if ($this->debug == 1) {
        print "using fsockopen\n";
      }

      $url = $scheme . $server . "/" . $this->url . "?" . $query_string;
      if ($this->debug == 1) {
        print "url " . $url . " " . "\n";
      }

      //now check if we are using regular HTTP
      if ($this->isSecure == 0) {
        //we using regular HTTP

        //parse the url to get
        //host, path and query
        $url3 = parse_url($url);
        $host = $url3["host"];
        $path = $url3["path"] . "?" . $url3["query"];

        //open the connection
        $fp = fsockopen ($host, 80, $errno, $errstr, $this->timeout);
        if ($fp) {
          //send the request
          fputs ($fp, "GET $path HTTP/1.0\nHost: " . $host . "\n\n");
          while (!feof($fp)) {
            $buf .= fgets($fp, 128);
          }
          $lines = preg_split("/\n/", $buf);
          // get the content
          $content = $lines[count($lines)-1];
          //close the connection
          fclose($fp);
        } else {
	  return 0;
	}
      } else {
        //secure HTTPS requires CURL
        print "error: you need to install curl if you want secure HTTPS or specify the variable to be $ccfs->isSecure = 0";
        return 0;
      }
    }

    if ($this->debug == 1) {
      print "content = " . $content . "\n";
    }
    // get the keys and values from
    // the string content and store them
    // the hash named outputstr

    // split content into pairs containing both 
    // the key and the value
    $keyvaluepairs = explode(";",$content);

    //get the number of key and value pairs
    $numkeyvaluepairs = count($keyvaluepairs);

    //for each pair store key and value into the
    //hash named outputstr
    for ($i = 0; $i < $numkeyvaluepairs; $i++) {
      //split the pair into a key and a value
      list($key,$value) = explode("=",$keyvaluepairs[$i]);
      if ($this->debug == 1) {
        print " output " . $key . " = " . $value . "\n";
      }
      //store the key and the value into the
      //hash named outputstr
      $this->outputstr[$key] = $value;
    }
    //check if outputstr has the score if outputstr does not have 
    //the score return 0
    if ($this->outputstr["score"] == "") {
      return 0;
    }
    //one other way to do it
    //if (!array_key_exists("score",$this->outputstr)) {
    //  return 0;
    //}
    return 1;
  }
}
?>