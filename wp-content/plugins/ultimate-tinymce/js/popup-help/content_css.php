<html><head>
<title>Content CSS</title>
<link rel=StyleSheet href="pop-upstyle.css" type="text/css" media="screen">
</head>

<BODY BGCOLOR="#F2F9FA" TEXT="#000000" LINK="#99CCFF" VLINK="#99CCFF" ALINK="#99CCFF" leftmargin=0 rightmargin=0 topmargin=0 bottommargin=0 marginheight=0 marginwidth=0>

<!-- OUTER TABLE-->
<TABLE cellpadding=0 cellspacing=0 border="0" bordercolor="666666" width="100%" height="100%"><tr><td ALIGN="CENTER" VALIGN="TOP">

<!-- CONTENT TABLE -->
<TABLE cellpadding=0 cellspacing=0 border="0" bordercolor="666666" width="80%"><tr><td ALIGN="left" VALIGN="TOP">
<br>

<span class="title" style="padding:0px 5px 0px 5px;background-color:#CCFCD9;border:1px solid #339966;"><strong>content_css:</strong></span>
<br />
This option will enable the content_css.css file found in the <strong>wp-content/plugins/ultimate-tinymce/css/</strong> directory.

<br /><br />

<span class="title" style="padding:0px 5px 0px 5px;background-color:#CCFCD9;border:1px solid #339966;"><strong>Usage:</strong></span>
<br />
Checking this option will "tell" the tinymce editor to load it's stylesheet from the alternate location mentioned above.<br /><br />
I have already included the default file.  This file can be modified (CSS knowledge required).<br /><br />
What this allows is for the user to modify the file mentioned above to suit their preferences.  There are a few various options a user can modify.  Here is one example:<br /><br />
<strong>Adjust editor default font size:</strong><br /><br />
On line one of the content.css file is the following code...<br />
<strong>body, td, pre {color:#000; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; margin:8px;}</strong><br /><br />
The font size can be adjusted by making the following modification (changes in red)...<br />
<strong>body, td, pre {color:#000; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:<span style="color:#FF0000;">16px</span>; margin:8px;}</strong><br /><br />

You can also adjust the font, the margin between lines, etc.<br /><br />
Note:<br />
These adjustments <strong>ONLY</strong> affect content inside the editor, and <strong>NOT</strong> what is rendered on the front-end of the website.<br /><br />

</td></tr></table>
<!-- CONTENT TABLE -->

</td></tr><tr><td ALIGN="center" VALIGN="bottom"><!-- OUTER TABLE -->

<!-- CLOSE BUTTON -->
<form>
<input type=button value="Close Window" onClick='self.close()'>
</form>

</td></tr></table>
<br /><br />
<!-- OUTER TABLE-->

</BODY>
</HTML>