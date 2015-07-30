{*
Report a project or maker or user.

$object_type 'project' or 'maker' or 'user'
$object either the Product or Maker or User
*}

{capture name="report_subject" assign="report_subject"}Report {$object_type}: {$object->name}{/capture}

{capture name="report_body" assign="report_body"}
Hi! I'd like to report a {$object_type} on Makerbase:

https://makerba.se/{if $object_type eq 'project'}p{elseif $object_type eq 'maker'}m{elseif $object_type eq 'user'}u{/if}/{$object->uid}{if isset($object->slug)}/{$object->slug}{/if}

Here's why this {$object_type} should be reviewed:


Thanks!

{if isset($logged_in_user)}
Reported by {$logged_in_user->name}
https://makerba.se/u/{$logged_in_user->uid}
{/if}
{/capture}

<div class="row" id="report-this">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">

  <a type="button" class="btn btn-default btn-link pull-right" href="mailto:team@makerba.se?subject={$report_subject|escape:'url'}&body={$report_body|escape:'url'}"><i class="fa fa-flag text-muted"></i><span class="hidden-xs hidden-sm text-muted"> Report this {$object_type}</span></a>

  </div>
</div>
