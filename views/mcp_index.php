<style>

.pageContents ol, .pageContents ul {
  margin: 10px 0 10px 10px;
}

.pageContents ol li,.pageContents ul li {
  margin: 5px 0 5px 15px;
}

</style>

<h3>Purpose</h3>

Render schema tags for the following social media platforms.
<br><br>

<table class="mainTable" border="0" cellspacing="0" cellpadding="0">
<?php

$mbm = 'Mo Betta Meta';
$nbm = 'NSM Better Meta';
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
      $order = "$nbm canonical URL $overrides EE page URL";
    } else {
      $order = '';
    }

    echo "<tr><td><b>$tag</b></td><td>$order</td></tr>";
  }
}

?>
</table>

<br>
Note the right-hand column above describes the logic used to populate the meta tag's content or value. 
<br>
If no content can be obtained from the primary, left-most source, then the source immediately to the right is attempted.
<br>
And so on, until the final, right-most source which is guaranteed&trade; to provide a generalized, default value.
<br><br>

<h3>Dependencies</h3>

<ol>
  <li>EE2</li>
  <li>NSM Better Meta</li>
</ol>

<br>

<h3>Usage</h3>

Add the following to a template:

<pre>
&lt;head&gt;

  {exp:nsm_better_meta:template entry_id='{structure:page:entry_id}'}

  {exp:mo_betta_meta:tags entry_id="{structure:page:entry_id"}

&lt;/head&gt;
</pre>

<br>

<h3>Configuration</h3>

<ul>
  <li><a href='<?=$config_url?>'><b>Edit</b></a></li>
</ul>


