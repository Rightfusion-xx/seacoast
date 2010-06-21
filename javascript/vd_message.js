function vd_price_box(products_id, products_name, products_image, products_msrp, non_member_price, member_price,is_member=false)
{
	if(is_member)
	{
		return(true);
	}
	var price_box=document.getElementById('vd_price_box');
	if (price_box==null){

//create price box
		document.body.innerHTML+='<div id="do_modal" style="background-color:#c6e3f5;-moz-opacity: 0.8;opacity:.80;filter: alpha(opacity=80);z-index:5000;position:fixed;width:100%;height:100%;top:0px;left:0px;vertical-align:middle;">'+
					'<div id="vd_price_box" class="green box" style="background-color:#ffffff;position:fixed;top:50%;left:50%;width:450px;height:300px;margin:-170px 0 0 -200px;z-index:9999;-moz-opacity: 1.9;opacity:1.90;filter: alpha(opacity=190);">' +
                	 '<h3 style="float:left;display:inline;">Exclusive Pricing</h3><h3 style="float:right;display:inline;">'+
                	 '<a href="javascript:return(false);" onclick="vd_close(this)" style="color:#ffffff;">[X] Never Mind</a>  ' +
                	 '</h3>' +
                	     '<div id="supplement_image" style="clear:both;float:left; margin-top:20px;margin-right:50px;margin-bottom:30px;">' +
					            '<img src="/images/products/transparent.gif" id="prod_image" border="0"/>' +
					        '<br/><span style="text-decoration:line-through" id="msrp">Regular MSRP: $20.49</span></div>' +			    
					    '<div style="padding:20px;float:left;">' +
						'<p style="text-align:left;">' +
						'<form name="cart_quantity" action="/product_info.php?products_id=558&action=add_product" method="post">'+		                
			            '    <b>Quantity:</b>' +
			            '    <select name="qty">' +
			                			                     '<option value="1">1</option><option value="2">2</option><option value="3">3</option>'+
							'<option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option>'+
							'<option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option>'+
							'<option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option>'+
							'<option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>'+
							'<option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option>'+
							'<option value="30">30</option></select>'+
			                '<br/><input type="hidden" name="products_id" value="558"><br/>'+
							'</p>' +
                 			'<p style="text-align:left"><b>Buy Now</b><br/>'+
                 			'<input type="submit" id="button_price" value="$12.71*" style="width:200px;height:30px;color:#66CC00;font-weight:bold;font-size:12pt;">'+
                 		'</p>' +
							'<p style="border:dashed 1px #dddddd;padding:5px;">'+
                 		'</script>' +
                 		'<input type="checkbox" name="cm_freetrial" value="true" checked onclick="toggle_price(this.checked);"/> Join Seacoast Vitamins-Direct FREE for 14-days '+
                 		'<span id="extra_savings" style="display:none;"><br/>and <span style="color:#ff0000;font-weight:bold;">save an extra 15%</span></span>'+
						'</form> </p>'+
                 		'<p id="cm_price_disclaimer"><i>* Seacoast Vitamins-Direct price shown.</i>' +
                 		'<a href="/community/" target="_blank" rel="nofollow">Learn more.</a></p>' +      
                 		'</div></div></div>'   	;
                 		
                 		var price_box=document.getElementById('vd_price_box');
	}

//Price box exists. Change message.
	document.body.style.overflow='hidden';
	document.getElementById('do_modal').style.display='block';
	//price_box.style.display='inline';
	//price_box.style.position='absolute';
	//price_box.style.width=500;
	//price_box.style.height=500;
	//price_box.style.left=0;
	//price_box.style.top=0;
	if(document.getElementById('prod_image').src.len>0){
		document.getElementById('prod_image').src='/images/products/transparent.gif'
		if(document.getElementById('prod_image').width>250){
					                document.getElementById('prod_image').width=250;
					            }
	}				            
		


//Now that box is created, don't post the form. Return false.
	return false;


}

function vd_close(obj)
{
	document.getElementById('vd_price_box').style.display='none';
	document.body.style.overflow='visible';
	document.getElementById('prod_image').src='/images/products/transparent.gif';
	document.getElementById('do_modal').style.display='none';

}


function toggle_price(show_discount)
     			{
      				
      				if(show_discount)
      				{
      					document.getElementById('button_price').value='$12.71*';
      					document.getElementById('button_price').style.color='#66CC00';
      					document.getElementById('button_price').style.fontWeight='bold';
      					document.getElementById('cm_price_disclaimer').style.display='block';
       	                document.getElementById('extra_savings').style.display='none';
      				}
      				else
      				{
      					document.getElementById('button_price').value='$14.95';
      					document.getElementById('button_price').style.color='#666666';
      					document.getElementById('button_price').style.fontWeight='normal';
      					document.getElementById('cm_price_disclaimer').style.display='none';
      	                document.getElementById('extra_savings').style.display='inline';
      				}
      				
      			}