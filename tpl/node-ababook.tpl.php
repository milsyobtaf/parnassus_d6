<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

<?php static $rel_count = 0; ?>
  <div class="abaproduct-content">
    <div class="abaproduct-image">
      <a href="http://images.indiebound.com/<?php print substr(check_plain($node->model),-3,3) ?>/<?php print substr(check_plain($node->model),-6,3) ?>/<?php print check_plain($node->model) ?>.jpg" title="<?php print check_plain($node->title) ?>" class="thickbox" rel="field_image_cache_<?php print $rel_count ?>"><img src="http://images.booksense.com/images/books/<?php print substr(check_plain($node->model),-3,3) .'/'. substr(check_plain($node->model),-6,3) .'/FC'. check_plain($node->model) .'.JPG" title="'. check_plain($product->title) .'"'?> onerror="this.src = '/sites/all/modules/custom/aba_product/book_not_found.jpg';" /></a>
    <?php if (!$teaser) { ?>
      <div id="google-book-preview">
        <script type="text/javascript" src="http://books.google.com/books/previewlib.js"></script>
        <script type="text/javascript">
        GBS_insertPreviewButtonPopup('ISBN:<?php echo $node->model ?>');
        GBS_setViewerOptions({'showLinkChrome': false});
        </script>
      </div>
    <?php } ?>
    </div>
    <?php 
    if (function_exists(has_staff_reviews) && ($reviews = has_staff_reviews(($node->model),($node->nid)))) { ?>
     <div class="abaproduct-staff-picks">
       <img src="/sites/all/modules/custom/aba_product/staff_picks_image_small.png" />
     </div>
    <?php } ?>
    <div class="abaproduct-page">
      <div class="abaproduct-title"><h2>
      <?php print check_plain($node->title) ?>
      <?php if (!empty($node->field_format[0]['view'])) { ?>
        (<?php print check_plain($node->field_format[0]['view']) ?>)
      <?php } ?>
      </h2></div>
      <?php if (is_array($node->field_contributor_name) && count($node->field_contributor_name) > 0) { ?>
      <div class="abaproduct-authors">
      <?php $tempCounter = 0;
        foreach ($node->field_contributor_name as $contributor) {
          if (!empty($contributor['view'])) {
            if ($tempCounter == 0) {
              print 'By ';
            }
            elseif ($tempCounter >0) {
              print ', ';
            }
            // formats the author's name from "Last, First" to "First Last"
            // book_authoroutput_normalize is in search.module
            print '<a href="/search/apachesolr_search/?author_filter=' . urlencode($contributor['value']) .'">' . book_authoroutput_normalize($contributor['view']) . '</a>';
            $tempCounter++;
          }
        }
        print '</div>';
      }?>

      <div class="abaproduct-page-details">
      <?php
        // if there is sell_price for the product, display sell_price
        if (!empty($node->sell_price)) {
    
          // compute_sell_price from /sites/all/modules/custom/store_pricing.module is called to compute sell price
          if(function_exists(compute_sell_price)) {
            $pricearray = compute_sell_price($node->model, $node->sell_price, $node->type, $node);
            $listprice = $node->list_price;                                   
            $sellprice = $pricearray['sellprice'];                            
            $price_difference = $listprice - $sellprice;
            $percent_difference = round(($price_difference / $listprice) * 100);
            $showcart = $pricearray['showcart'];

            // if the sell price is 0 (not for sale?) or cart is not suppposed to be shown
            // display price message instead of price
            if ($sellprice == 0 || $showcart=="no") {
              if (function_exists(is_notforsale) && is_notforsale($node->model)) {
                $sellprice = variable_get('notforsale_message', '');
              }
              else {
                $sellprice = variable_get('price_message', '');
              }?>
              <div class="abaproduct-price"><?php print check_plain($sellprice);?></div><?php
            }
            elseif ($price_difference > 0){                                     
              // if there is a savings for the customer, display the sell price with list price and the savings for the customer
              $listprice = uc_currency_format($listprice);                  
              $price_difference = uc_currency_format($price_difference);    
              $sellprice = uc_currency_format($sellprice);?> 
              <div class="abaproduct-listprice">List Price: <?php print check_plain($listprice);?></div>      
              <div class="abaproduct-price">Our Price: <?php print check_plain($sellprice);?></div>     
              <div class="abaproduct-discount">(Save: <?php print check_plain($price_difference);?> <?php print check_plain($percent_difference);?>%)</div>
            <?php
            }     
            else {
              // if there is no savings for the customer, display only the sell price
              $sellprice = uc_currency_format($sellprice);
            ?>
              <div class="abaproduct-price"><?php print check_plain($sellprice);?></div>
            <?php
            }      
          }
        }
        ?>
      </div>
      <div class="abaproduct-cart">
      <?php if (function_exists(get_lsi_qty)) {
        $lsi_info = get_lsi_qty($node->model);
      }

      $availability_status = "";
