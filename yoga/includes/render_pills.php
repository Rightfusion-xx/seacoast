<?php
function render_pills($pill_query)
{
    if($product_info=tep_db_fetch_array($pill_query))
    {
        ?><h2>Recommended Nutrition</h2><?php
    }
    
    do{
        ?>
        <div class="prod_res" style="margin-top:10px;">
            <a href="/product_info.php?products_id=<?php echo $product_info['products_id']; ?>"><?php echo $product_info['products_name']; ?></a> 
            <br/><?php echo $product_info['products_description'];?>
        </div>
        <?php  
    
    
    }while($product_info=tep_db_fetch_array($pill_query));
    
    

}



?>