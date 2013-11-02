<?php ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
<head>
<?php print $head ?>
  <title><?php print $head_title ?></title>
  <?php print $styles ?>
  <?php print $scripts ?>

  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
  <script type="text/javascript">
    WebFont.load({
      google: {
      families: ['Minion']
      }
    });
  </script>

</head>
<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

            <!--layout-->

    <div class="HeaderFrame">
    <div class="Header">
      <div class="Logo">
        <a href="http://www.parnassusbooks.net" title="Parnassus Books"><img src="/sites/parnassus.indiebound.com/themes/parnassus/Images/logo.png" alt="Parnassus Books" /></a>
      </div>
      <div class="TagLine">
        <h4>An Independent Bookstore<br />For Independent People</h4>
      </div>
      <div class="LoginTab">
        <a href="/user" title="Login" class="Login">Login</a>
        <a href="/cart" title="Shopping Cart" class="Cart">Shopping Cart</a>
      </div>
      <div class="QuickSearch">
        <h3>Find a Book</h3>
        <?php print $search_box ?>
      </div>
    </div>
  </div>
  <div class="SiteFrame">
    <div class="Nav">
      <div class="TopLine"></div>
      <div class="Social">
<div class="fb-like" data-href="https://www.facebook.com/parnassusbooks1" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
<div class="twitter"><a href="https://twitter.com/#!/ParnassusBooks1"><img src="/sites/parnassus.indiebound.com/themes/parnassus/Images/twit-icon.png" alt="Twitter" /></a></div>
      </div>
      <div class="Links">
        <?php echo theme('links', $primary_links, array('class' => 'links primary-links menu-subcategories'))

        ?>

      </div>
      <div class="BtmLine"></div>
    </div>

            <!-- /header -->

    <div class="Content">

      <?php if ($left): ?>
      <div class="LeftCol">
<?php print $left ?>
      </div>
      <?php endif; ?>

            <!--/left-column-->

      <div class="MidCol">
        <?php print $breadcrumb; ?>
        <div class="Clear20"></div>
          <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
          <?php if ($node) { if ($node->type !='ababook' && $node->type != 'gbook') { ?>
          <?php if ($title): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h2>'; endif; ?>
          <?php }} else { ?>
          <?php if ($title): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h2>'; endif; ?>
          <?php } ?>
          <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
          <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
          <?php if ($show_messages && $messages): print $messages; endif; ?>

        <div class="MidColContent">
          <?php if ($content_prefix): ?>
            <div class="MidColContent-Prefix">
              <?php print $content_prefix; ?>
            </div>
          <?php endif; ?>
              <?php print $content; ?>
          <?php if ($content_suffix): ?>
            <div class="MidColContent-Suffix">
              <?php print $content_suffix; ?>
            </div>
          <?php endif; ?>
        </div>
              <?php print $feed_icons; ?>
      </div>

            <!--/content-->

      <?php if ($right): ?>
      <div class="RightCol">
      <?php print $right ?>
      </div>
      <?php endif; ?>

            <!--/right-column-->


  <div class="FooterFrame">
    <div class="Divider"></div>
    <div class="Footer">
      <div class="Social">
<div class="fb-like" data-href="https://www.facebook.com/parnassusbooks1" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
<div class="twitter"><a href="https://twitter.com/#!/ParnassusBooks1"><img src="/sites/parnassus.indiebound.com/themes/parnassus/Images/twit-icon.png" alt="Twitter" /></a></div>
      </div>
      <p class="Location">
        Store Location: 3900 Hillsboro Pike, Nashville, TN &nbsp;:&nbsp; 615.953.2243 &nbsp;:&nbsp; Open Mon-Sat 10am-8pm. Sun 12pm-5pm
      </p>
      <p>
        All content © 2011 by Parnassus Books unless otherwise noted.
      </p>
      <p>
        Website powered by the American Booksellers Association and IndieCommerce.
        Design by <a href="http://www.familytreedesign.net" title="Familytree">Familytree</a>.
      </p>
      <div class="Clear20"</div>
    </div>
  </div>

            <!--/footer-->

            <!--/layout-->


  <?php print $closure ?>
  </body>
</html>
