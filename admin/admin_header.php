<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

require_once __DIR__ . '/../../../include/cp_header.php';
//require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');

require_once __DIR__ . '/../class/utility.php';
//require_once __DIR__ . '/../include/common.php';

if (!defined('_CHARSET')) {
    define('_CHARSET', 'UTF-8');
}
if (!defined('_CHARSET_ISO')) {
    define('_CHARSET_ISO', 'ISO-8859-1');
}

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

// Load language files
$moduleHelper->loadLanguage('admin');
$moduleHelper->loadLanguage('modinfo');
$moduleHelper->loadLanguage('main');

$myts = MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
//    $GLOBALS['xoopsTpl'] = new XoopsTpl();
    $xoopsTpl = new XoopsTpl();
}

//---------------------------------------------------

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
/*
if (file_exists($GLOBALS['xoops']->path('Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))) {
    require_once $GLOBALS['xoops']->path('Frameworks/moduleclasses/moduleadmin/moduleadmin.php');
    //return true;
} else {
    echo xoops_error("Error: You don't use the Frameworks \"admin module\". Please install this Frameworks");
    //return false;
}
*/

$GLOBALS['xpaymentImageIcon']  = XOOPS_URL . '/' . $GLOBALS['xpaymentModule']->getInfo('icons16');
$GLOBALS['xpaymentImageAdmin'] = XOOPS_URL . '/' . $GLOBALS['xpaymentModule']->getInfo('icons32');

if ($GLOBALS['xoopsUser']) {
    $modulepermHandler = xoops_getHandler('groupperm');
    if (!$modulepermHandler->checkRight('module_admin', $GLOBALS['xpaymentModule']->getVar('mid'), $GLOBALS['xoopsUser']->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}

$GLOBALS['xoopsTpl']->assign('pathImageIcon', $GLOBALS['xpaymentImageIcon']);

require_once __DIR__ . '/../include/xpayment.functions.php';
require_once __DIR__ . '/../include/xpayment.objects.php';
require_once __DIR__ . '/../include/xpayment.forms.php';
