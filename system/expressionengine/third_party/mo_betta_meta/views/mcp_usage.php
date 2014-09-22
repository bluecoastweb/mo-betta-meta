<style>

.pageContents ol, .pageContents ul {
  margin: 10px 0 10px 10px;
}

.pageContents ol li,.pageContents ul li {
  margin: 5px 0 5px 10px;
}

</style>

<?php

$mbm = '<i>Mo Betta Meta</i>';
$channel = 'blog';
$prefix = $channel.$config['field_name_prefix'];

?>

<h3>Add <tt>mo_betta_meta</tt> tag to EE Template</h3>

Add the following to a template:

<pre>
&lt;head&gt;

  {exp:nsm_better_meta:template entry_id='{structure:page:entry_id}'}

  {exp:mo_betta_meta:tags entry_id="{structure:page:entry_id"}

&lt;/head&gt;
</pre>

<br>

<h3>Override schema tag defaults</h3>

<p>
To override the default value for a <?=$mbm?> schema tag:
<ol>
  <li>Create a custom channel field for the tag (in one or more channels)</li>
  <li>Name the field in such a way that it can be uniquely identified by <?=$mbm?></li>
</ol>
</p>

<p>
A <?=$mbm?>-enabled custom field name will typically be composed of three parts:
<ol>
  <li>Short name of the channel, eg <b><?=$channel?></b>. This satisfies an EE constraint, ensuring that the field name will be unique across all channels.</li>
  <li>A site-wide <?=$mbm?> marker, eg <b><?=$config['field_name_prefix']?></b>. This enables <?=$mbm?> to identify the field.</li>
  <li>One of the several <?=$mbm?> platform suffixes, eg, <b><?=$config['so_name']?></b>. This enables <?=$mbm?> to identify the platform and tag.</li>
</ol>
</p>

<p>
For example, to override the default value of the <i>Schema.org</i> <b>name</b> tag for one or more entries of a channel named <b><?=$channel?></b>, create a new custom channel field in the <b><?=$channel?></b> channel field group with the following:
<ul>
  <li>Field Type: <b>Text Input</b></li>
  <li>Field Name: <b><?=$prefix?><?=$config['so_name']?></b></li>
</ul>
</p>

<p>
Then in Publish page for each <b>blog</b> entry, simply add your override text in the custom channel field provided.
</p>

<br>

<h3>More</h3>

<ul>
  <li><a href='<?=$home_url?>'><b><?=lang('mcp_nav_home')?></b></a></li>
  <li><a href='<?=$tags_url?>'><b><?=lang('mcp_nav_tags')?></b></a></li>
  <li><a href='<?=$config_url?>'><b><?=lang('mcp_nav_config')?></b></a></li>
</ul>

