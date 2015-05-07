{include file="_head.tpl"}


<div class="row" id="maker-profile">
  <div class="col-xs-2">
    <img class="img-responsive" src="{if isset($maker->avatar_url)}{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}{else}http://placehold.it/300&text=Image{/if}" alt="{$maker->name}" width="100%">
  </div>
  <div class="col-xs-10">
    <h1 {if $maker->is_archived}class="archived"{/if}><strong>{$maker->name}</strong> is a maker</h1>
    <h5><a href="{$maker->url}" class="text-muted">{$maker->url}</a></h5>
    {if isset($logged_in_user)}
      <button onclick="$('#maker-profile-edit').toggle();$('#maker-profile').toggle();" class="btn btn-default btn-link pull-right">edit</button>
    {else}
      <a href="{$sign_in_with_twttr_link}" class="btn btn-default btn-link pull-right">edit</a>
    {/if}

    {if isset($maker->twitter_username)}
      {assign var="tweet_body" value="@{$maker->twitter_username} Is your Makerbase page up to date?"}
      <a href="https://twitter.com/share?text={$tweet_body|urlencode}" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;" class="btn btn-xs btn-info">Ask @{$maker->twitter_username} to update this page</a>
    {/if}

  </div>
</div>


<div class="row" id="maker-profile-edit">
  <form method="post" action="/edit/maker/">
  <div class="col-xs-2">
    <img class="img-responsive" src="{if isset($maker->avatar_url)}{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}{else}http://placehold.it/300&text=Image{/if}" alt="{$maker->name}" width="100%">
  </div>
  <div class="col-xs-10">
    <input type="text" class="form-control input-lg" autocomplete="off" name="name" id="name" value="{$maker->name}" placeholder="Name">
    <input type="text" class="form-control input-sm" autocomplete="off" name="url" value="{$maker->url}" placeholder="URL for personal website">
    <input type="text" class="form-control input-sm" autocomplete="off" name="avatar_url" value="{$maker->avatar_url}" placeholder="URL for avatar image">
    <input type="hidden" name="maker_uid" value="{$maker->uid}">
    <button class="btn btn-primary" type="submit" id="update-maker">Update maker</button>

    <btn type="button" class="btn btn-default btn-link pull-right" onclick="$('#maker-profile').toggle();$('#maker-profile-edit').toggle();" aria-label="Center Align">cancel</a>
  </div>
  </form>
</div>

