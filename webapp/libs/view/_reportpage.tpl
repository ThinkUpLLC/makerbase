{*
Report a project or maker.

$object_type 'project' or 'maker'
$object either the project or Maker
*}

{capture name="report_subject" assign="report_subject"}Report {$object_type}: {$object->name}{/capture}

{capture name="report_body" assign="report_body"}
Hi! I'd like to report a {$object_type} on Makerbase:

http://makerba.se/{if $object_type eq 'project'}p{else}m{/if}/{$object->uid}/{$object->slug}

Here's why this {$object_type} should be reviewed:


Thanks!

{if isset($logged_in_user)}
Reported by {$logged_in_user->name}
http://makerba.se/u/{$logged_in_user->uid}
{/if}
{/capture}


  <a type="button" class="btn btn-default btn-link pull-right" href="mailto:help@thinkup.com?subject={$report_subject|escape:'url'}&body={$report_body|escape:'url'}"><i class="fa fa-flag text-muted"></i><span class="hidden-xs hidden-sm text-muted"> Report this {$object_type}</span></a>

