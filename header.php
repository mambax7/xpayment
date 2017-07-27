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
include __DIR__ . '/../../mainfile.php';

require_once $GLOBALS['xoops']->path('modules/xpayment/include/xpayment.functions.php');
require_once $GLOBALS['xoops']->path('modules/xpayment/include/xpayment.objects.php');
require_once $GLOBALS['xoops']->path('modules/xpayment/include/xpayment.forms.php');

xoops_load('pagenav');
//  xoops_load('xoopsmailer');

$GLOBALS['myts'] = MyTextSanitizer::getInstance();

/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler                   = xoops_getHandler('module');
$configHandler                   = xoops_getHandler('config');
$GLOBALS['xpaymentModule']       = $moduleHandler->getByDirname('xpayment');
$GLOBALS['xpaymentModuleConfig'] = $configHandler->getConfigList($GLOBALS['xpaymentModule']->getVar('mid'));

require_once $GLOBALS['xoops']->path('/class/template.php');
$GLOBALS['xoopsTpl'] = new XoopsTpl();
