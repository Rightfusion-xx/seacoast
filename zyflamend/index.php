<?php
  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  require(DIR_WS_FUNCTIONS . '/render_products.php');
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php


?>
<title>
Zyflamend Benefits, Treatments & Side Effects
</title>
<meta name="keywords" content="zyflamend, anti-inflammation"/>
<meta name="description" content="Zyflamend is a COX-1 and COX-2 cancer inhibitor and anti-inflammatory treatment."/>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">

<style type="text/css">

</style>
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<?php
              /*
// create column list

    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

    asort($define_list);
    $column_list = array();
    reset($define_list);
    
    $select_column_list = '';
          $select_column_list .= ' p.products_msrp, pd.products_name, pd.products_head_desc_tag as product_desc, ';
          $select_column_list .= 'pd.products_head_desc_tag as product_desc, ';
          $select_column_list .= 'm.manufacturers_name, products_image, ';


        $listing_sql = "select " . $select_column_list . " pd.products_head_desc_tag as product_desc, pd.products_uses, pd.products_ailments, pd.products_departments, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd JOIN  " . TABLE_PRODUCTS . " p on p.products_id=pd.products_id left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' and pd.products_name like '%zyflamend%'";
        $listing_sql .= " order by pd.products_isspecial desc, pd.products_name";

    $define_list = array('PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);



    asort($define_list);
        $column_list = array();

    reset($define_list);

    while (list($key, $value) = each($define_list)) {

      if ($value > 0) $column_list[] = $key;

    }
    
    */
?>

<div id="content">
    <h1>Zyflamend Benefits, Treatments & Side Effects</h1>

	<div class="green box c27">
<h3>ZYFLAMEND 120 COUNT - PAIN RELIEF, JOINT FUNCTION & INFLAMMATION RELIEF</h3>
<table><tr><td>
<div id="supplement_image" class="c20">
<div id="actual_prod_image" class="c18" onmouseover="this.style.overflow='visible';" onmouseout="this.style.overflow='hidden';" style="overflow-x: hidden; overflow-y: hidden; "><img src="images/products/1252.gif" id="prod_image" border="0" alt=""></div>
<script language="javascript" type="text/javascript">
//<![CDATA[
<!--
                                                    if(document.getElementById('prod_image').width>250){
                                                        document.getElementById('prod_image').width=250;
                                                    }
                                                   
                                                //-->
//]]>
</script> </div>  </td><td>
<div class="c26">
<p class="c21"></p>
<form name="cart_quantity" action="http://www.seacoastvitamins.com/product_info.php?products_id=1252&amp;action=add_product" method="post" id="cart_quantity"><strong>Quantity:</strong> <select name="qty">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
</select><br>
<input type="hidden" name="products_id" value="1252"><br>
<p class="c23"><strong>Buy Now</strong><br>
<input type="submit" class="formbutton" id="button_price" value="Click & Buy for $34.99*" style="width:25em;6em;color:#66CC00;font-weight:bold;font-size:16pt;"></p>
<p class="c25"><script type="text/javascript">
//<![CDATA[
                                        function toggle_price(show_discount)
                                        {
                                                
                                                if(show_discount)
                                                {
                                                        document.getElementById('button_price').value='Click & Buy for $34.99*';
                                                        document.getElementById('button_price').style.color='#66CC00';
                                                        document.getElementById('button_price').style.fontWeight='bold';
                                                        document.getElementById('cm_price_disclaimer').style.display='block';
                                        //document.getElementById('extra_savings').style.display='none';
                                                }
                                                else
                                                {
                                                        document.getElementById('button_price').value='Click & Buy for $41.17';
                                                        document.getElementById('button_price').style.color='#666666';
                                                        document.getElementById('button_price').style.fontWeight='normal';
                                                        document.getElementById('cm_price_disclaimer').style.display='none';
                                        //document.getElementById('extra_savings').style.display='inline';
                                                }
                                                
                                        }
//]]>
</script><div style="text-align:left;border:1px dashed #666666;padding:10px;"> <input type="checkbox" name="cm_freetrial" value="true" checked="checked" onclick="toggle_price(this.checked);"> Yes! I want to try Seacoast Vitamins-Direct FREE for 14-days.

<p>
 I'll get,
 <ul>
 <li><b>Exclusive Prices</b> on Every Product</li>
 <!--<li><b>Free Shipping</b> on this entire order</li> -->
 <li><b>Protection</b> if side effects occur - <br/>
                just return the unused portion in 30 days for a full refund.</li>

 </p>
 </div>

