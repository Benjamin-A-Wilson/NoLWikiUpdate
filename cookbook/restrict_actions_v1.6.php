<?php if (!defined('PmWiki')) exit();
/**
 *  restrict_actions: deny most actions even for admins (if not defined otherwise).
 *  I tried to manage allowed actions with $HandleAuth, but could not disable "?action=search".
 *
 *  If you're using AuthUser, be sure to include restrict_actions afterwards!
 *
 *  special settings: "*" = use default rules, "-" = nobody may do this
 **/


$pagename = ResolvePageName($pagename);
  # use default pagename if no pagename given (otherwise access is blocked in this case)


SDVA($RequiredPermissionLevels, array(
  'browse'     => 'read',  # normal [action] -> [privilege] rules.
    'print'    => 'read',
  'edit'       => '*',
    'diff'     => 'edit',
    'source'   => 'edit',
  'attr'       => '*',
    'postattr' => '*',
  'upload'     => '*',
  'login'      => '*',
    'logout'   => '*',
  'system'     => 'admin',  # rule for system pages, as defined below in $system_pagenames
  ));

$RequiredPermissionLevel = '-';
if (isset($RequiredPermissionLevels[$action]))
  { $RequiredPermissionLevel = $RequiredPermissionLevels[$action]; }

/**
 * protect system pages against direct user access.
 * if your skin allows additional per-group sidebars, you may want to add their pagename.
 **/

$system_pagenames = array('GroupHeader', 'GroupFooter', 'GroupAttributes', 'PageNotFound',
  'RecentChanges', 'SideBar', 'PageActions');

list (, $only_pagename) = explode('.', $pagename);

if (in_array($only_pagename, $system_pagenames)
  && ($RequiredPermissionLevels['system'] != '*')
  && ($action !== 'logout')
  && ($action !== 'login')
) { $RequiredPermissionLevel = $RequiredPermissionLevels['system']; }

/**
 * now that we know which permission level is required, let's check if the user has it:
 **/

$FmtPV['$RequestedAction'] = '"' . preg_replace("!\W!", '', $action) . '"';
switch($RequiredPermissionLevel)
{
  case '*': break;
  case '-': $action = 'login';
  default: if (!CondAuth($pagename, $RequiredPermissionLevel)) { $action = 'login'; }
}