<?php if (!defined('PmWiki')) exit();
/* PmWiki Enlighten skin
 *
 * Examples at: http://pmwiki.com/Cookbook/Enlighten and http://solidgone.org/Skins/
 * Copyright (c) 2009 David Gilbert
 * This work is licensed under a Creative Commons Attribution-Share Alike 3.0 United States License.
 * Please retain the links in the footer.
 * http://creativecommons.org/licenses/by-sa/3.0/us/
 */
global $FmtPV;
$FmtPV['$SkinName'] = '"Enlighten"';
$FmtPV['$SkinVersion'] = '"1.0.0"';

global $PageLogoUrl, $PageLogoUrlHeight, $PageLogoUrlWidth, $HTMLStylesFmt ,$SkinTheme;
if (!empty($PageLogoUrl)) {
	dg_SetLogoHeightWidth(15, 16);
	$HTMLStylesFmt['enlighten'] .=
		'#XXlogo .sitetitle a{height:' .$PageLogoUrlHeight .'; background: url(' .$PageLogoUrl .') left 16px no-repeat} '.
		'#XXlogo .sitetitle a, #XXlogo .sitetag{padding-left: ' .$PageLogoUrlWidth .'} ';
}
global $SkinWidth,$SkinSidebarWidth,$SkinWidthUnit;
SDV($SkinWidth,850);
SDV($SkinSidebarWidth,195);  #good percentage width is 25
SDV($SkinWidthUnit,'px');  #only use 'px' or '%'
$HTMLStylesFmt['enlighten'] .=	'#XXheader, #XXpage, #XXfooter-inner { width: '.$SkinWidth.$SkinWidthUnit.'; } '.
	'#XXcontent { width: '.($SkinWidthUnit=='px'?($SkinWidth-$SkinSidebarWidth-55) :(100-$SkinSidebarWidth-5)) .$SkinWidthUnit.'; } '.
	'#XXsidebar { width: '.$SkinSidebarWidth .$SkinWidthUnit.'; } ';

$SkinColor = dg_SetSkinColor('brown', array());

# ----------------------------------------
# - Standard Skin Setup
# ----------------------------------------
$FmtPV['$WikiTitle'] = '$GLOBALS["WikiTitle"]';
$FmtPV['$WikiTag'] = '$GLOBALS["WikiTag"]';

# Move any (:noleft:) or SetTmplDisplay('PageLeftFmt', 0); directives to variables for access in jScript.
$FmtPV['$LeftColumn'] = "\$GLOBALS['TmplDisplay']['PageLeftFmt']";
Markup('noleft', 'directives',  '/\\(:noleft:\\)/ei', "SetTmplDisplay('PageLeftFmt',0)");
$FmtPV['$RightColumn'] = "\$GLOBALS['TmplDisplay']['PageRightFmt']";
Markup('noright', 'directives',  '/\\(:noright:\\)/ei', "SetTmplDisplay('PageRightFmt',0)");
$FmtPV['$ActionBar'] = "\$GLOBALS['TmplDisplay']['PageActionFmt']";
Markup('noaction', 'directives',  '/\\(:noaction:\\)/ei', "SetTmplDisplay('PageActionFmt',0)");
$FmtPV['$TabsBar'] = "\$GLOBALS['TmplDisplay']['PageTabsFmt']";
Markup('notabs', 'directives',  '/\\(:notabs:\\)/ei', "SetTmplDisplay('PageTabsFmt',0)");
$FmtPV['$SearchBar'] = "\$GLOBALS['TmplDisplay']['PageSearchFmt']";
Markup('nosearch', 'directives',  '/\\(:nosearch:\\)/ei', "SetTmplDisplay('PageSearchFmt',0)");
$FmtPV['$TitleGroup'] = "\$GLOBALS['TmplDisplay']['PageTitleGroupFmt']";
Markup('notitlegroup', 'directives',  '/\\(:notitlegroup:\\)/ei', "SetTmplDisplay('PageTitleGroupFmt',0)");
Markup('notitle', 'directives',  '/\\(:notitle:\\)/ei', "SetTmplDisplay('PageTitleFmt',0); SetTmplDisplay('PageTitleGroupFmt',0)");
Markup('fieldset', 'inline', '/\\(:fieldset:\\)/i', "<fieldset>");
Markup('fieldsetend', 'inline', '/\\(:fieldsetend:\\)/i', "</fieldset>");

# Override pmwiki styles otherwise they will override styles declared in css
global $HTMLStylesFmt;
$HTMLStylesFmt['pmwiki'] = '';

# Add a custom page storage location
global $WikiLibDirs;
$PageStorePath = dirname(__FILE__)."/wikilib.d/{\$FullName}";
$where = count($WikiLibDirs);
if ($where>1) $where--;
array_splice($WikiLibDirs, $where, 0, array(new PageStore($PageStorePath)));

# ----------------------------------------
# - Standard Skin Functions
# ----------------------------------------
function dg_SetSkinColor($default, $valid_colors){
global $SkinColor, $ValidSkinColors, $_GET;
	if ( !is_array($ValidSkinColors) ) $ValidSkinColors = array();
	$ValidSkinColors = array_merge($ValidSkinColors, $valid_colors);
	if ( isset($_GET['color']) && in_array($_GET['color'], $ValidSkinColors) )
		$SkinColor = $_GET['color'];
	elseif ( !in_array($SkinColor, $ValidSkinColors) )
		$SkinColor = $default;
	return $SkinColor;
}
function dg_PoweredBy(){
	print ('<a href="http://pmwiki.com/'.($GLOBALS['bi_BlogIt_Enabled']?'Cookbook/BlogIt">BlogIt':'">PmWiki').'</a>');
}
# Determine logo height and width
function dg_SetLogoHeightWidth ($wPad, $hPad=0){
global $PageLogoUrl, $PageLogoUrlHeight, $PageLogoUrlWidth;
	if (!isset($PageLogoUrlWidth) || !isset($PageLogoUrlHeight)){
		$size = @getimagesize($PageLogoUrl);
		if (!isset($PageLogoUrlWidth))  SDV($PageLogoUrlWidth, ($size ?$size[0]+$wPad :0) .'px');
		if (!isset($PageLogoUrlHeight))  SDV($PageLogoUrlHeight, ($size ?$size[1]+$hPad :0) .'px');
	}
}