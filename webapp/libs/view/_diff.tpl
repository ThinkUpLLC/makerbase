{* Maker and product diffs *}
{if $action->action_type eq 'update' && isset($action->metadata->after->name)}
  {if $action->metadata->before->name neq $action->metadata->after->name}
  	<p>{insert name="string_diff" from_text=$action->metadata->before->name to_text=$action->metadata->after->name}</p>
  {/if}
  {if isset($action->metadata->before->description)}
  	{if $action->metadata->before->description neq $action->metadata->after->description}
	    <p>{insert name="string_diff" from_text=$action->metadata->before->description to_text=$action->metadata->after->description}</p>
    {/if}
  {/if}
  {if $action->metadata->before->url neq $action->metadata->after->url}
  <p>{insert name="string_diff" from_text=$action->metadata->before->url to_text=$action->metadata->after->url}</p>
  {/if}
{/if}

{* Role diffs *}
{if $action->action_type eq 'update' && isset($action->metadata->before->role)}
	{if $action->metadata->before->role neq $action->metadata->after->role}
	  <p>{insert name="string_diff" from_text=$action->metadata->before->role to_text=$action->metadata->after->role}</p>
	{/if}
	{if $action->metadata->before->start neq $action->metadata->after->start}
	  <p>{insert name="string_diff" from_text=$action->metadata->before->start to_text=$action->metadata->after->start}</p>
  {/if}
	{if $action->metadata->before->end neq $action->metadata->after->end}
	  <p>{insert name="string_diff" from_text=$action->metadata->before->end to_text=$action->metadata->after->end}</p>
  {/if}
{/if}