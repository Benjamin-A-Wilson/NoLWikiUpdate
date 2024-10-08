<?php if (!defined('PmWiki')) exit();
##  This is a sample config.php file.  To use this file, copy it to
##  local/config.php, then edit it for whatever customizations you want.
##  Also, be sure to take a look at https://www.pmwiki.org/wiki/Cookbook
##  for more details on the customizations that can be added to PmWiki.

##  $WikiTitle is the name that appears in the browser's title bar.
$WikiTitle = 'CMU NoL Wiki';

##  $ScriptUrl is the URL for accessing wiki pages with a browser.
##  $PubDirUrl is the URL for the pub directory.
## $ScriptUrl = 'https://cmunol-wiki.com/pmwiki-2024/pmwiki.php';
## $PubDirUrl = 'https://cmunol-wiki.com/pmwiki-2024/pub';

##  If you want to use URLs of the form .../pmwiki.php/Group/PageName
##  instead of .../pmwiki.php?p=Group.PageName, try setting
##  $EnablePathInfo below.  Note that this doesn't work in all environments,
##  it depends on your webserver and PHP configuration.  You might also
##  want to check https://www.pmwiki.org/wiki/Cookbook/CleanUrls more
##  details about this setting and other ways to create nicer-looking urls.
# $EnablePathInfo = 1;

## $PageLogoUrl is the URL for a logo image -- you can change this
## to your own logo if you wish.
# $PageLogoUrl = "$PubDirUrl/skins/pmwiki/pmwiki-32.gif";

## If you want to have a custom skin, then set $Skin to the name
## of the directory (in pub/skins/) that contains your skin files.
## See PmWiki.Skins and Cookbook.Skins.
$Skin = 'modernV2';


$MaxIncludes = 1000;
$UploadMaxSize = 1000000; # limit upload file size to 1 megabyte

## You'll probably want to set an administrative password that you
## can use to get into password-protected pages.  Also, by default
## the "attr" passwords for the PmWiki and Main groups are locked, so
## an admin password is a good way to unlock those.  See PmWiki.Passwords
## and PmWiki.PasswordsAdmin.
$DefaultPasswords['admin'] = pmcrypt('NoLWiki2024horses');
$DefaultPasswords['attr'] = pmcrypt('NoLWiki2024horses');
$HandleAuth['diff'] = 'edit';

##  Enable uploads and set a site-wide default upload password.
$EnableUpload = 1;
$UploadPermAdd = 0;

if($action=="browse") {
  include_once("$FarmD/cookbook/ape.php");
}

## Unicode (UTF-8) allows the display of all languages and all alphabets.
## Highly recommended for new wikis.
include_once("scripts/xlpage-utf-8.php");

## UserAdmin 
include_once("$FarmD/cookbook/useradmin-authuser.php");

## Activating AuthUser system.
include_once("$FarmD/scripts/authuser.php");

## Markup extension
include_once("$FarmD/cookbook/markupexprplus.php");

## Add action drop-down menu
include_once("$FarmD/cookbook/actionmenu.php");

## Add toggle
include_once("$FarmD/cookbook/toggle.php");


## Add new page buttons
include_once("$FarmD/cookbook/newpageboxplus.php");

## Comment box
# include_once("$FarmD/cookbook/commentboxplus.php");

## Export Grades
include_once("$FarmD/cookbook/exportgrades.php");

## Fox
include_once("$FarmD/cookbook/fox/fox.php");
include_once("$FarmD/cookbook/foxnotify.php");
include_once("$FarmD/cookbook/foxdelete.php");

## Require Actions
#include_once("$FarmD/cookbook/restrict_actions_v1.6.php");

## Template parameters
# note that this also uses the command
include_once( 'cookbook/templates.php' );

## Gather all edits in Site.AllEdits
$RecentChangesFmt['Site.AllEdits'] = '* [[{$FullName}]] . . .'
 .'$CurrentTime $[by] $AuthorLink: [=$ChangeSummary=]';
## all edits per author
$RecentChangesFmt['Site.AllRecentChangesPerAuthor'] =
'* [[{$FullName}]] $[by] $AuthorLink  . . . $CurrentTime: [=$ChangeSummary=]';

## All edits for grading
$RecentChangesFmt['Grading.EditsList'] =
'* [[{$FullName}]] $[by] $AuthorLink  $CurrentTime: [=$ChangeSummary=]';

