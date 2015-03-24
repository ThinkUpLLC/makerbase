{* Maker and product diffs *}
{if $action->action_type eq 'update' && isset($action->metadata->after->name)}
  <p>{insert name="string_diff" from_text=$action->metadata->before->name to_text=$action->metadata->after->name}</p>
  {if isset($action->metadata->before->description)}
    <p>{insert name="string_diff" from_text=$action->metadata->before->description to_text=$action->metadata->after->description}</p>
  {/if}
  <p>{insert name="string_diff" from_text=$action->metadata->before->url to_text=$action->metadata->after->url}</p>
{/if}

{* Role diffs *}
{if $action->action_type eq 'update' && isset($action->metadata->before->role)}
  <p>{insert name="string_diff" from_text=$action->metadata->before->role to_text=$action->metadata->after->role}</p>
  <p>{insert name="string_diff" from_text=$action->metadata->before->start to_text=$action->metadata->after->start}</p>
  <p>{insert name="string_diff" from_text=$action->metadata->before->end to_text=$action->metadata->after->end}</p>
{/if}