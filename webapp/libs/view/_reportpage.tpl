{*
Report a project or maker or user.

$object_type 'project' or 'maker' or 'user'
$object either the Product or Maker or User
*}

{capture name="report_subject" assign="report_subject"}Take a look at the {$object_type} "{$object->name|escape}"{/capture}
{capture name="report_body" assign="report_body"}
Hi! I'd like to report a {$object_type} on Makerbase:

https://makerba.se/{if $object_type eq 'project'}p{elseif $object_type eq 'maker'}m{elseif $object_type eq 'user'}u{/if}/{$object->uid}{if isset($object->slug)}/{$object->slug}{/if}

Here's why this {$object_type} should be reviewed:
{/capture}



{capture name="report_subject_featured" assign="report_subject_featured"}Hey, make {$object->name|escape} a featured {$object_type}!{/capture}
{capture name="report_body_featured" assign="report_body_featured"}
Hi! You should make {$object->name|escape} a featured {$object_type} on Makerbase.

https://makerba.se/{if $object_type eq 'project'}p{elseif $object_type eq 'maker'}m{elseif $object_type eq 'user'}u{/if}/{$object->uid}{if isset($object->slug)}/{$object->slug}{/if}


Here's why {$object->name|escape} is awesome:
{/capture}

{capture name="report_subject_duplicate" assign="report_subject_duplicate"}I think {$object->name|escape} might be a duplicate?{/capture}
{capture name="report_body_duplicate" assign="report_body_duplicate"}
Hi, I think this {$object->name|escape} might be a duplicate {$object_type}.

https://makerba.se/{if $object_type eq 'project'}p{elseif $object_type eq 'maker'}m{elseif $object_type eq 'user'}u{/if}/{$object->uid}{if isset($object->slug)}/{$object->slug}{/if}


Here's the other {$object_type} that it's a copy of:
{/capture}

{capture name="report_subject_vandalized" assign="report_subject_vandalized"}Please review {$object->name|escape}{/capture}
{capture name="report_body_vandalized" assign="report_body_vandalized"}
I think there's something wrong with the Makerbase {$object_type} page for {$object->name|escape}.

https://makerba.se/{if $object_type eq 'project'}p{elseif $object_type eq 'maker'}m{elseif $object_type eq 'user'}u{/if}/{$object->uid}{if isset($object->slug)}/{$object->slug}{/if}


Here's what seems amiss:
{/capture}

{capture name="report_subject_mine" assign="report_subject_mine"}Special request about the {$object_type} '{$object->name|escape}'{/capture}
{capture name="report_body_mine" assign="report_body_mine"}
Hello, I have a personal request about the Makerbase {$object_type} "{$object->name|escape}".

https://makerba.se/{if $object_type eq 'project'}p{elseif $object_type eq 'maker'}m{elseif $object_type eq 'user'}u{/if}/{$object->uid}{if isset($object->slug)}/{$object->slug}{/if}


Here are the details:
{/capture}


{capture name="report_footer" assign="report_footer"}




Thanks!

{if isset($logged_in_user)}
Reported by {$logged_in_user->name}
https://makerba.se/u/{$logged_in_user->uid}
{/if}
{/capture}

<button type="button" class="btn btn-default btn-link flag" data-toggle="modal" data-target="#flagform" data-backdrop="static">
  <i class="fa fa-flag text-muted"></i><span class="hidden-xs hidden-sm text-muted"> Flag this {$object_type}</span>
</button>

    <!-- Flag form -->
    <div class="modal" id="flagform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Flag <strong>{$object->name|escape}</strong></h4>
          </div>
          <div class="modal-body">

            <div class="radio">
              <label>
                <input type="radio" name="flag-form-option" id="flag-featured" value="mailto:team@makerba.se?subject={$report_subject_featured|escape:'url'}&amp;body={$report_body_featured|escape:'url'}{$report_footer|escape:'url'}" checked>
                {$object->name|escape} is great and should be a featured {if $object_type eq 'user'}contributor{else}{$object_type}{/if}.
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="flag-form-option" id="flag-duplicate" value="mailto:team@makerba.se?subject={$report_subject_duplicate|escape:'url'}&amp;body={$report_body_duplicate|escape:'url'}{$report_footer|escape:'url'}">
                {$object->name|escape} is a duplicate.{if $object_type neq 'user'} (Hint: Click on "Edit" and press the "Archive" button to remove a duplicate.){/if}
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="flag-form-option" id="flag-vandalized" value="mailto:team@makerba.se?subject={$report_subject_vandalized|escape:'url'}&amp;body={$report_body_vandalized|escape:'url'}{$report_footer|escape:'url'}">
                {if $object_type eq 'maker' || $object_type eq 'project'}
                {$object->name|escape} is being vandalized or maliciously edited.
                {else}
                {$object->name|escape}'s activity needs attention.
                {/if}
              </label>
            </div>
            {if $object_type neq 'user'}
            <div class="radio">
              <label>
                <input type="radio" name="flag-form-option" id="flag-mine" value="mailto:team@makerba.se?subject={$report_subject_mine|escape:'url'}&amp;body={$report_body_mine|escape:'url'}{$report_footer|escape:'url'}">
                {if $object_type eq 'maker'}
                	I am {$object->name|escape} &amp; I have a request.
                {elseif $object_type eq 'project'}
                	{$object->name|escape} is my project &amp; I have a request.
                {/if}
              </label>
            </div>
            {/if}

          </div>
          <div class="modal-footer">
            <a type="button" class="btn btn-primary" id="flag-form-action" href="mailto:team@makerba.se?subject={$report_subject_featured|escape:'url'}&amp;body={$report_body_featured|escape:'url'}{$report_footer|escape:'url'}"><i class="fa fa-envelope"></i> Send</a>
          </div>
        </div>
      </div>
    </div>