</form>
<p id="cm_price_disclaimer" style="display: block; "><em>* Seacoast Vitamins-Direct price shown.</em> </p>
</div>     </td></tr>
<tr><td colspan="2">
  <h2><b>Prevent & Protect Against Side Effects</b></h2>
  <ul>
  <li>Zyflamend contains green tea and should not be taken before bedtime.</li>
  <li>Side effects, though rare, were mild and included sleeplesness, anxiety, and upset stomach. If you are nursing, pregnant, or thinking of becoming pregnant, or if you are
  on medication for your heart or other serious conditions, consult with your doctor about using Zyflamend.
  <li><b>Protect Your Investment:</b> Join Seacoast Vitamins-Direct FREE for 14-days and you may return the unused portion within 30 days for a FULL REFUND if it causes side effects.</li>

</td></tr>
</table>
</div>

<br style="clear:both;"/>
<h2>Clinically Proven for Pain Relief and Inflammation</h2>
    <p>
        Zyflamend by New Chapter is a unique blend of herbal supplements that have been researched and proven to work together for outstanding, anti-inflammatory purposes. It is a natural and effective treatment option to help regulate the body’s inflammatory pathways. Numerous studies have been performed to show that Zyflamend acts as a COX inhibitor and plays a valuable role in cancer treatment. It has also shown to help reduce inflammation and pain associated with arthritis and sports injuries. Zyflamend can increase energy levels throughout the day as well.
    </p>
    <p style="border:1px solid #cccccc;padding:20px;">
        <b>Additional Resources</b>
        <br/><A href="/index.php?cPath=318">Discount Zyflamend</a>
        <br/><A href="/article_info.php?articles_id=24">Zyflamend as a COX-2 Inhibitor - Clinical Study</a>
        <br/><A href="/article_info.php?articles_id=42">Columbia University Study</a>
    </p>

    <h2>
        Herbal ingredients Composing Zyflamend
    </h2>
    
    <p>
        Zyflamend is comprised of multiple herbs that synergistically combine to enhance its anti-inflammatory properties.  The herbs in Zyflamend have been extracted using a supercritical carbon dioxide technology that delicately removes the herbs without the influence of chemical solvents. Chemical processes for herbal extraction can harm the environment, alter the herb’s phytochemicals, and leave a chemical residue on the herbal product. The herbs in Zyflamend are extracted by heating carbon dioxide to its supercritical state, which penetrates plant material, dissolving herbal constituents that can then be extracted. This process produces herbs in their purest, healthiest, most concentrated forms.
    </p>
    
    <h2>Turmeric</h2>
    
    <p>
        Turmeric is a spice local to India that is known for its antioxidant and anti-inflammatory effects. It has shown to interact effectively with green tea’s polyphenols, maximizing its ability to reduce inflammation and protect cells.
    </p>
    
    <h2>Holy Basil</h2>
    
    <p>
        Holy Basil contains phytonutrients which provide several health benefits. It has been shown to block the cyclooxygenase and lipoxygenase pathways of arachidonic acid metabolism. Holy Basil contains ursolic acid which enhances the body’s production of glutathione S-transferase. Glutathione S-transferase is a key enzyme for detoxification of the liver and inflammation reduction. Holy Basil also works to prevent changes in cortisol levels induced by stress.
    </p>
    
    <h2>Ginger</h2>
    
    <p>
        Ginger is key for its ability to increase absorption and implementation of other herbs up to 2.5 times. It is also used for its influence on inflammation. Ginger has shown to play an inhibitory role on lipoxygenase COX-2 enzyme activity.
    </p>
    
    <h2>Rosemary</h2>
        Zyflamend contains a dual-extraction of rosemary to maximize the effects of its valuable constituents. Rosemary contains ursolic acid, betulinic acid, rosemarinic acid, carnosol, and oleanolic acid. These constituents have proven effective in their ability to promote detoxification by down-regulation of phase l cytochrome P450 enzymes and up-regulation of phase ll glutathione S-transferase activity. Rosemary also works as a COX-2 inhibitor and supports normal cell growth while regulating the inflammatory pathways at the cellular level.
    </p>
    
    <h2>Green Tea</h2>
    
    <p>
        Green Tea is well-known for its antioxidant power. It works to protect cells and promote overall health.  It is full of polyphenols and catechins, as well as 51 anti-inflammatory phytonutrients. The polyphenols in green tea have been shown to support healthy joints and reduce COX-2.  
    </p>
    
    <h2>Scutellaria</h2>
    
    <p>
        Scutellaria is packed full of antioxidants and anti-inflammatory constituents in the form of flavonoids. The specific flavonoids responsible for the main health benefits of scutellaria are baicalin, baicalein, and wogonin. Research has revealed that this important herb effectively inhibits COX-2, lipoxygenase, and nitric acid. It also promotes the phase ll detoxifying power of the enzyme, quinine reductase.
    </p>
    
    <h2>HuZhang</h2>
    
    <p>
        HuZhang contains the powerful polyphenol, resveratrol, which is known for its extensive antioxidant and anti-inflammatory properties. Resveratrol has proven to have some COX-2 inhibiting effects.
    </p>
    
    <h2>Chinese Goldthread and Barberry</h2>
    
    <p>
        Zyflamend has utilized the full-spectrum extracts of Chinese goldthread and barberry because they are well-identified for high levels of berberine. Berberine has a documented history of helping the body regulate inflammatory response. It helps protect and preserve cells and reduce inflammation because it works as a COX-2 inhibitor.
    </p>
    
    <h2>Oregano</h2>
    
    <p>
        Oregano offers powerful health benefits such as reduction of inflammation, cell protection, support of healthy platelet function, and the promotion of cardiovascular health. Oregano contains 31 anti-inflammatory constituents according to the USDA database. It also contains romarinic acid, which is recognized for its role in platelet function and ultimately, cardiovascular health.
        <br/>(http://www.drhoffman.com/page.cfm/497)
    </p>
    
    <h2>Endorsements & Reviews of Zyflamend</h2>
    
    <p>
        Zyflamend is taking the medical community by storm concerning several major health issues. Physicians are taking notice of the research supporting the use of Zyflamend for inflammation regulation and the treatment of prostate cancer.  
    </p>
    <p class="quote">
        “I recommend Zyflamend to a lot of patients with inflammatory disorders. I think there's a lot of evidence that the components of it--herbs like ginger and turmeric--do inhibit these enzymes that promote inflammation, as well as hormones that do the same. I think it is an area of very hot research.”
        <br/>-Dr. Andrew Weil, M.D.
    </p>
    <p>
        Zyflamend works by inhibiting COX-1 and COX-2, which cause inflammatory response. The prevention of COX-1 and COX-2 releasing in the body has been very successful in reducing inflammation and pain associated several physical conditions such as arthritis, Alzheimer’s disease, and more.
    </p>
    
    <p class="quote">
        “A great millennial introduction to COX-2 inhibition…”
        <br/>-Dr. James Duke, PhD. World-renowned authority on botanical medicine
    </p>
    
    <p>
        The medical benefit of Zyflamend that is, perhaps, most captivating to the medical community is its role in effectively helping to treat and prevent prostate cancer in men. It is being included as an essential form of treatment by ground-breaking physicians such as Aaron E. Katz, M.D., author of Dr. Katz's Guide to Prostate Health: From Conventional to Holistic Therapies. Dr. Katz is director of the Center for Holistic Urology at Columbia University Medical Center, and an associate professor of clinical urology at Columbia University College of Physicians and Surgeons.
    </p>
    <p class="quote">
        "Zyflamend is derived from natural herbal sources and is readily available in health food and nutritional supplement stores. Given the impressive data we're reporting, Zyflamend is a potentially more convenient and desirable means to target the enormous population that is susceptible to prostate cancer."                                                                                                                                                                                 
        <br/>-Dr. Debra L. Bemis of the Columbia University Department of Urology 
    </p>
    
    <h2>Clinical Outcomes of Zyflamend Studies</h2>
    <p><b>Effect on Prostate Cancer</b><br/>
        Research is being conducted on the uses and benefits of Zyflamend. The data has been very encouraging thus far concerning the powerful anti-inflammatory effect of Zyflamend as well as its success in slowing the growth of prostate cancer. It is being found that Zyflamend promotes overall body health because the inflammatory response is so closely connected to the health and wellness of the body’s organs and systems.
    </p>
    
    <p class="quote">
        “In my professional opinion, New Chapter’s Zyflamend is the premier herbal strategy
        for total body health. I say this because modern science now recognizes that the health
        of major organ systems – immune, cardiovascular, metabolic, skeletal, the brain
        – is intimately connected to a healthy inflammation response, and Zyflamend
        represents the finest herbal approach to supporting a well-functioning inflammatory
        process.”    
        <br/>-Richard L. Sarnat M.D., President and Cofounder of Alternative Medicine Integration Group 
    </p>
    <p>                                                                                                                                                                       
        One study was performed on men ages 40-75 who had been diagnosed with prostatic intraepithelial neoplasia (PIN) at prostate biopsy and were high risk for developing prostate cancer. The study indicated that the Zyflamend treatment  worked to promote a low progression rate to prostate cancer. Results also showed that Zyflamend showed low toxicity and no adverse side effects.
    </p>
    <p>
        (Journal of Clinical Oncology, 2006 ASCO Annual Meeting Proceedings Part I. Vol 24, No. 18S (June 20 Supplement), 2006: 14575                                                                                                                               Authors: Richard L. Sarnat M.D., President and Cofounder of Alternative Medicine Integration Group)
    </p>
    <p>
        Zyflamend has been shown to be extremely effective in reducing cancerous cells and helping to fight prostate cancer in one valuable, clinical study. This study analyzed the effects of Zyflamend on the human prostate cancer cell line LNCaP. The study also determined COX inhibitory activity as well as evaluated Zyflamend's effect on androgen receptors.  Prostate cancer can often be charged by testosterone received by androgen receptors and will grow accordingly. The results of the study were very exciting as they pertain to prostate cancer. LNCaP cell growth diminished. Zyflamend inhibited both COX-1 and COX-2 enzymatic activities and induced apoptosis (death) of the cancerous cells. Androgen receptor expression levels decreased by 40%, which helped to dramatically slow the growth of the prostate cancer. 
    <p>
        (Zyflamend®, a Unique Herbal Preparation With Nonselective COX Inhibitory Activity, Induces Apoptosis of Prostate Cancer Cells That Lack COX-2 Expression.
        <br/>Authors: Debra L. Bemis, Jillian L. Capodice, Aristotelis G. Anastasiadis, Aaron E. Katz, and Ralph Buttyan) 
    </p>
    
    <p>
        Another study performed by the Columbia University Medical Center Department of Urology in 2005 indicated that Zyflamend’s unique, herbal composition is effective as a COX-1 and COX-2 inhibitor which greatly reduces the body’s inflammatory response. The in vitro study showed that Zyflamend has the ability to reduce the proliferation of prostate cancer cells by as much as 78% and it causes the cancerous cells to destroy themselves through the process of apoptosis. 
    </p>
    <h2>Practical Uses & Treatments with Zyflamend</h2>
    <p>
        The natural and effective combination of herbs in Zyflamend makes it a good choice for the treatment of a variety of physical conditions. Zyflamend specifically works as a COX inhibitor, which causes it to powerfully regulate and reduce inflammation. Zyflamend can be very beneficial in helping to control pain and inflammation associated with arthritis. It also promotes recovery and decreased inflammation in sports injuries. Zyflamend is a good alternative in some cases to NSAIDs (non-steroidal anti-inflammatory drugs such as aspirin and ibuprofen). Zyflamend is a natural alternative to traditional medicine for diseases and injuries related to inflammation. Due to its potent antioxidant compilation, Zyflamend can also be used as a successful tool in fighting the effects and signs of aging.
    </p>
    <p>
        Zyflamend is especially noted for its effective use in fighting and preventing prostate cancer. It is highly recommended for patients who are high risk for developing prostate cancer, particularly those who have been diagnosed with prostatic intraepithelial neoplasia (PIN). It is used in conjunction with other forms of treatment as well for men who have been diagnosed with prostate cancer as it has been proven to reduce prostate cancer tumors, induce apoptosis of cancer cells, and block androgen receptors.
    </p>
    <h2>Potential Side Effects</h2>
    <p>
        One of the most appealing qualities of Zyflamend is that it has no know side effects at this point. Research is ongoing concerning the long-term or chronic use of Zyflamend. However, to this date, no dangerous, harmful, or uncomfortable complications been found. Some patients could display a hypersensitivity to herbal constituents in Zyflamend. The herbal ingredients in Zyflamend could potentially interact with other drugs. You should always discuss any medications, natural or otherwise, with your physician before taking them.
        <br/>(Memorial Sloan-Kettering Cancer Center,  http://www.mskcc.org/mskcc/html/69 429.cfm)                                                                                                                                                                              
    </p>
  	<h2>Related Zyflamend Products</h2>
	<?php
	global $products_name;
	$products_name='zyflamend';
	include(DIR_WS_MODULES . 'similar_products_google.php');         
	
	include(DIR_WS_MODULES . 'similar_picks.php'); ?>        


	
	</div>

  </div>   </div></div>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

