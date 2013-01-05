<?php
// Hook into Wordpress so we can use all the custom functions and global variables
$file = dirname(__FILE__);
$file = substr($file, 0, stripos($file, "wp-content") );
// Added for multisite
require( $file . "/wp-load.php");
//require( $file . "/wp-admin/admin.php");
//require( $file . "/wp-admin/includes/admin.php");
// Now we can use Wordpress
global $shortcode_tags;
?>
<head>
<title>{#shortcodes_dlg.title}</title>
<script type="text/javascript" src="../../tinymce/tiny_mce_popup.js"></script>
<script type="text/javascript" src="js/dialog.js"></script>
</head>

<body>

<div class="y_logo_contener">
	<img src="img/wordpress-shortcode.png" width="128" height="128" alt="Youtube" />
</div>
<div class="yinstr">
	<p>{#shortcodes_dlg.instr}</p>
    <p>{#shortcodes_dlg.note}</p>
</div>

<form onSubmit="ShortcodesDialog.insert();return false;" action="#" method="post">
<div class="mceActionPanel">
<script type="text/javascript" language="javascript">
var jwl_sel_content2 = tinyMCE.activeEditor.selection.getContent();
</script>
<?php
echo "<div><table id='shortcodes_table'>";
foreach($shortcode_tags as $tagname=>$tag) {
    echo "<tr><td><a href=\"javascript:;\" onClick=\"tinyMCEPopup.close();\" onmousedown=\"tinyMCE.execCommand('mceInsertContent',false,'[".$tagname."]' + jwl_sel_content2 + '[/".$tagname."]');\">[".$tagname,"]</a></td></tr>";
}
echo "</table></div>";

?>

</div>

<div class="mceActionPanel">
  <div style="float:left;padding-top:5px">
  </div>
</div>
</form>
</body>