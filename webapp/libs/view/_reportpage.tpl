{*
Report a product or maker.

$object_type 'product' or 'maker'
$object either the Product or Maker
*}

{capture name="report_subject" assign="report_subject"}Report {$object_type}: {$object->name}{/capture}

{capture name="report_body" assign="report_body"}
Hi! I'd like to report a page on Makerbase:

http://makerba.se/{if $object_type eq 'product'}p{else}m{/if}/{$object->uid}/{$object->slug}

Here's why this page should be reviewed:


Thanks!

{if isset($logged_in_user)}
Reporting user:
{$logged_in_user->name}
http://makerba.se/u/{$logged_in_user->uid}
{/if}
{/capture}


  <a type="button" class="btn btn-default btn-link pull-right" href="mailto:help@thinkup.com?subject={$report_subject|escape:'url'}&body={$report_body|escape:'url'}"><i class="fa fa-flag text-muted"></i><span class="hidden-xs hidden-sm text-muted"> Report this page</span></a>

