<style>

.pageContents ol, .pageContents ul {
  margin: 10px 0 10px 10px;
}

.pageContents ol li,.pageContents ul li {
  margin: 5px 0 5px 10px;
}

</style>

<ul>
  <li>The <b>left column</b> identifies the schema tag <i>name</i> or <i>property</i></li>
  <li>
    The <b>right column</b> describes the logic used to populate that tag's <i>content</i> or <i>value</i>. 
    <br>
    If no content can be obtained from the primary, left-most source, then the source immediately to the right is attempted, and so on.
  </li>
<br>
<br>
<br>

<table class="mainTable" border="0" cellspacing="0" cellpadding="0">
<?php

$mbm = 'Mo Betta Meta';
$nbm = 'NSM Better Meta';
$ee = 'ExpressionEngine';
$custom_field = "<a href='$config_url'>$mbm custom field value</a>";
$default_image = "<a href='$config_url'>$mbm default image</a>";
$default_page_type = "<a href='$config_url'>$mbm default page type</a>";
$overrides = '&rarr;';

foreach($platforms as $name => $tags) {
  echo "<tr><th>$name tag property or name</th><th>Tag content or value</th></tr>";

  foreach($tags as $tag) {
    if (in_array($tag, array('card', 'type'))) {
      $order = "$custom_field $overrides $default_page_type";
    } elseif (in_array($tag, array('name', 'title'))) {
      $order = "$custom_field $overrides $nbm title $overrides Channel entry title $overrides $nbm default site title";
    } elseif ($tag == 'description') {
      $order = "$custom_field $overrides $nbm description $overrides $nbm default description";
    } elseif ($tag == 'image') {
      $order = "$custom_field $overrides $default_image";
    } elseif ($tag == 'url') {
      $order = "$nbm canonical URL $overrides $ee page URL";
    } elseif ($tag == 'site_name') {
      $order = "$nbm default site title $overrides $ee site name";
    } else {
      $order = '';
    }

    echo "<tr><td><b>$tag</b></td><td>$order</td></tr>";
  }
}

?>
</table>

<br>
<br>

<h3>More</h3>

<ul>
  <li><a href='<?=$home_url?>'><b><?=lang('mcp_nav_home')?></b></a></li>
  <li><a href='<?=$usage_url?>'><b><?=lang('mcp_nav_usage')?></b></a></li>
  <li><a href='<?=$config_url?>'><b><?=lang('mcp_nav_config')?></b></a></li>
</ul>

