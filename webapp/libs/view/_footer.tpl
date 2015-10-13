
    </div><!-- /.container -->

    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-sm-4 col-sm-offset-1 col-xs-4">
            <img src="/assets/img/makerbase-mark-white.svg" class="hidden-xs pull-left" width="120" style="padding-right: 20px; margin-top: -20px;"/>
            <ul class="list-unstyled">
              <li>&copy; 2015 <a href="/p/{$thinkup_uid}/thinkup">ThinkUp</a></li>
              <li>Go make something.</li>
            </ul>
          </div>

          <div class="col-sm-2 col-xs-4">
            <p>sponsors:</p>
            <ul class="list-unstyled footer-sponsors">

              <li class="">
                <a href="/p/7p97ga/hover">
                    <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-small-hover.png" alt="Hover">
                    Hover
                  </a>
              </li>
              <li class="">
                <a href="/p/9u0s6y/mailchimp">
                    <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-small-mailchimp.png" alt="MailChimp">
                    MailChimp
                  </a>
              </li>
              <li class="">
                <a href="/p/m348b6/slackhq">
                    <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-square-slack.png" alt="Slack">
                    Slack
                  </a>
              </li>
            </ul>
          </div>

          <div class="col-sm-2 hidden-xs">
            <ul class="list-unstyled">
              <li><a href="/about/terms/">Terms</a></li>
              <li><a href="/about/privacy/">Privacy</a></li>
              <li><a href="mailto:team@makerba.se?subject=sponsorship">Become a sponsor</a></li>
              <li><a href="mailto:team@makerba.se">Contact us</a></li>
            </ul>
          </div>

          <div class="col-sm-2 col-xs-4">
            <ul class="list-unstyled">
              <li><a href="/explore/"  style="color: black;"> <span>New!</span> Explore</a></li>
              <li><a href="/about/">About</a></li>
              <li><a href="https://making.makerba.se">Blog</a></li>
              <li><i class="fa fa-twitter hidden-xs"></i> <a href="https://twitter.com/makerbase">@makerbase</a></li>
            </ul>
          </div>

        </div>
      </div>
    </footer>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{$site_root_path}assets/js/vendor/typeahead.bundle.min.js"></script>
    <!-- Twitter intents -->
    <script type="text/javascript" async src="//platform.twitter.com/widgets.js"></script>

    {literal}
    <script type='text/javascript'>

      //Smart suggestions autofills
      $('.add-autofill').click(function(){
          $('#name').val($(this).data('name'));
          $('#avatar-url').val($(this).data('avatar'));
          $('#avatar-img').attr('src', $(this).data('avatar'));
          $('#url').val($(this).data('url'));
          $('#description').val($(this).data('description'));
          $('#slug').val($(this).data('slug'));
          $('#network-id').val($(this).data('network-id'));
          $('#network').val($(this).data('network'));
          $('#network-username').val($(this).data('network-username'));
      });

      //Search makers and products
      var searchAllMakersProducts = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: '/autocomplete/?q=%QUERY'
      });

      searchAllMakersProducts.initialize();

      $('#remote-search .typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 1,
        container: 'body'
      },
      {
        name: 'all-makers-products',
        displayKey: 'name',
        source: searchAllMakersProducts.ttAdapter(),
        templates: {
           empty: [
            '<div class="media" id="add-buttons">',
            '<h4 class="media-heading">Oops! No makers or projects match.</h4>',
            '</div>'
          ].join('\n'),
          suggestion: function(data){
            return '<a class="media" href="/' + data.type + '/' + data.uid + '/' + data.slug + '"><div class="media-left"><img class="media-object" src="' + data.avatar_url + '" alt="' + data.name + '" width="20" height="20"></div><div class="media-body"><h4 class="media-heading">' + data.name + '</h4></div></a>';
          },
          footer: [
            '<br><div class="media-body">',
            '<a onclick="location.href=\'/add/maker/?q=\'+ encodeURIComponent($(\'#nav-typeahead\').val());" class="btn btn-success btn-md">Create this Maker</a> ',
            '<a onclick="location.href=\'/add/product/?q=\'+ encodeURIComponent($(\'#nav-typeahead\').val());" class="btn btn-success btn-md">Create this Project</a>',
            '</div>',
          ].join('\n')
        }
      }).on('typeahead:selected', function (e, suggestion, data) {
          document.location = '/' + suggestion.type + '/' + suggestion.uid + '/' + suggestion.slug;
      });

      //Autocomplete makers
      var searchAllMakers = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('slug'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: '/autocomplete/?type=maker&q=%QUERY'
      });

      searchAllMakers.initialize();

      $('#remote-search-makers .typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 1,
        container: 'body'
      },
      {
        name: 'all-makers',
        displayKey: 'name',
        source: searchAllMakers.ttAdapter(),
        templates: {
          empty: [
            '<div class="media" id="add-buttons">',
            '<h4 class="media-heading">Oops! No makers match.</h4>',
            '</div>'
          ].join('\n'),
          suggestion: function(data){
            return '<div class="media-left"><img class="media-object" src="' + data.avatar_url + '" alt="' + data.name + '" width="20" height="20"></div><div class="media-body"><h4 class="media-heading">' + data.name + '</h4></div>';
          },
          footer: [
            '<br><div class="media-body"><a onclick="location.href=\'/add/maker/{/literal}{if isset($smarty.get.uid)}{$smarty.get.uid}/{/if}{literal}?q=\'+ encodeURIComponent($(\'#maker-name\').val());" class="btn btn-success btn-md">Create this Maker</a></div>'
          ].join('\n')
        }
      });


      $('#maker-name').bind('typeahead:selected', function(obj, datum, name) {
              $('#maker-uid').val(datum.uid);
              $('#maker-name').val(datum.name);
              $('#maker-extended-form').show();
      }).off('blur');

      //Autocomplete products
      var searchAllProducts = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('slug'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: '/autocomplete/?type=product&q=%QUERY'
      });

      searchAllProducts.initialize();

      $('#remote-search-products .typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 1,
        container: 'body'
      },
      {
        name: 'all-products',
        displayKey: 'name',
        source: searchAllProducts.ttAdapter(),
        templates: {
          empty: [
            '<div class="media" id="add-buttons">',
            '<h4 class="media-heading">Oops! No projects match.</h4>',
            '</div>'
          ].join('\n'),
          suggestion: function(data){
            return '<div class="media-left"><img class="media-object" src="' + data.avatar_url + '" alt="' + data.name + '" width="20" height="20"></div><div class="media-body"><h4 class="media-heading">' + data.name + '</h4></div></div>';
          },
          footer: [
            '<br><div class="media-body"><a onclick="location.href=\'/add/product/{/literal}{if isset($smarty.get.uid)}{$smarty.get.uid}/{/if}{literal}?q=\'+ encodeURIComponent($(\'#product-name\').val());" class="btn btn-success btn-md">Create this Project</a></div>'
          ].join('\n')
        }
      });

      $('#product-name').bind('typeahead:selected', function(obj, datum, name) {
              $('#product-uid').val(datum.uid);
              $('#product-name').val(datum.name);
              $('#product-extended-form').show();
      }).off('blur');

      //Enable drop-down month/year selection in roles

      $('.from_month').change(function(){
          var date_parent = $(this).closest("div").attr("id");
          if ($('#' + date_parent + ' .from_month').val() && $('#' + date_parent + ' .from_year').val()) {
            var start_date = $('#' + date_parent + ' .from_year').val() + '-' + $('#' + date_parent + ' .from_month').val();
            $('#' + date_parent + ' input#start_date_' + date_parent).val(start_date);

            $('#' + date_parent + ' .to_month').show();
            $('#' + date_parent + ' .to_year').show();
          } else {
            $('#' + date_parent + ' input#start_date_' + date_parent).val('');

            $('#' + date_parent + ' .to_month').hide();
            $('#' + date_parent + ' .to_year').hide();
          }
      });
      $('.from_year').change(function(){
          var date_parent = $(this).closest("div").attr("id");
          if ($('#' + date_parent + ' .from_month').val() && $('#' + date_parent + ' .from_year').val()) {
            var start_date = $('#' + date_parent + ' .from_year').val() + '-' + $('#' + date_parent + ' .from_month').val();
            $('#' + date_parent + ' input#start_date_' + date_parent).val(start_date);

            $('#' + date_parent + ' .to_month').show();
            $('#' + date_parent + ' .to_year').show();
          } else {
            $('#' + date_parent + ' input#start_date_' + date_parent).val('');

            $('#' + date_parent + ' .to_month').hide();
            $('#' + date_parent + ' .to_year').hide();
          }
      });
      $('.to_month').change(function(){
          var date_parent = $(this).closest("div").attr("id");
          if ($('#' + date_parent + ' .to_month').val() && $('#' + date_parent + ' .to_year').val()) {
            var end_date = $('#' + date_parent + ' .to_year').val() + '-' + $('#' + date_parent + ' .to_month').val();
            $('#' + date_parent + ' input#end_date_' + date_parent).val(end_date);
          } else {
            $('#' + date_parent + ' input#end_date_' + date_parent).val('');
          }
      });
      $('.to_year').change(function(){
          var date_parent = $(this).closest("div").attr("id");
          if ($('#' + date_parent + ' .to_month').val() && $('#' + date_parent + ' .to_year').val()) {
            var end_date = $('#' + date_parent + ' .to_year').val() + '-' + $('#' + date_parent + ' .to_month').val();
            $('#' + date_parent + ' input#end_date_' + date_parent).val(end_date);
          } else {
            $('#' + date_parent + ' input#end_date_' + date_parent).val('');
          }
      });

      // Handle subjects on flagging form
      $('#flagform input[name="flag-form-option"]').change(function(){
        $('#flagform #flag-form-action').attr("href", $(this).val())
      });

      // Trigger wait-states on slow adding actions
      $( "form.add-form" ).submit(function() {
        $("form.add-form #add-action").prop('disabled', function (_, val) { return ! val; });
      });

      // Initialize popovers, alerts and searchbox
      $(function () {
        $('[data-toggle="popover"]').popover();
        $(".alert").addClass("in");
        $('#nav-typeahead').focus();


        // Javascript to enable link to tab
        var url = document.location.toString();
        if (url.match('#')) {
            $('#maker-tab-set a[href=#'+url.split('#')[1]+']').tab('show') ;
        } 

        // Change hash for page-reload
        $('#maker-tab-set a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        })


      });

      // Analytics tracking

      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    {/literal}{if isset($logged_in_user)}{literal}
      ga('create', 'UA-62611536-1', { 'userId': '{/literal}{$logged_in_user->uid}{literal}' });
    {/literal}{else}{literal}
      ga('create', 'UA-62611536-1');
    {/literal}{/if}{literal}

      ga('send', 'pageview');

    </script>

    {/literal}
  </body>
</html>