<?php

class pfp
{
    //global $xmlPay;
    //global $tx;
    
	function pfp($pfptid)
    {
        global $remote, $pfptransid;
        $pfptransid=$pfptid;
        
        $this->addTemplateXml();
        
        $remote=curl_init();
        

    
    }
    
    function addTemplateHeaders($remote, $pfptransid)
    {
        global $xmlPay,$remote;
        $headers = array(
            "Content-Length: ".strlen($xmlPay),
            "Content-Type: text/xml",
            "Host: ".PFP_HOST,
            "X-VPS-REQUEST-ID: ".$pfptransid,
            "X-VPS-CLIENTTIMEOUT: 45"); 
       
        curl_setopt($remote, CURLOPT_HTTPHEADER, $headers);  
        curl_setopt($remote, CURLOPT_URL,'https://'.PFP_HOST.'/transaction');
	    curl_setopt($remote, CURLOPT_VERBOSE, 1);

	    //turning off the server and peer verification(TrustManager Concept).
	    curl_setopt($remote, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($remote, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($remote, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($remote, CURLOPT_POST, 1);
        curl_setopt($remote, CURLOPT_FORBID_REUSE, TRUE);
        
        return(0);
    
    }
    
    function addTemplateXml()
    {
        global $xmlPay;
        
        $xmlPay='<?xml version="1.0" encoding="UTF-8"?>
                    <XMLPayRequest Timeout="30" version = "2.0" xmlns="http://www.paypal.com/XMLPay"> 
                        <RequestData>
                            <Partner>'.PFP_PARTNER.'</Partner>
                            <Vendor>'.PFP_VENDOR.'</Vendor>
                            <Transactions></Transactions>
                        </RequestData>
                        <RequestAuth><UserPass>
                            <User>'.PFP_USER.'</User>
                            <Password>'.PFP_PASSWORD.'</Password></UserPass>
                        </RequestAuth>
                    </XMLPayRequest>';
        
    
    }
    
    function addSaleXml($order, $match=true)
    {
        global $xmlPay;
        
        $tx='<Transaction>
            <Sale>
                <PayData>
                    <Tender>
                        <Card>
                            <CardNum>'.str_replace('-','',str_replace(' ','',$order->info['cc_number'])).'</CardNum>
                            <ExpDate>'.'20'.substr($order->info['cc_expires'],-2,2).substr($order->info['cc_expires'],0,2).'</ExpDate>
                            <NameOnCard>'.$order->info['cc_owner'].'</NameOnCard>';
                            if($match)$tx.='<CVNum>'.$order->info['cvvnumber'].'</CVNum>';
                       $tx.= '</Card>
                    </Tender>
                    <Invoice>';
                    if($match){ $tx.='
                        <BillTo>
                            <Address>
                                <Street>'.$order->billing['street_address'].'</Street>
                                <City>'.$order->billing['city'].'</City>
                                <State>'.$order->billing['state'].'</State>
                                <Zip>'.$order->billing['postcode'].'</Zip>
                                <Country>'.$order->billing['country'].'</Country>                       
                            </Address>                        
                        </BillTo>';}
                    $tx.='
                        <InvNum>'.$order->info['orders_id'].'</InvNum>
                        <TotalAmt>'.number_format($order->info['ot_total'],'2','.','').'</TotalAmt>
                        <FreightAmt>'.number_format($order->info['ot_shipping'],'2','.','').'</FreightAmt>
                    </Invoice>
                </PayData>
            </Sale>
            </Transaction>';
                    
        $xmlPay=str_replace('</Transactions>',$tx.'</Transactions>',$xmlPay);       

        
        $this->doXmlPayTransaction();
    }
    
    function doXmlPayTransaction()
    {
        global $remote, $xmlPay, $pfptransid;
        $this->addTemplateHeaders($remote,$pfptransid);
        curl_setopt($remote,CURLOPT_POSTFIELDS,$xmlPay);

	    //getting response from server
	    //echo $xmlPay.'<br/>';
	    $response = curl_exec($remote);
    	
	    $this->response= $response;
	    $this->xmlPay=$xmlPay;
	    //echo curl_getinfo($remote);
	    
	    //get results
	    
	    preg_match('/\<Message\>(.*)\</Message\>/', $response, $regs);
	    $this->message=$regs[1];
	    
	    preg_match('/\<Result\>(.*)\</Result\>/', $response, $regs);
	    $this->result=$regs[1];
	    
	    preg_match('/\<PNRef\>(.*)\</PNRef\>/', $response, $regs);
	    $this->pnref=$regs[1];
        
        
        
    }





}

?>