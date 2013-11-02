 <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

<?php static $rel_count = 0; ?>
  <div class="abaproduct-content">
    <div class="abaproduct-image">
      <a href="http://books.google.com/books?id=<?php echo $node->model ?>&printsec=frontcover&img=1&zoom=2" title="<?php print check_plain($node->title) ?>" class="thickbox" rel="field_image_cache_<?php print $rel_count ?>"><img src="http://books.google.com/books?id=<?php echo $node->model ?>&printsec=frontcover&img=1&zoom=1" title="<?php echo $node->title ?>" height="140" /></a>
    <?php if (!$teaser) { ?>
      <div id="google-book-preview">
        <a href="http://books.google.com/ebooks/reader?id=<?php echo $node->model; ?>&printsec=frontcover&output=reader&retailer_id=<?php echo gbook_get_retailerid(); ?>"><img src="http://books.google.com/intl/en/googlebooks/images/gbs_preview_button1.gif" border="0" /></a>
      </div>
    <?php } ?>
    </div>
	  <div class="abaproduct-page-details">
      <div class="abaproduct-title"><h2>
      <?php print check_plain($node->title) ?> <span class="abaproduct-format">(Google eBook)</span>
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
            $role = $node->field_contributor_role[$tempCounter]['view'];
            if ($role != 'Author' && $role != 'With' && $role != '') $role = ' (' . $role . ')';
            else $role = '';
      // formats the author's name from "Last, First" to "First Last"
      // book_authoroutput_normalize is in search.module
            print '<a href="/search/gbook/?author_filter=' . urlencode($contributor['value']) .'">' . book_authoroutput_normalize($contributor['view']) . '</a>' . $role;
            $tempCounter++;
          }
        }
        print '</div>';
      } ?>
      <div class="gbook-logo" style="float:right"><img src="<?php echo base_path() . drupal_get_path('module', 'gbook') ?>/ge120x60.gif" /></div>