# add cauthor to page attributes as extra field for ?action=attr
$PageAttributes['cauthor'] = '$[Page created by:]';

# add page variable {$CreatedBy} 
$FmtPV['$CreatedBy'] = '@$page["cauthor"]';

# enable checking whether user is in a group
$Conditions['authgroup'] = '$GLOBALS["AuthList"][$condparm] > 0';

# enable checking individual users
$Conditions['authuser'] = '$GLOBALS["AuthId"]==$condparm';


# automatically set page creator to $Author for every new page
function SetPageCreator($pagename, &$page, &$new) {
	global $EnablePost, $Author, $PageCreator, $Now;
	SDV($PageCreator, $Author);
	if ($EnablePost && !$new["author"])
		$new["cauthor"] = $PageCreator; 
}
# array_unshift($EditFunctions, 'SetPageCreator');

# add (:if intext 'string':) conditional
$Conditions['intext'] = 'StringInText( $pagename, $condparm )';
function StringInText( $pn, $arg ) {
	$arg = ParseArgs($arg);
	if($arg[''][1]) $pn = MakePageName($pn, $arg[''][1]);
	$page = RetrieveAuthPage($pn, 'read', true);
	$text = preg_replace('/\\(:(.*?):\\)/' ,"", $page['text']);
	if( strpos($text, $arg[''][0])!==false ) return true;
}

# set pages to ungraded by default
  $DefaultUnsetPageTextVars = array(
    'status' => 'ungraded',
    'name' => '{=$Name}',
  );

  $DefaultEmptyPageTextVars = array(
    'status' => 'ungraded',
  );

## If you're running a publicly available site and allow anyone to
## edit without requiring a password, you probably want to put some
## blocklists in place to avoid wikispam.  See PmWiki.Blocklist.
# $EnableBlocklist = 1;                    # enable manual blocklists
# $EnableBlocklist = 10;                   # enable automatic blocklists

##  PmWiki comes with graphical user interface buttons for editing;
##  to enable these buttons, set $EnableGUIButtons to 1.
$EnableGUIButtons = 1;

##  To enable markup syntax from the Creole common wiki markup language
##  (http://www.wikicreole.org/), include it here:
# include_once("scripts/creole.php");

# require valid login before viewing pages
$DefaultPasswords['read'] = 'id:*';
# require valid login before editing pages
$DefaultPasswords['edit'] = 'id:*';



##  Some sites may want leading spaces on markup lines to indicate
##  "preformatted text blocks", set $EnableWSPre=1 if you want to do
##  this.  Setting it to a higher number increases the number of
##  space characters required on a line to count as "preformatted text".
# $EnableWSPre = 1;   # lines beginning with space are preformatted (default)
# $EnableWSPre = 4;   # lines with 4 or more spaces are preformatted
# $EnableWSPre = 0;   # disabled

##  If you want uploads enabled on your system, set $EnableUpload=1.
##  You'll also need to set a default upload password, or else set
##  passwords on individual groups and pages.  For more information
##  see PmWiki.UploadsAdmin.
$EnableUpload = 1;
$DefaultPasswords['upload'] = pmcrypt('17Tarano17');
$UploadPermAdd = 0; # Recommended for most new installations

##  Setting $EnableDiag turns on the ?action=diag and ?action=phpinfo
##  actions, which often helps others to remotely troubleshoot
##  various configuration and execution problems.
# $EnableDiag = 1;                         # enable remote diagnostics

##  By default, PmWiki doesn't allow browsers to cache pages.  Setting
##  $EnableIMSCaching=1; will re-enable browser caches in a somewhat
##  smart manner.  Note that you may want to have caching disabled while
##  adjusting configuration files or layout templates.
# $EnableIMSCaching = 1;                   # allow browser caching



##  Set $SpaceWikiWords if you want WikiWords to automatically
##  have spaces before each sequence of capital letters.
# $SpaceWikiWords = 1;                     # turn on WikiWord spacing

##  Set $EnableWikiWords if you want to allow WikiWord links.
##  For more options with WikiWords, see scripts/wikiwords.php .
# $EnableWikiWords = 1;                    # enable WikiWord links

##  $DiffKeepDays specifies the minimum number of days to keep a page's
##  revision history.  The default is 3650 (approximately 10 years).
# $DiffKeepDays=30;                        # keep page history at least 30 days

