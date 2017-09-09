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

//$adminmenu[] = [
//$adminmenu[$i]["title"] = _XPY_ADMENU0;
//'link' =>  "admin/index.php",
//$adminmenu[$i]["icon"]  = $pathIcon322 . '/home.png';
//];

$adminmenu[] = [
    'title' => _XPY_ADMENU8,
    'icon'  => $pathIcon32 . '/home.png',
    'image' => $pathIcon32 . '/home.png',
    'link'  => 'admin/main.php?op=dashboard',
];

$adminmenu[] = [
    'title' => _XPY_ADMENU1,
    'icon'  => $pathModIcon32 . '/xpayment.invoices.png',
    'image' => $pathModIcon32 . '/xpayment.invoices.png',
    'link'  => 'admin/main.php?op=invoices&fct=list',
];

$adminmenu[] = [
    'title' => _XPY_ADMENU2,
    'icon'  => $pathModIcon32 . '/xpayment.transactions.png',
    'image' => $pathModIcon32 . '/xpayment.transactions.png',
    'link'  => 'admin/main.php?op=transactions&fct=list',
];

$adminmenu[] = [
    'title' => _XPY_ADMENU3,
    'icon'  => $pathModIcon32 . '/xpayment.gateways.png',
    'image' => $pathModIcon32 . '/xpayment.gateways.png',
    'link'  => 'admin/main.php?op=gateways&fct=list',
];

$adminmenu[] = [
    'title' => _XPY_ADMENU4,
    'icon'  => $pathModIcon32 . '/xpayment.permissions.png',
    'image' => $pathModIcon32 . '/xpayment.permissions.png',
    'link'  => 'admin/main.php?op=permissions&fct=email',
];

$adminmenu[] = [
    'title' => _XPY_ADMENU5,
    'icon'  => $pathModIcon32 . '/xpayment.groups.png',
    'image' => $pathModIcon32 . '/xpayment.groups.png',
    'link'  => 'admin/main.php?op=groups&fct=brokers',
];

$adminmenu[] = [
    'title' => _XPY_ADMENU6,
    'icon'  => $pathModIcon32 . '/xpayment.taxes.png',
    'image' => $pathModIcon32 . '/xpayment.taxes.png',
    'link'  => 'admin/main.php?op=tax&fct=list',
];

$adminmenu[] = [
    'title' => _XPY_ADMENU7,
    'icon'  => $pathModIcon32 . '/xpayment.discounts.png',
    'image' => $pathModIcon32 . '/xpayment.discounts.png',
    'link'  => 'admin/main.php?op=discounts&fct=list',
];

$adminmenu[] = [
    'title' => _XPY_ADMENU9,
    'icon'  => $pathIcon32 . '/about.png',
    'image' => $pathIcon32 . '/about.png',
    'link'  => 'admin/main.php?op=about',
];

//$adminmenu[] = [
//'title' =>  _AM_MODULEADMIN_ABOUT,
//$adminmenu[$i]["link"]  = "admin/about.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';
//];

