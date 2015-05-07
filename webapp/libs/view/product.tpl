{include file="_head.tpl"}

<div class="row" id="project-profile">
  <div class="col-xs-2">
    <img class="img-responsive" src="{if isset($product->avatar_url)}{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='m'}{else}http://placehold.it/300&text=Image{/if}" alt="{$product->name}" width="100%">

  </div>
  <div class="col-xs-10">
    <h1 {if $product->is_archived}class="archived"{/if}>We made <strong>{$product->name}</strong></h1>
    <h3>{$product->description}</h3>
    <h5><a href="{$product->url}" class="text-muted">{$product->url}</a></h5>
    {if isset($logged_in_user)}
      <button onclick="$('#project-profile-edit').toggle();$('#project-profile').toggle();" class="btn btn-default btn-link pull-right">edit</button>
    {else}
      <a href="{$sign_in_with_twttr_link}" class="btn btn-default btn-link pull-right">edit</a>
    {/if}

  </div>
</div>



<div class="row" id="project-profile-edit">


  <div class="col-xs-2">
    <img class="img-responsive" src="{if isset($product->avatar_url)}{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='m'}{else}http://placehold.it/300&text=Image{/if}" alt="{$product->name}" width="100%">

    <form method="post" action="/edit/product/" id="project-profile-archive" class="">
      <div class="form-group">
        <input type="hidden" name="uid" value="{$product->uid}" />
        <input type="hidden" name="archive" value="{if $product->is_archived}0{else}1{/if}"/>
        {if $product->is_archived}
        <button type="submit" class="btn btn-xs btn-success col-xs-12" id="project-profile-archive-button">
            <span class="fa fa-check"></span> <span class="hidden-xs">Unarchive</span>
        </button>
        {else}
        <button type="submit" class="btn btn-xs btn-danger col-xs-12" id="project-profile-archive-button">
            <span class="fa fa-remove"></span> <span class="hidden-xs">Archive</span>
        </button>
        {/if}
      </div>
    </form>

  </div>
  <div class="col-xs-10">

  <form method="post" action="/edit/maker/" id="project-profile-edit-form">

          <div class="form-group">
            <label for="name" class="col-sm-2 hidden-xs control-label">Name</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-lg" autocomplete="off" name="name" id="name" value="{$product->name}">
            </div>
          </div>

          <div class="form-group">
            <label for="description" class="col-sm-2 hidden-xs control-label">Description</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control" autocomplete="off" name="description" value="{$product->description}">
            </div>
          </div>

          <div class="form-group">
            <label for="url" class="col-sm-2 hidden-xs control-label">Web site</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-sm" autocomplete="off" name="description" id="url" value="{$product->url}" placeholder="https://www.example.com/">
            </div>
          </div>
          
          <div class="form-group">
            <label for="avatar_url" class="col-sm-2 hidden-xs control-label">Project avatar</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-sm" autocomplete="off" name="description" id="avatar_url" value="{$product->avatar_url}" placeholder="https://www.example.com/image.png">
            </div>
          </div>

          
          <div class="form-group">
            <div class="col-xs-12 col-sm-10 col-sm-offset-2">
              <input type="hidden" name="product_uid" value="{$product->uid}">
              <button class="btn btn-primary" type="submit" id="update-project">Update project</button>
            </div>
          </div>
  </form>

    <btn type="button" class="btn btn-default btn-link pull-right" onclick="$('#project-profile').toggle();$('#project-profile-edit').toggle();">cancel</a>
  </div>

</div>


<div class="row">
  <div class="col-xs-12">

    <div id="makers">

          <ul class="list-group list-unstyled col-xs-12" id="role-list">
          {foreach $roles as $role}
            <li class="">
                {include file="_role.tpl"}
            </li>
          {/foreach}
          </ul>


          <!-- add roles -->


          {if isset($logged_in_user)}

            <button class="btn btn-primary" type="submit" id="add-maker-action" data-toggle="collapse" data-target="#add-maker-form" onclick="$('#add-maker-action').toggle();" ><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} maker</button>


            <form method="post" action="/add/role/" class="form-horizontal col-xs-12 collapse" id="add-maker-form">

            <h2>Add a{if $roles}nother{/if} maker</h2>
              <input type="hidden" name="product_uid" value="{$product->uid}">
              <input type="hidden" name="originate_slug" value="{$product->slug}">
              <input type="hidden" name="originate_uid" value="{$product->uid}">
              <input type="hidden" name="originate" value="product">
              <input type="hidden" name="maker_uid" id="maker-uid">
              <!--
              <div class="col-xs-1 col-sm-1">
                <img class="img-responsive" src="" alt="maker_name avatar">
              </div>
              -->
              <div class="form-group col-xs-12">
                <label for="product_name" class="col-sm-1 control-label hidden-xs">Name</label>
                <div class="col-xs-12 col-sm-10" id="remote-search-makers">
                  <input type="text" class="typeahead form-control input-lg" placeholder="Maker's name" name="maker_name" id="maker-name">
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
                  <a class="btn btn-link btn-sm pull-right" data-toggle="collapse" data-target="#add-maker-form" onclick="$('#add-maker-action').toggle();" >cancel</a>
                  <button class="btn btn-primary" type="submit">Add maker</button>
                </div>
              </div>

            </form>


          {else}

            <a href="{$sign_in_with_twttr_link}" class="btn btn-primary col-offset-xs-1 col-offset-sm-3" id="add-maker-action"><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} project</a>

          {/if}

          <!-- /add roles -->


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



{include file="_footer.tpl"}
