<?php
/**
 * Invoice Transaction Gateway with Modular Plugin set
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Co-Op http://www.chronolabs.coop/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         xpayment
 * @since           1.30.0
 * @author          Simon Roberts <simon@chronolabs.coop>
 * @translation     Erol Konik <aphex@aphexthemes.com>
 * @translation     Mariane <mariane_antoun@hotmail.com>
 * @translation     Voltan <voltan@xoops.ir>
 * @translation     Ezsky <ezskyyoung@gmail.com>
 * @translation     Richardo Costa <lusopoemas@gmail.com>
 * @translation     Kris_fr <kris@frxoops.org>
 */
	
require_once __DIR__ . '/../../../include/cp_header.php';

if (!defined('_CHARSET')) {
    define('_CHARSET', 'UTF-8');
}
if (!defined('_CHARSET_ISO')) {
    define('_CHARSET_ISO', 'ISO-8859-1');
}
		
	$GLOBALS['myts'] = MyTextSanitizer::getInstance();
	
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler                   = xoops_getHandler('module');
$configHandler                   = xoops_getHandler('config');
$GLOBALS['xpaymentModule']       = $moduleHandler->getByDirname('xpayment');
$GLOBALS['xpaymentModuleConfig'] = $configHandler->getConfigList($GLOBALS['xpaymentModule']->getVar('mid'));
		
	xoops_load('pagenav');	
	xoops_load('xoopslists');
	xoops_load('xoopsformloader');
	
require_once $GLOBALS['xoops']->path('class/xoopsmailer.php');
require_once $GLOBALS['xoops']->path('class/xoopstree.php');
	
if (file_exists($GLOBALS['xoops']->path('Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))) {
    require_once $GLOBALS['xoops']->path('Frameworks/moduleclasses/moduleadmin/moduleadmin.php');
	        //return true;
	    }else{
	        echo xoops_error("Error: You don't use the Frameworks \"admin module\". Please install this Frameworks");
	        //return false;
	    }
	$GLOBALS['xpaymentImageIcon'] = XOOPS_URL .'/'. $GLOBALS['xpaymentModule']->getInfo('icons16');
	$GLOBALS['xpaymentImageAdmin'] = XOOPS_URL .'/'. $GLOBALS['xpaymentModule']->getInfo('icons32');
	
	if ($GLOBALS['xoopsUser']) {
    $modulepermHandler = xoops_getHandler('groupperm');
    if (!$modulepermHandler->checkRight('module_admin', $GLOBALS['xpaymentModule']->getVar('mid'), $GLOBALS['xoopsUser']->getGroups())) {
	        redirect_header(XOOPS_URL, 1, _NOPERM);
	    }
	} else {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
	}
	
	if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
    require_once XOOPS_ROOT_PATH . '/class/template.php';
		$GLOBALS['xoopsTpl'] = new XoopsTpl();
	}
	
	$GLOBALS['xoopsTpl']->assign('pathImageIcon', $GLOBALS['xpaymentImageIcon']);
	
require_once $GLOBALS['xoops']->path('modules/xpayment/include/xpayment.functions.php');
require_once $GLOBALS['xoops']->path('modules/xpayment/include/xpayment.objects.php');
require_once $GLOBALS['xoops']->path('modules/xpayment/include/xpayment.forms.php');

//  xoops_load('xoopsmailer');
	/*
	IF (!@ include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php"):    
	function loadModuleAdminMenu($currentoption, $breadcrumb = "")
	{
		if (!$adminmenu = $GLOBALS['xpaymentModule']->getAdminMenu()) {
			return false;
		}
			
		$breadcrumb = empty($breadcrumb) ? $adminmenu[$currentoption]["title"] : $breadcrumb;
		$module_link = XOOPS_URL."/modules/".$GLOBALS['xpaymentModule']->getVar("dirname")."/";
		$image_link = XOOPS_URL."/modules/".$GLOBALS['xpaymentModule']->getVar("dirname")."/images";
		
		$adminmenu_text ='
		<style type="text/css">
		<!--
		#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0;}
		#buttonbar { float:left; width:100%; background: #e7e7e7 url("'.$image_link.'/modadminbg.gif") repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px;}
		#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
		#buttonbar li { display:inline; margin:0; padding:0; }
		#buttonbar a { float:left; background:url("'.$image_link.'/left_both.gif") no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
		#buttonbar a span { float:left; display:block; background:url("'.$image_link.'/right_both.gif") no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
//		 Commented Backslash Hack hides rule from IE5-Mac \
		#buttonbar a span {float:none;}
//		 End IE5-Mac hack
		#buttonbar a:hover span { color:#333; }
		#buttonbar .current a { background-position:0 -150px; border-width:0; }
		#buttonbar .current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
		#buttonbar a:hover { background-position:0% -150px; }
		#buttonbar a:hover span { background-position:100% -150px; }    
		//-->
		</style>
		<div id="buttontop">
		 <table style="width: 100%; padding: 0; " cellspacing="0">
			 <tr>
				 <td style="width: 70%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;">
					 <a href="../index.php">'.$GLOBALS['xpaymentModule']->getVar("name").'</a>
				 </td>
				 <td style="width: 30%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;">
					 <strong>'.$GLOBALS['xpaymentModule']->getVar("name").'</strong>&nbsp;'.$breadcrumb.'
				 </td>
			 </tr>
		 </table>
		</div>
		<div id="buttonbar">
		 <ul>
		';
		foreach (array_keys($adminmenu) as $key) {
			$adminmenu_text .= (($currentoption == $key) ? '<li class="current">' : '<li>').'<a href="'.$module_link.$adminmenu[$key]["link"].'"><span>'.$adminmenu[$key]["title"].'</span></a></li>';
		}
		$adminmenu_text .= '<li><a href="'.XOOPS_URL.'/modules/system/admin.php?fct=preferences&op=showmod&mod='.$GLOBALS['xpaymentModule']->getVar("mid").'"><span>'._PREFERENCES.'</span></a></li>';
		$adminmenu_text .= '
		 </ul>
		</div>
    <br style="clear:both;">';
		
		echo $adminmenu_text;
	}
		
	ENDIF;
*/
