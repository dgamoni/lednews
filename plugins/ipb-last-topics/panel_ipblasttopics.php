<?php
define( 'MYPLUGINNAME_PATH', plugin_dir_path(__FILE__) );
 
if(file_exists(MYPLUGINNAME_PATH .'/ipb_config.php')){
require(MYPLUGINNAME_PATH .'/ipb_config.php');
}

if(isset($_POST['submit'])){
	
$my_file = MYPLUGINNAME_PATH .'/ipb_config.php';
	
$handle = fopen($my_file, 'w');
$fileContent ="<?php
\$ipburl = '$_POST[ipburl]';\n
\$dbhost = '$_POST[dbhost]';\n
\$dbname = '$_POST[dbname]';\n
\$dbuser = '$_POST[dbuser]';\n
\$dbpass = '$_POST[dbpass]';\n
\$dbprifix = '$_POST[dbprifix]';\n
\$limit = '$_POST[limit]';\n
?>";

fwrite($handle, $fileContent);
fclose($handle);
?>
<div id="setting-error-settings_updated" class="updated settings-error"><p><?php echo "" . __('Settings saved', 'ipb') . ""; ?></strong></p></div>
<?php	
}
?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br>
  </div>
  <h2><?php echo "" . __('Settings', 'ipb') . ""; ?></h2>
  <form action="" method="post">
    <table class="form-table">
      <tbody>
     	<tr valign="top">
          <th scope="row">
          	<h4><?php echo "" . __('forum Address', 'ipb') . ""; ?> :</h4>
          </th>
        </tr>
        
        <tr valign="top">
          <th scope="row">
          	<label><?php echo "" . __('forum Address', 'ipb') . ""; ?></label>
          </th>
          <td>
          	<input type="text" class="regular-text ltr" value="<?php echo $ipburl;?>" name="ipburl">
          </td>
        </tr>
        
        <tr valign="top">
          <th scope="row">
          	<h4><?php echo "" . __('community information database', 'ipb') . ""; ?>:</h4>
          </th>
        </tr>
        
        <tr valign="top">
          <th scope="row">
          	<label><?php echo "" . __('server name', 'ipb') . ""; ?></label>
          </th>
          <td>
          	<input type="text" class="regular-text ltr" value="<?php echo $dbhost;?>" name="dbhost">
          </td>
        </tr>
        
        <tr valign="top">
          <th scope="row">
          	<label><?php echo "" . __('database name', 'ipb') . ""; ?></label>
          </th>
          <td>
          	<input type="text" class="regular-text ltr" value="<?php echo $dbname;?>" name="dbname">
          </td>
        </tr>
        
        <tr valign="top">
          <th scope="row">
          	<label><?php echo "" . __('database username', 'ipb') . ""; ?></label>
          </th>
          <td>
          	<input type="text" class="regular-text ltr" value="<?php echo $dbuser;?>" name="dbuser">
          </td>
        </tr>
        
        <tr valign="top">
          <th scope="row">
          	<label><?php echo "" . __('database password', 'ipb') . ""; ?></label>
          </th>
          <td>
          	<input type="password" class="regular-text ltr" value="<?php echo $dbpass;?>" name="dbpass">
          </td>
        </tr>
        
        <tr valign="top">
          <th scope="row">
          	<label><?php echo "" . __('prefix tables', 'ipb') . ""; ?></label>
          </th>
          <td>
          	<input type="text" class="regular-text ltr" value="<?php echo $dbprifix;?>" name="dbprifix">
          </td>
        </tr>
        
        <tr valign="top">
          <th scope="row">
          	<h4><?php echo "" . __('display topics', 'ipb') . ""; ?> :</h4>
          </th>
        </tr>
        
        <tr valign="top">
          <th scope="row">
          	<label><?php echo "" . __('display topics', 'ipb') . ""; ?></label>
          </th>
          <td>
          	<input type="text" class="small-text ltr" value="<?php echo $limit;?>" name="limit">
             <label><?php echo "" . __('item', 'ipb') . ""; ?></label>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input type="submit" value="<?php echo "" . __('save', 'ipb') . ""; ?>" class="button button-primary" id="submit" name="submit">
    </p>
  </form>
</div>
