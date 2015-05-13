{* Maker and product diffs *}
{if $action->action_type eq 'update' && isset($action->metadata->after->name)}
  <h3>{insert name="string_diff" from_text=$action->metadata->before->name to_text=$action->metadata->after->name}</h3>
  {if isset($action->metadata->before->description)}
    <h5>{insert name="string_diff" from_text=$action->metadata->before->description to_text=$action->metadata->after->description}</h5>
  {/if}
  <h5>{insert name="string_diff" from_text=$action->metadata->before->url to_text=$action->metadata->after->url}</h5>
{/if}

{* Role diffs *}
{if $action->action_type eq 'update' && isset($action->metadata->before->role)}
  <h4>{insert name="string_diff" from_text=$action->metadata->before->role to_text=$action->metadata->after->role}</h4>
  <h5>{insert name="string_diff" from_text=$action->metadata->before->start to_text=$action->metadata->after->start}</h5>
  <h5>{insert name="string_diff" from_text=$action->metadata->before->end to_text=$action->metadata->after->end}</h5>
{/if}