## By default, viewers are prevented from seeing the existence
## of read-protected pages in search results and page listings,
## but this can be slow as PmWiki has to check the permissions
## of each page.  Setting $EnablePageListProtect to zero will
## speed things up considerably, but it will also mean that
## viewers may learn of the existence of read-protected pages.
## (It does not enable them to access the contents of the pages.)
$EnablePageListProtect = 0;

##  The refcount.php script enables ?action=refcount, which helps to
##  find missing and orphaned pages.  See PmWiki.RefCount.
# if ($action == 'refcount') include_once("scripts/refcount.php");

##  The feeds.php script enables ?action=rss, ?action=atom, ?action=rdf,
##  and ?action=dc, for generation of syndication feeds in various formats.
# if ($action == 'rss')  include_once("scripts/feeds.php");  # RSS 2.0
# if ($action == 'atom') include_once("scripts/feeds.php");  # Atom 1.0
# if ($action == 'dc')   include_once("scripts/feeds.php");  # Dublin Core
# if ($action == 'rdf')  include_once("scripts/feeds.php");  # RSS 1.0

##  By default, pages in the Category group are manually created.
##  Uncomment the following line to have blank category pages
##  automatically created whenever a link to a non-existent
##  category page is saved.  (The page is created only if
##  the author has edit permissions to the Category group.)
$AutoCreate['/^Category\\./'] = array('ctime' => $Now);

##  PmWiki allows a great deal of flexibility for creating custom markup.
##  To add support for '*bold*' and '~italic~' markup (the single quotes
##  are part of the markup), uncomment the following lines.
##  (See PmWiki.CustomMarkup and the Cookbook for details and examples.)
# Markup("'~", "<'''''", "/'~(.*?)~'/", MakePageName("$1"));        # '~ convert to title ~'



# Markup("'*", "<'''''", "/'\\*(.*?)\\*'/", "<b>$1</b>");    # '*bold*'

##  If you want to have to approve links to external sites before they
##  are turned into links, uncomment the line below.  See PmWiki.UrlApprovals.
##  Also, setting $UnapprovedLinkCountMax limits the number of unapproved
##  links that are allowed in a page (useful to control wikispam).
# $UnapprovedLinkCountMax = 10;
# include_once("scripts/urlapprove.php");

##  The following lines make additional editing buttons appear in the
##  edit page for subheadings, lists, tables, etc.
# $GUIButtons['h2'] = array(400, '\\n!! ', '\\n', '$[Heading]',
#                     '$GUIButtonDirUrlFmt/h2.gif"$[Heading]"');
# $GUIButtons['h3'] = array(402, '\\n!!! ', '\\n', '$[Subheading]',
#                     '$GUIButtonDirUrlFmt/h3.gif"$[Subheading]"');
# $GUIButtons['indent'] = array(500, '\\n->', '\\n', '$[Indented text]',
#                     '$GUIButtonDirUrlFmt/indent.gif"$[Indented text]"');
# $GUIButtons['outdent'] = array(510, '\\n-<', '\\n', '$[Hanging indent]',
#                     '$GUIButtonDirUrlFmt/outdent.gif"$[Hanging indent]"');
# $GUIButtons['ol'] = array(520, '\\n# ', '\\n', '$[Ordered list]',
#                     '$GUIButtonDirUrlFmt/ol.gif"$[Ordered (numbered) list]"');
# $GUIButtons['ul'] = array(530, '\\n* ', '\\n', '$[Unordered list]',
#                     '$GUIButtonDirUrlFmt/ul.gif"$[Unordered (bullet) list]"');
# $GUIButtons['hr'] = array(540, '\\n----\\n', '', '',
#                     '$GUIButtonDirUrlFmt/hr.gif"$[Horizontal rule]"');
# $GUIButtons['table'] = array(600,
#                       '||border=1 width=80%\\n||!Hdr ||!Hdr ||!Hdr ||\\n||     ||     ||     ||\\n||     ||     ||     ||\\n', '', '',
#                     '$GUIButtonDirUrlFmt/table.gif"$[Table]"');

# stripping sidebar, title and footer when not logged in
/*
if (!$AuthId && !admin) {
	$HTMLStylesFmt[] = "
	  footer nav, #breadcrumb, nav.primary, #wikilogo, #wikihead, #wikileft, #wikititle, #wikifoot {display:none}\n";
  };
*/