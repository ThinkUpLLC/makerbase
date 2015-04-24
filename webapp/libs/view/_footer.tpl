
    </div><!-- /.container -->

    <footer class="footer">
      <div class="container">
        <p class="pull-right text-muted"><a href="/about/">About</a> &#183; <a href="/about/terms/">Terms</a> &#183; <a href="/about/privacy/">Privacy</a></p>
        <p class="text-muted">&copy; 2015 <a href="/p/{$thinkup_uid}/thinkup">ThinkUp</a>. Go make something.</p>
      </div>
    </footer>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="{$site_root_path}assets/js/vendor/bootstrap-datepicker.min.js"></script>
    <script src="{$site_root_path}assets/js/vendor/typeahead.bundle.min.js"></script>
    <script src="{$site_root_path}assets/js/vendor/handlebars-v3.0.0.js"></script>
    {literal}
    <script type='text/javascript'>
      $('.input-daterange').datepicker({
          format: "yyyy-mm",
          autoclose: 1,
          clearBtn: 1,
          orientation: "top",
          startView: 1,
          minViewMode: 1
      });

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

    </script>

    <script type="text/javascript">
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
          suggestion: Handlebars.compile('<a class="media" href="/{{type}}/{{uid}}/{{slug}}"><div class="media-left"><img class="media-object" src="{{avatar_url}}" alt="{{name}}" width="20" height="20"></div><div class="media-body"><h4 class="media-heading">{{name}}</h4></div></a>'),
          footer: [
            '<br><div class="media-body">',
            '<a onclick="location.href=\'/add/maker/?q=\'+ encodeURIComponent($(\'#nav-typeahead\').val());" class="btn btn-success btn-md">Create a Maker</a> ',
            '<a onclick="location.href=\'/add/product/?q=\'+ encodeURIComponent($(\'#nav-typeahead\').val());" class="btn btn-success btn-md">Create a Project</a>',
            '</div>',
          ].join('\n')
        }
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
        displayKey: 'uid',
        source: searchAllMakers.ttAdapter(),
        templates: {
          empty: [
            '<div class="media" id="add-buttons">',
            '<h4 class="media-heading">Oops! No makers match.</h4>',
            '</div>'
          ].join('\n'),
          suggestion: Handlebars.compile('<div class="media-left"><img class="media-object" src="{{avatar_url}}" alt="{{name}}" width="20" height="20"></div><div class="media-body"><h4 class="media-heading">{{name}}</h4></div>'),
          footer: [
            '<br><div class="media-body"><a onclick="location.href=\'/add/maker/?q=\'+ encodeURIComponent($(\'#maker-name\').val());" class="btn btn-success btn-md">Create Maker</a></div>'
          ].join('\n')
        }
      });


      $('#maker-name').bind('typeahead:selected', function(obj, datum, name) {
              $('#maker-uid').val(datum.uid);
              $('#maker-name').val(datum.name);
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
        displayKey: 'uid',
        source: searchAllProducts.ttAdapter(),
        templates: {
          empty: [
            '<div class="media" id="add-buttons">',
            '<h4 class="media-heading">Oops! No projects match.</h4>',
            '</div>'
          ].join('\n'),
          suggestion: Handlebars.compile('<div class="media-left"><img class="media-object" src="{{avatar_url}}" alt="{{name}}" width="20" height="20"></div><div class="media-body"><h4 class="media-heading">{{name}}</h4></div></div>'),
          footer: [
            '<br><div class="media-body"><a onclick="location.href=\'/add/product/?q=\'+ encodeURIComponent($(\'#product-name\').val());" class="btn btn-success btn-md">Create Project</a></div>'
          ].join('\n')
        }
      });

      $('#product-name').bind('typeahead:selected', function(obj, datum, name) {
              $('#product-uid').val(datum.uid);
              $('#product-name').val(datum.name);
      }).off('blur');

      $(function () {
        $('[data-toggle="popover"]').popover()
      })
    </script>

    <script type='text/javascript'>
      $(window).resize(function() {
        if ($('#add-panels').length){
          if (window.matchMedia('(max-width: 767px)').matches) {
            $('body').height('100%'),
            $('#add-panels').height($(window).height() - $('#add-form').height() - 110),
            $('#add-panels').css('overflow-y', 'scroll');
          }
        }
      });

      $(window).trigger('resize');
    </script>

    {/literal}
  </body>
</html>
