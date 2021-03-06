<style>

.pageContents ol, .pageContents ul {
  margin: 10px 0 10px 20px;
}

.pageContents ol li,.pageContents ul li {
  margin: 5px 0 5px 20px;
}

.pageContents .mainTable td.input-text input {
  width: 30%;
}

.pageContents .mainTable td.input-text {
  font-family: monospace;
}

</style>

<?php

$mbm = '<i>Mo Betta Meta</i>';
$channel = 'blog';
$prefix = $channel.$config['field_name_prefix'];

?>

<?=form_open($action_url, '', $form_hidden)?>
<table class="mainTable" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr class="">
      <th colspan="2">Site-wide field name marker</th>
    </tr>
     <tr>
      <td><b>Field name marker</b> <small>prefixed to the suffixes below</small></td>
      <td class="input-text"><?=$channel?> <input type="text" name="field_name_prefix" value="<?=$config['field_name_prefix']?>"> <?=$config['so_name']?></td>
    </tr>
    <tr>
      <th colspan="2">Schema.org field name suffixes</th>
    </tr>
    <tr>
      <td><b>Name</b></td>
      <td class="input-text"><?=$prefix?> <input type="text" name="so_name" value="<?=$config['so_name']?>"></td>
    </tr>
    <tr>
      <td><b>Description</b></td>
      <td class="input-text"><?=$prefix?> <input type="text" name="so_description" value="<?=$config['so_description']?>"></td>
    </tr>
    <tr class="">
      <td><b>Image</b></td>
      <td class="input-text"><?=$prefix?> <input type="text" name="so_image" value="<?=$config['so_image']?>"></td>
    </tr>
    <tr>
      <th colspan="2">Twitter field name suffixes</th>
    </tr>
    <tr>
      <td><b>Title</b></td>
      <td class="input-text"><?=$prefix?> <input type="text" name="tw_title" value="<?=$config['tw_title']?>"></td>
    </tr>
    <tr>
      <td><b>Description</b></td>
      <td class="input-text"><?=$prefix?> <input type="text" name="tw_description" value="<?=$config['tw_description']?>"></td>
    </tr>
    <tr>
      <td><b>Image</b></td>
      <td class="input-text"><?=$prefix?> <input type="text" name="tw_image" value="<?=$config['tw_image']?>"></td>
    </tr>
    <tr class="">
      <th colspan="2">OpenGraph field name suffixes</th>
    </tr>
    <tr>
      <td><b>Title</b></td>
      <td class="input-text"><?=$prefix?> <input type="text" name="og_title" value="<?=$config['og_title']?>"></td>
    </tr>
    <tr>
      <td><b>Description</b></td>
      <td class="input-text"><?=$prefix?> <input type="text" name="og_description" value="<?=$config['og_description']?>"></td>
    </tr>
    <tr>
      <td><b>Image</b></td>
      <td class="input-text"><?=$prefix?> <input type="text" name="og_image" value="<?=$config['og_image']?>"></td>
    </tr>
    <tr class="">
      <th colspan="2">Cross-platform field name suffix</th>
    </tr>
    <tr>
      <td><b>Page type</b> <small>distinguish article from video</small></td>
      <td class="input-text"><?=$prefix?> <input type="text" name="page_type" value="<?=$config['page_type']?>"></td>
    </tr>
    <tr class="">
      <th colspan="2">Default schema tag values</th>
    </tr>
    <tr>
    <tr>
      <td><b>Image</b> <small>relative path, combined with site URL</small></td>
      <td class="input-text">http://<?=$_SERVER['HTTP_HOST']?>/ <input type="text" name="default_image" value="<?=$config['default_image']?>"></td>
    </tr>
      <td><b>Page type</b></td>
      <td>
        <select name="default_page_type">
          <option value="article" <?php echo $config['default_page_type'] == 'article' ? 'selected="selected"' : ''; ?>>article</option>
          <option value="video" <?php echo $config['default_page_type'] == 'video' ? 'selected="selected"' : ''; ?>>video</option>
        </select>
      </td>
    </tr>
    <tr class="">
      <th colspan="2">Debug Logging</th>
    </tr>
    <tr>
      <td><b>Developer Log</b></td>
      <td><input type="checkbox" name="log_developer" value="y" <?php echo $config['log_developer'] == 'y' ? 'checked="checked"' : ''; ?>></td>
    </tr>
    <tr>
      <td><b>Template</b></td><td>
      <input type="checkbox" name="log_template" value="y" <?php echo $config['log_template'] == 'y' ? 'checked="checked"' : ''; ?>></td>
    </tr>
  </tbody>
</table>
<input type="submit" name="submit" value="Update Config" class="submit">
</form>

