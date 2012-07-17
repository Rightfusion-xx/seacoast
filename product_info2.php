<?php

require('includes/application_top.php');
require('includes/classes/review_handler.php');

// Seed random generator so that items are mixed up, but consistent across page refreshes.
srand($product_info['products_id']);

// check for moded url
//redirect_moded_url();

if(strpos($_SERVER['HTTP_USER_AGENT'], "seacoast-crawler") > 0)
{
    $seacoast_crawler = true;
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

if((int)$HTTP_GET_VARS['products_id'] == CM_FTPID || (int)$HTTP_GET_VARS['products_id'] == CM_PID)
{
    redir301('/community/');
}

$product_info_query = tep_db_query("select pd.products_target_keyword, p.products_keywords, p.products_die, p.products_sku, p.products_upc,
        p.products_dieqty, pd.products_head_title_tag, pd.products_head_keywords_tag,
        pd.products_head_desc_tag, pd.products_type,
        pd.products_departments,pd.products_ailments,pd.products_uses,
        p.products_weight, p.products_ordered, pd.products_head_keywords_tag,
        pd.products_viewed, date_format(p.products_date_added,'%m/%d/%Y') as
        products_date_added, p.products_last_modified,
        p.products_id, pd.products_name, pd.products_description, p.products_model,
        p.products_quantity, p.products_image, pd.products_url, p.products_msrp,
        p.products_price, p.products_tax_class_id, p.products_date_available,
        p.manufacturers_id, m.manufacturers_name, pd.products_takeaway
        from " . TABLE_PRODUCTS . " p join  " . TABLE_PRODUCTS_DESCRIPTION . " pd on
        p.products_id=pd.products_id join " . TABLE_MANUFACTURERS . " m on m.manufacturers_id=p.manufacturers_id
        where p.products_status = '1' and p.products_id = '" . (int)$_REQUEST['products_id'] . "' and pd.language_id =' " . (int)$languages_id . "'");

if(!($product_info = tep_db_fetch_array($product_info_query)))
{
    //No product found, redirect.
    redir301(HTTP_SERVER);
}
else
{

    $product_parts = parse_nameparts($product_info['products_name']);
    $tname         = $product_parts['name'];
    $tmisc         = $product_parts['attributes'];
    $shortname     = $tname;

    //check URL
    //echo $_SERVER["REQUEST_URI"]."<br/>".str_replace('//','/',"/".seo_url_title($tname)."/".seo_url_title($product_info["manufacturers_name"])."/".seo_url_title($tmisc)."/p".$product_info['products_id']);exit();
    $test_url = (str_replace('//', '/', "/" . seo_url_title($tname) . "/" . seo_url_title($product_info["manufacturers_name"]) . "/" . seo_url_title($tmisc) . "/p" . $product_info['products_id']));
    if($test_url <> $_SERVER["REQUEST_URI"])
    {
        redir301(str_replace('//', '/', "/" . seo_url_title($tname) . "/" . seo_url_title($product_info["manufacturers_name"]) . "/" . seo_url_title($tmisc) . "/p" . $product_info['products_id']));
    }

    if(strpos(' ' . $_SERVER['HTTP_USER_AGENT'], 'gsa-crawler') > 0)
    {
        include(DIR_WS_INCLUDES . 'create_crawler_page.php');
        exit();
    }

    $lastmod                = strtotime($product_info['products_last_modified']);
    $is_cm_eligible         = strpos($product_info['products_name'], '*') ? 0 : 1;
    $tags_array['keywords'] = $product_info['products_head_keywords_tag'];
    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    //Get image location
    $product_image_path = select_image($product_info['products_id'], $product_info['products_image'], $product_info['manufacturers_id']);

    //check for product specials
    $new_price = tep_get_products_special_price($product_info['products_id']);

    //Get product name
    $products_name = $product_info['products_name'];

    //Get price
    if($new_price != '')
    {
        $price = ($new_price);
    }
    else
    {
        $price = $product_info['products_price'];
    }

    //Calculate membership discounts
    if($product_info['manufacturers_id'] == 69)
    {
        $cm_price = $price * .75; //25% Off
    }
    elseif(!strpos($product_info['products_name'], '*'))
    {
        $cm_price = $price * .85; //15% Off
    }
    else
    {
        $cm_price = $price;
    }

    //Get review details
    $reviews_query  = tep_db_query("select count(*) as count, avg(reviews_rating) as rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
    $reviews        = tep_db_fetch_array($reviews_query);
    $reviews_rating = ceil($reviews['rating']);

    //Check for alternate search keywords
    if(strlen($product_info['products_keywords']) > 0)
    {
        $alt_keywords = array();
        $alt_keywords = preg_split('/,/', $product_info['products_keywords']);
    }
}
// get all url links
populate_backlinks();

$cache = new megacache(24 * 60 * 60);
if(!$cache->doCache('products_main' . $pmod, true, $lastmod))
{

    include(DIR_WS_MODULES . 'customer_reviews.php');

    $product_parts = parse_nameparts($product_info['products_name']);
    $tname         = $product_parts['name'];
    $tmisc         = $product_parts['attributes'];
    $shortname     = $tname;

    $title       = $product_info['products_head_title_tag']; //$tname . ' | '.$product_info['manufacturers_name'].' | '. $tmisc;
    $description = $product_info['products_head_desc_tag']; //$shortname.' vitamin supplement from '.$product_info['manufacturers_name'] .' includes uses, indications and dosing information with '.$product_info['products_name'].'. '. $product_info['products_head_title_tag'];

    // Create product Description
    $tmp_desc = stripslashes($product_info['products_description']);
    $i        = 0;
    $tmp_len  = strlen($tmp_desc);
    while($i < $tmp_len)
    {

        $i2 = strpos($tmp_desc, '<a', $i);
        $i3 = strpos($tmp_desc, '<img', $i);
        if($i3 < $i2 && $i3)
            $i2 = $i3;

        $segment = substr($tmp_desc, $i, $i2);

        if(is_array($alt_keywords))
        {
            foreach($alt_keywords as $tmp_keyword)
            {
                $tmp_keyword = trim($tmp_keyword);
                $segment     = str_Ireplace($tmp_keyword, '<a href="/topic.php?health=' . strtolower(urlencode(trim($tmp_keyword))) . '" >' . ucwords(trim($tmp_keyword)) . '</a>', $segment);
            }
        }
        $tmp .= $segment;

        if($i2)
        {

            $i  = $i2;
            $i2 = strpos($tmp_desc, '>', $i) + 1;
            if(!$i2)
                $i2 = $tmp_len - $i;
            $tmp .= substr($tmp_desc, $i, $i2);
            $i = $i2;
        }
        else
        {
            $i = $tmp_len;
        }
    }
    // remove errant widths
    $tmp_desc = preg_replace('/width="*.?"/im', '', $tmp_desc);

    // remove empty tags , but not <td>
    $tmp_desc = preg_replace('/(^\<td)\<([a-z]+)[^\>]*?\>(\W|&nbsp;|\s)*\<\/\1\>/im', '', $tmp_desc);

    // remove comments
    $tmp_desc = preg_replace('/\<!--.*?--\>/im', '', $tmp_desc);
    //Count number of words in title / manufacturer
    $title_count = strlen($product_info['manufacturers_name'] . ' ' . $product_info['products_name']) + 1;

    if(preg_match('/(^(\<.*?\>))([^\<\>]{' . $title_count . '})/im', $tmp_desc, $matches, null, $pos))
    {
        // We have a valid description, and found the start, so go there.
        $tmp_desc = substr($tmp_desc, strpos($tmp_desc, $matches[3]));
    }
    if(strlen($product_info['products_target_keyword']) > 0)
    {
        $product_info['products_name'] = $product_info['products_target_keyword'];
    }
    else
    {
        $product_info['products_target_keyword'] = $tname;
        $product_info['products_name']           = $tname;
    }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo $title; ?></title>
    <meta name="description" content="<?php echo $description; ?>"/>
    <meta name="keywords" content="<?php echo $product_info['products_head_keywords_tag']; ?>"/>
    <link rel="stylesheet" type="text/css" href="/stylesheet.css">
    <script>(function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if(d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1&appId=<?php echo FB_APP_ID?>";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    FB.init({appId: "<?php echo FB_APP_ID?>", status: true, cookie: true});
    </script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
    <div id="fb-root"></div>

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div class="container">
    <div class="row">
    <div class="span8">

        <div style="text-align:center;padding:3em;">
            <a class="btn btn-danger"
               href="<?php echo '/shopping_cart.php?products_id=' . $product_info['products_id']; ?>">
                <span style="font-size:18pt; font-weight:bold">Choose Price & Options</span><br/>
                <?php
                if($product_info['products_msrp'] > 0 && $product_info['products_price'] < $product_info['products_msrp'])
                {
                    echo '<span style="color:#000;font-weight:bold;"><strike>MSRP ' . $currencies->display_price($product_info['products_msrp'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</strike> - ';
                    echo ' (more than ' . floor(($product_info['products_msrp'] - $cm_price) / ($product_info['products_msrp']) * 100) . '% off)</span>';
                }
                elseif($product_info['products_price'] > 0)
                {
                    echo '<span style="color:#000;font-weight:bold;">' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '';
                }?>
            </a>
        </div>

    <?php
    if(strlen($product_info['products_takeaway']) > 0)
    {
        echo '<p>', $product_info['products_takeaway'], '</p>';

        if(strlen($first_review) > 0)
        {
            //echo '<p>',$first_review,'</p>';
        }
        ?>

        <?php

        $cache->addCache('products_main' . $pmod);
    }
    ?>


    <?php
    if(is_numeric($reviews_rating) && $reviews_rating > 0)
    {
        echo draw_stars($reviews_rating);
    }?>
        <h1 style="margin-top:0em;">

            <?php echo $title; ?>.
        </h1>


        <div style="margin:1em;" class="fb-like" data-href="
                    <?php echo HTTP_SERVER . $test_url;?>" data-send="false" data-width="450" data-show-faces="true"
             data-action="recommend" data-font="tahoma">
        </div>













    <?php
    if(!$cache->doCache('products_main2' . $pmod, true, $lastmod))
    {

        $query = "select r.*, rd.* from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd ";
        $query .= "where r.products_id=" . (int)$product_info['products_id'] . " ";
        $query .= "and r.reviews_id = rd.reviews_id and ";
        $query .= "rd.languages_id = '1' and review_parent_id is null order by r.date_added desc;";

        $parentResultset = tep_db_query($query);
        $reviewHandler   = new review_Handler($parentResultset);
        ?>

        <!-- bottom reviews -->
        <b>Most recent reviews</b>
        <table border="0" style="margin-bottom: 20px;" cellspacing="0" cellpadding="2" width="100%">
            <?php
            if($reviewHandler->getReviewCount() > 0)
            {
                while($reviews = tep_db_fetch_array($parentResultset))
                {

                    $reviewHandler->writeToPage($reviews); // print parent
                    $reviewHandler->printChildren($parentResultset, $reviews['reviews_id']); // print children

                }
            }
            else
            {
                ?>
                <tr>
                    <td style="padding-top: 20px; font-weight: bold;">
                        <?php
                        echo 'No reviews yet. <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . 'Review Now!</a>';
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <b><a href="/product_reviews.php?products_id=<?php echo $product_info['products_id']?>">See All Reviews</a></b>


        <div style="text-align:right;">
            <?php
            echo '<a style="margin-bottom:40px;" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . 'Post a review, comment, or quesiton</a><br/>Get quick feedback.';
            ?>
        </div>
        <?php //echo $review;?>

        <a name="product_description_loc"> </a><p><span class="buzz">Ingredients & Description</span></p>
        <?php echo $tmp_desc; ?>

        <?php
        if(strtotime($product_info['products_last_modified']) < strtotime('2007-03-01'))
        {
            ?>
            <p>
                You have reached <?php echo $product_info['products_name']?> on Seacoast.com from the
                manufacturer <?php echo $product_info['manufacturers_name']?>. We're proud to have
                served <?php echo $product_info['products_viewed']?> customers
                since <?php echo $product_info['products_date_added']?> who were also interested in
                purchasing <?php echo $product_info['products_name']?>.
                It currently ranks as our <?php echo $product_info['products_ordered']?> most popular natural health
                product.
            </p>
            <p><b>Technical <?php echo $product_info['products_name']?> Details:</b> Locate this product using sku
                number <?php echo $product_info['product_sku']?> or ISBN <?php echo $product_info['product_upc']?>. For
                shipping, the weight is
                equal to <?php echo $product_info['products_weight']?> pounds and is currently out of stock. Typical
                inquiries include
                <?php
                $keywords = preg_split('/,/', $product_info['products_head_keywords_tag']);
                $keywords = array_reverse($keywords);
                foreach($keywords as $keyword)
                {
                    echo $keyword . ', ';
                }

                ?> and natural health. Seacoast Vitamins offers this product at a
                $<?php echo $product_info['products_msrp'] - $product_info['products_price']?> discount
                off of the suggested retail price $<?php echo $product_info['products_msrp']?>. Our price is
                $<?php echo $product_info['products_price']?>.</p>

            <?php } ?>

        <?php $hubs = match_hub_links($page_links, true); ?>


                </div>
                <div class="span4">
                    <div id="supplement_image">
                        <?php
                        if(isset($product_image_path) && file_exists($_SERVER['DOCUMENT_ROOT'] . $product_image_path) || 1 == 1)
                        {
                            @$dims = getimagesize($_SERVER['DOCUMENT_ROOT'] . $product_image_path);
                            $width = $dims[0] > 340 || $dims[0] < 1 ? 340 : $dims[0];   ?>
                            <div id="actual_prod_image" style="background-color:#ffffff;">
                                <img src="<?php echo $product_image_path;?>" id="prod_image" border="0"
                                     alt="<?php echo str_replace('"', '\'', $product_info['products_head_desc_tag']);?>."
                                     title="<?php echo $product_info['products_name'];?>" width="<?php echo $width;?>"/>
                            </div>

                            <?php } ?>
                    </div>
                    <?php  if(strlen($product_info['products_uses']) > 0 || strlen($product_info['products_uses']) > 0)
                {
                    ?>
                    <div class="alert alert-info">

                        <p><b>*Not intended to diagnose or treat diseases or ailments, and is not reviewed by the FDA.</b>
                        </p>

                        <span class="buzz">Uses & Indications.</span>

                        <ul>
                            <?php
                            $uses          = preg_split('/,/', str_replace(', ', ',', str_replace('  ', ' ', $product_info['products_uses'])));
                            $benefit_links = '';
                            shuffle($uses);
                            foreach($uses as $usename)
                            {
                                ?>
                                <li><?php echo ucwords($usename)?></li>
                                <?php
                                if($mflink = link_exists('/natural_uses.php?use=' . urlencode(strtolower($usename)), $page_links))
                                {
                                    $benefits = '<a href="' . $mflink . '">' . ucwords($usename) . ' ' . $product_info['products_type'] . '</a> &nbsp;' . $benefits;
                                }
                            }
                            ?>
                        </ul>
                        <?php if(strlen($product_info['products_uses']) > 0)
                    {

                        ?>

                        <span class="buzz" style="margin-top:1em;">Ailments & Concerns.</span>

                        <ul>
                            <?php
                            $uses          = preg_split('/,/', str_replace(', ', ',', str_replace('  ', ' ', $product_info['products_ailments'])));
                            $benefit_links = '';
                            shuffle($uses);
                            foreach($uses as $usename)
                            {
                                ?>
                                <li><?php echo ucwords($usename)?></li>
                                <?php
                                if($mflink = link_exists('/ailments.php?remedy=' . urlencode(strtolower($usename)), $page_links))
                                {
                                    $ailments = '<a href="' . $mflink . '">' . ucwords($usename) . ' ' . $product_info['products_type'] . '</a> &nbsp;' . $ailments;
                                }
                            }
                            ?>
                        </ul>



                        <?php } ?>
                    </div>
                    <?php } ?>

                    <p>
                        <?php
                        if(strlen($product_info['products_sku']) > 0)
                        {
                            echo 'SKU: ' . $product_info['products_sku'] . '<br />';
                        }

                        if(strlen($product_info['products_upc']) > 0)
                        {
                            echo 'UPC: ' . $product_info['products_upc'] . '<br />';
                        }

                        echo 'Distributed or manufactured from ', $product_info['manufacturers_name'], '. See more ';
                        $mflink = link_exists('/index.php?manufacturers_id=' . (int)$product_info['manufacturers_id'], $page_links);
                        if(!strlen($mflink))
                        {

                            $mflink = '/index.php?manufacturers_id=' . (int)$product_info['manufacturers_id'];
                        }
                        echo '<a href="', $mflink, '">', $product_info['manufacturers_name'], '</a> products.';
                        echo '<br />';
                        ?>
                    </p>

                    <script type="text/javascript">

                        //<![CDATA[

                        <!--

                        google_ad_client = "ca-pub-6691107876972130";

                        /* Product Page Right Side */

                        google_ad_slot = "3365106085";

                        google_ad_width = 336;

                        google_ad_height = 280;

                        //-->

                        //]]>

                    </script>
                    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">

                    </script>
                    <?php if(strtotime($product_info['products_last_modified']) > strtotime('2007-03-01') || strlen($tmp_desc) < 150)
                {
                    include(DIR_WS_MODULES . 'similar_products_google.php');
                }?>

                </div>
            </div>
            <?php
        $cache->addCache('products_main2' . $pmod);
    } //end cache
    ?>
    <?php
    if($seacoast_crawler)
    {
        echo '<div>', $product_info['products_id'], '</div><div>', HTTP_SERVER . $_SERVER['REQUEST_URI'], '</div>';
    }
    ?>
    <div class="fb-comments" data-href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>" data-num-posts="4" data-width="770"></div>
</div>
<?php $product_info['products_name'] = '';?>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