//drupal_set_message('<pre>In node ababook before the content field view_view =  '.  check_plain(print_r($node, 1)) . "</pre>", TRUE);
      
      if (isset($lsi_info['lsi_qty']) && $lsi_info['lsi_qty'] > 0) {
        $node->field_availability[0]['value'] = '5';
        		 ?>
        <fieldset class="availability">
        <?php
        $hide_onhand = variable_get('lsi_hide_onhand', FALSE);
        if (module_exists(store_outlets)) {
          $multi_outlet_lsi_array = multi_outlet_lsi_availability($lsi_info['lsi_model']);
          for ($i = 0 ; $i < sizeof($multi_outlet_lsi_array) ; $i++) {
            $each_outlet = $multi_outlet_lsi_array[$i];
            if (isset($each_outlet['lsi_outlet_name'])) { ?>
            <strong><?php print $each_outlet['lsi_outlet_name'] ?> </strong><br/>
            <?php }
            if (!$hide_onhand) {
              print $each_outlet['lsi_qty_onhand'] . " on hand, as of ". $each_outlet['lsi_upload_date']. (!empty($each_outlet['lsi_location']) ? " (".$each_outlet['lsi_location'].")":"");
            }
            else {
              print "On hand as of ". $each_outlet['lsi_upload_date']. (!empty($each_outlet['lsi_location']) ? " (".$each_outlet['lsi_location'].")":"");
            }
            ?><br/><?php
          }
        } else {
          $single_outlet_lsi_array = single_outlet_lsi_availability($lsi_info['lsi_model']);
          if (!$hide_onhand) {
            print $single_outlet_lsi_array['lsi_qty_onhand'] . " on hand as of ". $single_outlet_lsi_array['lsi_upload_date']. (!empty($single_outlet_lsi_array['lsi_location']) ? " (".$single_outlet_lsi_array['lsi_location'].")":"");
          }
          else {
            print "On hand as of ". $single_outlet_lsi_array['lsi_upload_date']. (!empty($single_outlet_lsi_array['lsi_location']) ? " (".$single_outlet_lsi_array['lsi_location'].")":"");
          }
          ?><br/><?php
        }
        ?></fieldset>
        <?php
      }
      else {
        if (!empty($node->field_availability[0]['view'])) { 
          // if the book is out of print (1) or not yet published (4), do not show button to cart.
          if (($node->field_availability[0]['value']=='1') || ($node->field_availability[0]['value']=='4')  
            || ((is_special_order_disabled() && ($node->field_availability[0]['value']=='0') && !is_book_manually_added($node->model))))
            $showcart="no";
          if ($node->field_availability[0]['value']=='4' && is_nyp_enabled()) $showcart = "yes";
        }
      }

      if (module_exists('uc_cart')&& !($showcart == "no")) {
          //This doesn't work for some reason when passing in the $node..
          //$output .= ;
          print theme('uc_product_add_to_cart', $node); ?>
          <!-- <div class="abaproduct-special"> for LSI/ebook downloads/others </div>-->
      <?php }
      if (!empty($node->field_availability[0]['view'])) { ?>
        <div class="abaproduct-status"><?php print (content_format('field_availability', $node->field_availability[0],'default')); ?></div>
      <?php } ?>
      </div>
      
    <div class="abaproduct-related-editions">
    <table><tr><td>
    
        <?php if (!$teaser && !empty($node->field_family_id[0]['value'])) { ?>
            <?php 
                $relatedEditions = related_product($node->field_family_id[0]['value'], $node->model);
                if (count($relatedEditions['top'] > 0)) {
                  print '<div class="abaproduct-related-editions-head">Related Editions</div>';
                  print theme_item_list($relatedEditions['top']);
                }
                if (count($relatedEditions['rest']) > 0) {
                  print '<div class="abaproduct-related-editions-list"><a href="#relatededition">More...</a></div>';
                } ?>
        <?php } ?>
    </td></tr></table>
    </div>


      
    </div>
      <?php $rel_count++; ?>
  </div>

  <!--  if reviews are available for this title.. add them here.-->
  <?php if (function_exists(has_staff_reviews) && ($reviews = has_staff_reviews($node->model, $node->nid))) { ?>
  <div class="staffreview-body">
    <h3>Staff Reviews</h3><hr />
    <?php
      foreach($reviews as $review) { ?>
        <br /><?php print $review['review'];?>  <?php isset($review['by']) ? print "--".$review['by'] : '' ?><br /><?php 
      }
    ?>
  <br />
  </div>
  <?php }?>
  <!--  end reviews-->
  <!--  INL blurbs -->
  <?php if (function_exists(has_inl_blurbs) && ($blurbs = has_inl_blurbs($node->model, $node->nid))) { ?>
  <div class="inl-blurb-body">
   <!-- <h3>Indie Next List </h3><hr/>-->
   <?php 
     foreach($blurbs as $blurb) { ?>
       <h3>Indie Next List </h3><?php print $blurb['date'];?><hr/><?php 
       print $blurb['blurb'];?> -- <?php print $blurb['by']?><hr/><br/> <?php 
     }
   ?>
  </div>
  <?php }?>
  <!--  end INL blurbs -->
  <?php if (!empty($node->content['body']['#value'])) { ?>
  <div class="abaproduct-body">
    <h3>Description</h3><hr/>
  <?php } ?>
  <?php if(function_exists(aba_product_show_product_video)) {
    //print bookstream video info
    print aba_product_show_product_video($node->model);
  } ?>
    <?php print $node->content['body']['#value']; ?>
  <?php if (!empty($node->content['body']['#value'])) { ?>
  </div>
  <?php } ?>

  <?php // load and display ONIX data, if available.
  if (!$teaser) {
    $onixdata = db_fetch_object(db_query("SELECT data FROM aba_share_onix_data WHERE isbn = '%s'", $node->model));
    $onixdata = drupal_unpack($onixdata);
    if (!empty($onixdata->bio)) { ?>
  <div class="abaproduct-body">
    <h3>About the Author</h3><hr/>
    <?php print $onixdata->bio; ?>
  </div>
  <?php }
    if (!empty($onixdata->blurbs)) { ?>
  <div class="abaproduct-body">
    <h3>Praise for <?php echo $node->title; ?>&hellip;</h3><hr/>
    <?php print $onixdata->blurbs; ?>
  </div>
  <?php }
  } // end if $teaser ?>

  <div class="clear-block clear"><fieldset style="clear: both;"><legend>Product Details</legend>
  <?php if (!empty($node->field_isbn[0]['view'])) { ?>
    <strong>ISBN-10:</strong> <?php print check_plain($node->field_isbn[0]['view']) ?><br/>
  <?php }
  if (!empty($node->model)) { ?>
    <strong>ISBN-13:</strong> <?php print check_plain($node->model) ?> <br/>
  <?php }
  if (!empty($node->field_largeprint[0]['view'])) {
    print check_plain($node->field_largeprint[0]['view']) ?><br/>
  <?php }
  if (!empty($node->field_abridged[0]['view'])) {
    print check_plain($node->field_abridged[0]['view']) ?><br/>
  <?php }
  if (!empty($node->field_publisher[0]['view'])) { ?>
    <strong>Published:</strong> <?php print $node->field_publisher[0]['view'];
  if (!empty($node->field_publish_date[0]['view'])) {
      $node->field_streetdate[0]['value'] = trim($node->field_streetdate[0]['value']);
      if (!empty($node->field_streetdate[0]['value'])) {
        if (substr($node->field_streetdate[0]['value'],0,4) == '0000' || substr($node->field_streetdate[0]['value'], 5,5) == '01-01') print ', '. $node->field_publish_date[0]['view'];
    else print ', ' . date("m/d/Y", strtotime($node->field_streetdate[0]['value']));
      }
      else print ', ' . $node->field_publish_date[0]['view'];
  } ?>
    <br/>
  <?php }
  if (!empty($node->field_pages[0]['view'])) { ?>
    <strong>Pages:</strong> <?php print check_plain($node->field_pages[0]['view']);
  ?>
    <br/>
  <?php }
  if (!empty($node->field_language[0]['view'])) { ?>
    <strong>Language:</strong> <?php print check_plain($node->field_language[0]['view']);
  ?>
    <br/>
  <?php }
  if (user_access('administer store pricing') && !empty($node->field_ingramdiscountcode[0]['value'])) { ?>
    <strong>Ingram Discount Code:</strong> <?php print check_plain($node->field_ingramdiscountcode[0]['value']);
  ?>
    <br/>
  <?php } ?>
  </fieldset></div>

  <?php if (!empty($node->field_minage[0]['view']) ||
            !empty($node->field_maxage[0]['view']) ||
            !empty($node->field_mingrade[0]['view']) ||
            !empty($node->field_maxgrade[0]['view']) ) { ?>
  <fieldset class="collapsible"><legend>Recommended Reading Level</legend>
    <?php if (!empty($node->field_minage[0]['view'])) { ?>
    <strong>Minimum Age:</strong> <?php print check_plain($node->field_minage[0]['view']); ?><br/>
    <?php } ?>
    <?php if (!empty($node->field_maxage[0]['view'])) { ?>
    <strong>Maximum Age:</strong> <?php print check_plain($node->field_maxage[0]['view']); ?><br/>
    <?php } ?>
    <?php if (!empty($node->field_mingrade[0]['view'])) { ?>
    <strong>Minimum Grade Level:</strong> <?php print check_plain($node->field_mingrade[0]['view']); ?><br/>
    <?php } ?>
    <?php if (!empty($node->field_maxgrade[0]['view'])) { ?>
    <strong>Maximum Grade Level:</strong> <?php print check_plain($node->field_maxgrade[0]['view']); ?><br/>
    <?php } ?>
  </fieldset>
  <?php } ?>


    <?php if (count($relatedEditions['rest']) > 0) { ?>
        <fieldset class="collapsible abaproduct-related-editions"><legend><a name="relatededition">Related Editions (all)</a></legend>
        <?php print theme_item_list($relatedEditions['rest']); ?>
        </fieldset>
    <?php } ?>

  <div class="clear-block clear">
    <div class="meta">
    <?php if(!module_exists('product_browser')) {?>
    <?php if ($taxonomy): ?>
      <div class="abaproduct-terms"><?php print $terms ?></div>
    <?php endif;?>
    <?php }?>
    </div>

    <?php if ($links): ?>
    <div class="links"><?php print $links; ?></div>
    <?php endif; ?>
  </div>

</div>
