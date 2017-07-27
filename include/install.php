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
 * @param XoopsModule $module
 * @return bool
 */
function xoops_module_pre_install_xpayment(XoopsModule $module)
{
    xoops_loadLanguage('modinfo', 'xpayment');

    $groupsHandler = xoops_getHandler('group');
    $criteria      = new Criteria('group_type', _XPY_MI_GROUP_TYPE_BROKER);
    if (count($groupsHandler->getObjects($criteria)) == 0) {
        $group = $groupsHandler->create();
        $group->setVar('name', _XPY_MI_GROUP_NAME_BROKER);
        $group->setVar('description', _XPY_MI_GROUP_DESC_BROKER);
        $group->setVar('group_type', _XPY_MI_GROUP_TYPE_BROKER);
        $groupsHandler->insert($group, true);
    }

    $groupsHandler = xoops_getHandler('group');
    $criteria      = new Criteria('group_type', _XPY_MI_GROUP_TYPE_ACCOUNTS);
    if (count($groupsHandler->getObjects($criteria)) == 0) {
        $group = $groupsHandler->create();
        $group->setVar('name', _XPY_MI_GROUP_NAME_ACCOUNTS);
        $group->setVar('description', _XPY_MI_GROUP_DESC_ACCOUNTS);
        $group->setVar('group_type', _XPY_MI_GROUP_TYPE_ACCOUNTS);
        $groupsHandler->insert($group, true);
    }

    $groupsHandler = xoops_getHandler('group');
    $criteria      = new Criteria('group_type', _XPY_MI_GROUP_TYPE_OFFICER);
    if (count($groupsHandler->getObjects($criteria)) == 0) {
        $group = $groupsHandler->create();
        $group->setVar('name', _XPY_MI_GROUP_NAME_OFFICER);
        $group->setVar('description', _XPY_MI_GROUP_DESC_OFFICER);
        $group->setVar('group_type', _XPY_MI_GROUP_TYPE_OFFICER);
        $groupsHandler->insert($group, true);
    }

    return true;
}

function xoops_module_install_xpayment(XoopsModule $module)
{
    require_once $GLOBALS['xoops']->path('modules/xpayment/include/xpayment.functions.php');

    return xpayment_install_gateway('paypal');
}
