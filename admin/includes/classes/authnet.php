<?php

class authnet
{

    private $lovearray;
    
	function authnet()
    {

        
        $this->addTemplate();

    }


    
    function addTemplate()
    {
        $this->lovearray['x_login']='292BYrpbw';
        $this->lovearray['x_tran_key']='295y79Rb9MwYm5hB';
        $this->lovearray['x_version']='3.1';
        //$this->lovearray['x_delim_char']='|';
        $this->lovearray['x_delim_data']='TRUE';
        $this->lovearray['x_duplicate_window']='0';
        //$this->lovearray['x_encap_char']='"';
        $this->lovearray['x_method']='CC';
        //$this->lovearray['x_trans_id']=$transid;
        $this->lovearray['x_test_request']=X_TEST_REQUEST; //Remove before going LIVE

    
    }
    
    function addSale(&$order)
    {
        $fnpos=strpos($order->info['cc_owner'],' ');
        if(!$fnpos)$fnpos=strlen($order->info['cc_owner']);
        $this->lovearray['x_type']='AUTH_CAPTURE';
        $this->lovearray['x_card_num']=str_replace('-','',str_replace(' ','',$order->info['cc_number']));
        $this->lovearray['x_exp_date']=substr($order->info['cc_expires'],0,2).substr($order->info['cc_expires'],-2,2);
        $this->lovearray['x_first_name']=substr(substr($order->info['cc_owner'],0,$fnpos),0,50);
        $this->lovearray['x_last_name']=substr(substr($order->info['cc_owner'],$fnpos+1),0,50);

        //Match transaction information
            $this->lovearray['x_card_code']=$order->info['cvvnumber'];
            $this->lovearray['x_address']=substr($order->billing['street_address'],0,60);
            $this->lovearray['x_city']=substr($order->billing['city'],0,40);
            $this->lovearray['x_state']=$order->billing['state'];
            $this->lovearray['x_zip']=substr($order->billing['postcode'],0,20);


        $this->lovearray['x_invoice_num']=$order->info['orders_id'];
        $this->lovearray['x_freight']=number_format($order->info['ot_shipping'],'2','.','');
        $this->lovearray['x_amount']=number_format($order->info['ot_total'],'2','.','');
        $this->lovearray['x_cust_id']=$order->customer['id'];
        $this->lovearray['x_company']=substr($order->billing['company'],0,50);
        $this->lovearray['x_country']=substr($order->billing['country'],0,60);
        $this->lovearray['x_description']=substr($order->info['description'],0,255);
        $this->lovearray['x_duty']='0';
        $this->lovearray['x_email']=substr($order->customer['email_address'],0,255);
        $this->lovearray['x_email_customer']='FALSE';
        $this->lovearray['x_fax']=substr('',0,25);
        $this->lovearray['x_phone']=substr($order->customer['telephone'],0,25);
        $this->lovearray['x_po_num']=substr($order->info['orders_id'],0,25);
        $this->lovearray['x_recurring_billing']='FALSE';
        
        $fnpos=strpos($order->delivery['name'],' ');
        $this->lovearray['x_ship_to_address']=substr($order->delivery['street_address'],0,60);
        $this->lovearray['x_ship_to_company']=substr($order->delivery['company'],0,50);
        $this->lovearray['x_ship_to_country']=substr($order->delivery['country'],0,60);
        $this->lovearray['x_ship_to_city']=substr($order->delivery['city'],0,40);
        $this->lovearray['x_ship_to_first_name']=substr(substr($order->delivery['name'],0,$fnpos),0,50);
        $this->lovearray['x_ship_to_last_name']=substr(substr($order->delivery['name'],$fnpos+1),0,50);
        $this->lovearray['x_ship_to_state']=substr($order->delivery['state'],0,40);
        $this->lovearray['x_ship_to_zip']=substr($order->delivery['postcode'],0,20);
        
        if($order->is_subscription)
        {
          $this->lovearray['x_recurring_billing']='TRUE';
        }  


        $this->lovearray['x_line_item']=Array();
        foreach($order->products as $item)
        {
          $this->lovearray['x_tax']+=$item['tax'];
          array_push($this->lovearray['x_line_item'],
                                    clean_string($item['products_id']).'<|>'.
                                    substr(clean_string($item['name']),0,31).'<|>'.
                                    substr(clean_string($item['manufacturer']),0,255).'<|>'.
                                    clean_string($item['qty']).'<|>'.
                                    number_format($item['final_price'],'2','.','').'<|>'.
                                    (($item['tax']>0) ? 'TRUE' : 'FALSE'));
        
        }
        
        if($this->lovearray['x_tax']==0)
        {
          $this->lovearray['x_tax_exempt']='TRUE';
        }
        else
        {
          $this->lovearray['x_tax_exempt']='FALSE';
        }
        



    }
    