<?php    // if there is sell_price for the product, display sell_price
      if (!empty($node->sell_price)) {

      // compute_sell_price from /sites/all/modules/custom/store_pricing.module is called to compute sell price
        if(function_exists(compute_sell_price)){

          $pricearray = compute_sell_price($node->model, $node->sell_price, $node->type, $node);
          $listprice = $node->list_price;
          $sellprice = $pricearray['sellprice'];
          $price_difference = $listprice - $sellprice;
          if ($listprice > 0) {
            $percent_difference = round(($price_difference / $listprice) * 100);
          }
          //$percent_difference = round(($price_difference / $listprice) * 100);
          $showcart = $pricearray['showcart'];

          // if the sell price is 0 (not for sale?) or cart is not suppposed to be shown
          // display price message instead of price
          // 06/22/11 - we are now allowing $0 sell price, for free ebooks
          if ($showcart=="no") {
            $sellprice = variable_get('price_message', '');
            print check_plain($sellprice)?><br/><?php;
          }
          elseif ($price_difference > 0 && empty($node->field_gbook_publisher_id[0]['value'])) {
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
      ?>
      <?php
        }
      } ?>
      <?php
      if (is_ebook_disabled()) { ?>
        <strong>Availability:</strong> Sorry, ebooks cannot be purchased through this site.<br/>
        <?php
          $showcart = "no";
      } else { 
        $pubtime = strtotime($node->field_gbook_publish_date[0]['value']) + (60 * 60 * 9); // pad by 9 hours
      if ($pubtime > time()) $availability_message = 'Coming Soon';
      else $availability_message = 'Immediate Download';
      ?>
          <strong>Availability:</strong> <?php echo $availability_message; ?><br/>
      <?php } ?>
      <?php if (module_exists('uc_cart')&& !($showcart == "no")) {
        //This doesn't work for some reason when passing in the $node..
        //$output .= ;
        ?>
        <fieldset style="text-align: left; width: 250px">
        <?php print theme('uc_product_add_to_cart', $node); ?>
        </fieldset>
     <!-- <div class="abaproduct-special"> for LSI/ebook downloads/others </div>-->
      <?php } ?>
    </div>
      <?php $rel_count++; ?>
  </div>
  <?php if (!empty($node->content['body']['#value'])) { ?>
  <div class="abaproduct-body">
    <h3>Description</h3><hr/>
  <?php echo $node->content['body']['#value']; ?>
  </div>
  <?php } ?>

  <?php // load and display ONIX data, if available.
  if (!$teaser) {
    $onixdata = db_fetch_object(db_query("SELECT data FROM aba_share_onix_data WHERE isbn = '%s'", $node->field_gbook_physical_reference[0]['value']));
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


  <div class="clear-block clear"><fieldset style="float: left;"><legend>Product Details</legend>
  <?php if (!empty($node->model)) { ?>
    <strong>SKU:</strong> <?php print check_plain($node->model) ?> <br/>
  <?php }
  if (!empty($node->field_gbook_publisher[0]['view'])) { ?>
    <strong>Published:</strong> <?php print $node->field_gbook_publisher[0]['view'];
  if (!empty($node->field_gbook_publish_date[0]['view'])) {
    print ', '. $node->field_gbook_publish_date[0]['view'];
  } ?>
    <br/>
  <?php }
  if (!empty($node->field_pages[0]['view'])) { ?>
    <strong>Pages:</strong> <?php print check_plain($node->field_pages[0]['view']);
  ?>
    <br/>
  <?php }
  if (!empty($node->field_gbook_language[0]['view'])) { ?>
    <strong>Language:</strong> <?php print check_plain($node->field_gbook_language[0]['view']);
  ?>
    <br/>
  <?php } ?>
  <?php
  //! tease out metadata
  if (!empty($node->field_gbook_metadata[0]['value'])) {
    $metadata = unserialize($node->field_gbook_metadata[0]['value']);
    foreach ($metadata as $key => $value) {
      if ($key == 'Images') {
        if (strpos($value, 'true') !== FALSE) echo 'This eBook contains images.<br />';
        else echo 'NOTICE! This Google eBook does not contain images or other illustrations found in the print version.<br />';
      }
      elseif (!$acs_version && ($key == 'PDF' || $key == 'ePub')) {
        $acs_version = TRUE;
        echo 'This Google eBook includes an <a href="/gbook/help/adobe_digital_editions" target="_blank">Adobe Digital Editions</a> download.<br />';
      }
    }
    if (!$acs_version) echo '<em>This Google eBook does not include an Adobe Digital Editions download.</em><br />';
  }
  ?>
  <?php
  //! display google discount code to users
  if (!$teaser && user_access('administer store pricing')) {
    $discount = db_result(db_query("SELECT discount FROM google_discount_schedule WHERE code = '%s'", $node->field_gbook_discountcode[0]['value']));
  ?>
    <strong>Google Discount:</strong> <?php print $node->field_gbook_discountcode[0]['value'] . ' (' . $discount * 100 . '%)<br /><strong>Your Cost:</strong> ' . round(($node->list_price * abs(1-(float)$discount)), 2);
  ?>
  <br/>
  <?php
  //! Displaying if it is an agency or nonagency title.
  $is_agency = db_result(db_query("SELECT publisher_id FROM google_agency_nexus WHERE publisher_id = '%s'", $node->field_gbook_publisher_id[0]['value']));
  if ($is_agency) {
  ?>
    <strong>Agency eBook</strong>
  <?php
  } else { ?>
    <strong>Non-agency eBook</strong>
  <?php } ?>
    <br/>
  <?php } ?>
  </fieldset></div>

        <?php 
        // load related editions for gbooks
        if (!$teaser && !empty($node->field_gbook_family_id[0]['value'])) { 
        $relatedEditions = related_product($node->field_gbook_family_id[0]['value'], $node->model);
   if (count($relatedEditions['rest']) > 0) { ?>
        <fieldset class="collapsible abaproduct-related-editions"><legend><a name="relatededition">Related Editions (all)</a></legend>
        <?php print theme_item_list($relatedEditions['rest']); ?>
        </fieldset>
    <?php }
    } ?>

  <div class="clear-block clear">
    <div class="meta">
    <?php if ($taxonomy): ?>
      <div class="abaproduct-terms"><?php print $terms ?></div>
    <?php endif;?>
    </div>

    <?php if ($links): ?>
    <div class="links"><?php print $links; ?></div>
    <?php endif; ?>
  </div>

</div>
