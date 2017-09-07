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

use Xmf\Module\Admin;
use Xmf\Module\Helper;

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Helper::getHelper('system');
}
//

$path = dirname(dirname(dirname(__DIR__)));
require_once $path . '/mainfile.php';

$dirname = basename(dirname(__DIR__));
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler   = xoops_getHandler('module');
$module          = $moduleHandler->getByDirname($dirname);
$pathModuleAdmin = $module->getInfo('dirmoduleadmin2');
$pathLanguage    = $path . $pathModuleAdmin;

//if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
//    $fileinc = $pathLanguage . '/language/english/main.php';
//}
//require_once $fileinc;

/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler             = xoops_getHandler('module');
$GLOBALS['xpaymentModule'] = XoopsModule::getByDirname('xpayment');
$moduleInfo                = $moduleHandler->get($GLOBALS['xpaymentModule']->getVar('mid'));
$pathIcon32                = Admin::menuIconPath('');
$pathModIcon32             = $moduleHelper->getModule()->getInfo('modicons32');

$adminmenu = [];
$i         = 0;
//$adminmenu[$i]["title"] = _XPY_ADMENU0;
//$adminmenu[$i]['link'] = "admin/index.php";
//$adminmenu[$i]["icon"]  = $pathIcon322 . '/home.png';
//++$i;
$adminmenu[$i]['title'] = _XPY_ADMENU8;
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
$adminmenu[$i]['image'] = $pathIcon32 . '/home.png';
$adminmenu[$i]['link']  = 'admin/main.php?op=dashboard';
++$i;
$adminmenu[$i]['title'] = _XPY_ADMENU1;
$adminmenu[$i]['icon']  = $pathModIcon32 . '/xpayment.invoices.png';
$adminmenu[$i]['image'] = $pathModIcon32 . '/xpayment.invoices.png';
$adminmenu[$i]['link']  = 'admin/main.php?op=invoices&fct=list';
++$i;
$adminmenu[$i]['title'] = _XPY_ADMENU2;
$adminmenu[$i]['icon']  = $pathModIcon32 . '/xpayment.transactions.png';
$adminmenu[$i]['image'] = $pathModIcon32 . '/xpayment.transactions.png';
$adminmenu[$i]['link']  = 'admin/main.php?op=transactions&fct=list';
++$i;
$adminmenu[$i]['title'] = _XPY_ADMENU3;
$adminmenu[$i]['icon']  = $pathModIcon32 . '/xpayment.gateways.png';
$adminmenu[$i]['image'] = $pathModIcon32 . '/xpayment.gateways.png';
$adminmenu[$i]['link']  = 'admin/main.php?op=gateways&fct=list';
++$i;
$adminmenu[$i]['title'] = _XPY_ADMENU4;
$adminmenu[$i]['icon']  = $pathModIcon32 . '/xpayment.permissions.png';
$adminmenu[$i]['image'] = $pathModIcon32 . '/xpayment.permissions.png';
$adminmenu[$i]['link']  = 'admin/main.php?op=permissions&fct=email';
++$i;
$adminmenu[$i]['title'] = _XPY_ADMENU5;
$adminmenu[$i]['icon']  = $pathModIcon32 . '/xpayment.groups.png';
$adminmenu[$i]['image'] = $pathModIcon32 . '/xpayment.groups.png';
$adminmenu[$i]['link']  = 'admin/main.php?op=groups&fct=brokers';
++$i;
$adminmenu[$i]['title'] = _XPY_ADMENU6;
$adminmenu[$i]['icon']  = $pathModIcon32 . '/xpayment.taxes.png';
$adminmenu[$i]['image'] = $pathModIcon32 . '/xpayment.taxes.png';
$adminmenu[$i]['link']  = 'admin/main.php?op=tax&fct=list';
++$i;
$adminmenu[$i]['title'] = _XPY_ADMENU7;
$adminmenu[$i]['icon']  = $pathModIcon32 . '/xpayment.discounts.png';
$adminmenu[$i]['image'] = $pathModIcon32 . '/xpayment.discounts.png';
$adminmenu[$i]['link']  = 'admin/main.php?op=discounts&fct=list';
++$i;
$adminmenu[$i]['title'] = _XPY_ADMENU9;
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
$adminmenu[$i]['image'] = $pathIcon32 . '/about.png';
$adminmenu[$i]['link']  = 'admin/main.php?op=about';

//++$i;
//$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
//$adminmenu[$i]["link"]  = "admin/about.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';