    function addRefund(&$order, &$ph, $amount)
    {
      $this->lovearray['x_trans_id']=$ph[6];
      $this->lovearray['x_invoice_num']=$order->info['orders_id'];
      $this->lovearray['x_description']=substr($ph['description'],0,255);
      //check time to see if need to do void or refund
      if(date("H",time())>=18)
      {
        $lastsettlement=strtotime(date("M d, Y",time()) .' 7:00pm');
      }
      else
      {
        $lastsettlement=strtotime(date("M d, Y",time()-86400) .' 7:00pm');
      }
      
      if(strtotime($ph['transaction_time'])>$lastsettlement)
      {
        //can only void
        $this->lovearray['x_type']='VOID';
        
        //If anything other than full amount was passed then cancel the transaction
        if($amount<$ph[9])
        {
          return(Array('Cannot refund less than the full amount because the transaction has not been settled.'));
        }
        else
        {
          //do the transaction

        }
      }
      else
      {
        //can only refund
        $this->lovearray['x_type']='CREDIT';
        $this->lovearray['x_amount']=$amount;
        $this->lovearray['x_card_num']=str_replace('-','',str_replace(' ','',$order->info['cc_number']));
        $this->lovearray['x_exp_date']=substr($order->info['cc_expires'],0,2).substr($order->info['cc_expires'],-2,2);
      }

      //$transdate=date("H",strtotime($ph['transaction_time']))
      //if((currentdate-transactiondate)-now<
      //
      
      
      
    }
    
    function execTransaction()
    {
      //Create a string to send.
      $paystring='';
      $index=0;
      foreach($this->lovearray as $item=>$val)
      {
        if($index>0)
        {
          $paystring.='&';   //Delimiter
        }
        if($item=='x_line_item')
        {
          $i=0;
          foreach($val as $xli)
          {
            if($i>0)
            {
              $paystring.='&';
            }
            $paystring.='x_line_item='.$xli;
            $i++;
          }
        }
        else
        {
          $paystring.= $item.'=';
          $paystring.=(is_numeric($val) || $item=='x_email')?$val:clean_string($val);
        }
        $index++;
      }
      

        
      $remote=curl_init();

      curl_setopt($remote, CURLOPT_URL,'https://secure.authorize.net/gateway/transact.dll');
      curl_setopt($remote, CURLOPT_VERBOSE, 1);

      //turning off the server and peer verification(TrustManager Concept).
      curl_setopt($remote, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($remote, CURLOPT_SSL_VERIFYHOST, FALSE);

      curl_setopt($remote, CURLOPT_RETURNTRANSFER,1);
      curl_setopt($remote, CURLOPT_POST, 1);
      curl_setopt($remote, CURLOPT_FORBID_REUSE, TRUE);
      
      curl_setopt($remote,CURLOPT_POSTFIELDS,$paystring);

      //getting response from server
      //echo $xmlPay.'<br/>';
      $response = curl_exec($remote);
      $results=preg_split('/,/',$response);
      
      if($this->lovearray['x_test_request']=='TRUE')
      {
        $results[6]=time().rand(1000,9999);
      }
      if($results[6]=='0' && strlen($this->lovearray['x_trans_id']>0))
      {
        $results[6]=$this->lovearray['x_trans_id'];
      }

      logResults($results);
      

      
      return($results);


    }


}

function runPayment(&$order)
{
  $pfp=new authnet();
  $pfp->addSale($order);
  $results=$pfp->execTransaction();
  return($results);

}

function runRefund(&$order,&$ph,$amount)
{
  $pfp=new authnet();
  $tryRefund=$pfp->addRefund($order, $ph, $amount);
  if(is_array($tryRefund))
  {
    //transaction cannot go through
    return($tryRefund);
  }

  $results=$pfp->execTransaction();
  return($results);
  
  
}

function logResults(&$results)
{
  if($record=tep_db_fetch_array(tep_db_query('select * from authnet_transactions where trans_id="'.$results[6].'"')))
  {
    //found a record, so update.
    tep_db_query('update authnet_transactions set orders_id="'.tep_db_input($results[7]).'", results="'.tep_db_input(gzcompress(serialize($results),9)).'" where trans_id="'.tep_db_input($results[6]).'"');
  }
  else
  {
    //insert new transaction
    tep_db_query('insert into authnet_transactions(trans_id, orders_id, results) values'.
                         '("'.tep_db_input($results[6]).'","'.tep_db_input($results[7]).'","'.tep_db_input(gzcompress(serialize($results),9)).'");');

  }

}



?>