<div class="row">
  <div class="col-xs-12">

    <div id="projects">

          <ul class="list-group list-unstyled col-xs-12" id="role-list">
          {foreach $roles as $role}
            <li class="">
                {include file="_role.tpl"}
            </li>
          {/foreach}
          </ul>

          {if isset($logged_in_user)}

            <button class="btn btn-primary" type="submit" id="add-project-action" data-toggle="collapse" data-target="#add-project-form" onclick="$('#add-project-action').toggle();" ><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} project</button>


            <form method="post" action="/add/role/" class="form-horizontal col-xs-12 collapse" id="add-project-form">

            <h2>Add a{if $roles}nother{/if} project</h2>
              <input type="hidden" name="maker_uid" value="{$maker->uid}">
              <input type="hidden" name="originate_slug" value="{$maker->slug}">
              <input type="hidden" name="originate_uid" value="{$maker->uid}">
              <input type="hidden" name="originate" value="maker">
              <input type="hidden" name="product_uid" id="product-uid">
              <!--
              <div class="col-xs-1 col-sm-1">
                <img class="img-responsive" src="" alt="product_name logo">
              </div>
              -->
              <div class="form-group col-xs-12">
                <label for="product_name" class="col-sm-1 control-label hidden-xs">Project</label>
                <div class="col-xs-12 col-sm-10" id="remote-search-products">
                  <input type="text" class="typeahead form-control input-lg" placeholder="Project name" name="product_name" id="product-name">
                </div>
              </div>

              <div class="form-group col-xs-12">
                <label for="role" class="col-sm-1 control-label hidden-xs">Role</label>
                <div class="col-xs-12 col-sm-10">
                  <input type="text" class="form-control" autocomplete="off" id="role" name="role" placeholder="'{$placeholder}'">
                </div>
              </div>

              <div class="form-group col-xs-12">
                <label for="role" class="col-sm-1 control-label hidden-xs">Dates</label>
                <div class="col-xs-12 col-sm-10">

                  <select name="from_month" id="from_month" class="form-input col-sm-2 col-xs-3 input-sm">
                    <option value="">From</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                  </select>

                  <select name="from_year" id="from_year" class="form-input col-sm-2 col-xs-3 input-sm">
                    <option value="">Year</option>
                    <option value="2015">2015</option>
                    <option value="2014">2014</option>
                    <option value="2013">2013</option>
                    <option value="2012">2012</option>
                    <option value="2011">2011</option>
                    <option value="2010">2010</option>
                    <option value="2009">2009</option>
                    <option value="2008">2008</option>
                    <option value="2007">2007</option>
                    <option value="2006">2006</option>
                    <option value="2005">2005</option>
                    <option value="2004">2004</option>
                    <option value="2003">2003</option>
                    <option value="2002">2002</option>
                    <option value="2001">2001</option>
                    <option value="2000">2000</option>
                    <option value="1999">1999</option>
                    <option value="1998">1998</option>
                    <option value="1997">1997</option>
                    <option value="1996">1996</option>
                    <option value="1995">1995</option>
                    <option value="1994">1994</option>
                    <option value="1993">1993</option>
                    <option value="1992">1992</option>
                    <option value="1991">1991</option>
                    <option value="1990">1990</option>
                    <option value="1989">1989</option>
                    <option value="1988">1988</option>
                    <option value="1987">1987</option>
                    <option value="1986">1986</option>
                    <option value="1985">1985</option>
                    <option value="1984">1984</option>
                    <option value="1983">1983</option>
                    <option value="1982">1982</option>
                    <option value="1981">1981</option>
                    <option value="1980">1980</option>
                    <option value="1979">1979</option>
                    <option value="1978">1978</option>
                    <option value="1977">1977</option>
                    <option value="1976">1976</option>
                    <option value="1975">1975</option>
                    <option value="1974">1974</option>
                    <option value="1973">1973</option>
                    <option value="1972">1972</option>
                    <option value="1971">1971</option>
                    <option value="1970">1970</option>
                  </select>

                  <select name="to_month" id="to_month" class="form-input col-sm-2 col-sm-offset-1 col-xs-3 input-sm">
                    <option value="">To</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                  </select>

                  <select name="to_year" id="to_year" class="form-input col-sm-2 col-xs-3 input-sm">
                    <option value="">Present</option>
                    <option value="2015">2015</option>
                    <option value="2014">2014</option>
                    <option value="2013">2013</option>
                    <option value="2012">2012</option>
                    <option value="2011">2011</option>
                    <option value="2010">2010</option>
                    <option value="2009">2009</option>
                    <option value="2008">2008</option>
                    <option value="2007">2007</option>
                    <option value="2006">2006</option>
                    <option value="2005">2005</option>
                    <option value="2004">2004</option>
                    <option value="2003">2003</option>
                    <option value="2002">2002</option>
                    <option value="2001">2001</option>
                    <option value="2000">2000</option>
                    <option value="1999">1999</option>
                    <option value="1998">1998</option>
                    <option value="1997">1997</option>
                    <option value="1996">1996</option>
                    <option value="1995">1995</option>
                    <option value="1994">1994</option>
                    <option value="1993">1993</option>
                    <option value="1992">1992</option>
                    <option value="1991">1991</option>
                    <option value="1990">1990</option>
                    <option value="1989">1989</option>
                    <option value="1988">1988</option>
                    <option value="1987">1987</option>
                    <option value="1986">1986</option>
                    <option value="1985">1985</option>
                    <option value="1984">1984</option>
                    <option value="1983">1983</option>
                    <option value="1982">1982</option>
                    <option value="1981">1981</option>
                    <option value="1980">1980</option>
                    <option value="1979">1979</option>
                    <option value="1978">1978</option>
                    <option value="1977">1977</option>
                    <option value="1976">1976</option>
                    <option value="1975">1975</option>
                    <option value="1974">1974</option>
                    <option value="1973">1973</option>
                    <option value="1972">1972</option>
                    <option value="1971">1971</option>
                    <option value="1970">1970</option>
                  </select>
                </div>
              </div>

              <div class="form-group col-xs-12">

                <input type="hidden" name="start_date" id="start_date" placeholder="YYYY-MM" autocomplete="off" value=""/>
                <input type="hidden" name="end_date" id="end_date" autocomplete="off" />

                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                  <a class="btn btn-link btn-sm pull-right" data-toggle="collapse" data-target="#add-project-form" onclick="$('#add-project-action').toggle();" >cancel</a>
                  <button class="btn btn-primary" type="submit">Add project</button>
                </div>
              </div>

            </form>


          {else}

            <a href="{$sign_in_with_twttr_link}" class="btn btn-primary col-offset-xs-1 col-offset-sm-3" id="add-project-action"><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} project</a>

          {/if}


        </div>

        <div id="history">

          <h4>History</h4>

          {if sizeof($actions) > 0}
          <ul class="list-group">
          {foreach $actions as $action}
              <li class="list-group-item col-xs-12">
              {include file="_action.tpl"}
              </li>
          {/foreach}
          </ul>
          {/if}
        </div>

    </div>
  </div>
</div>

{include file="_footer.tpl"}