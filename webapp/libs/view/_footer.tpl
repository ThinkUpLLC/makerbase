
    </div><!-- /.container -->

    <footer class="footer">
      <div class="container">
        <p class="text-muted">&copy; 2015 <a href="/p/thinkup">ThinkUp</a>. Now go make something.</p>
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
    </script>

    <script type="text/javascript">
      //Search makers and products
      var searchAllMakersProducts = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: '/search.php?auto=1&q=%QUERY'
      });

      searchAllMakersProducts.initialize();

      $('#remote-search .typeahead').typeahead(null, {
        name: 'all-makers-products',
        displayKey: 'name',
        source: searchAllMakersProducts.ttAdapter(),
        templates: {
          empty: [
            '<div class="empty-message">',
            'Oops! No makers or products match',
            '</div>'
          ].join('\n'),
          suggestion: Handlebars.compile('<p style="background-color:white;color:gray"><strong>{{slug}}</strong> – {{name}}</p>')
        }
      });

      //Autocomplete makers
      var searchAllMakers = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('slug'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: '/search.php?auto=1&type=maker&q=%QUERY'
      });

      searchAllMakers.initialize();

      $('#remote-search-makers .typeahead').typeahead(null, {
        name: 'all-makers',
        displayKey: 'slug',
        source: searchAllMakers.ttAdapter(),
        templates: {
          empty: [
            '<div class="empty-message">',
            'Oops! No makers match',
            '</div>'
          ].join('\n'),
          suggestion: Handlebars.compile('<p style="background-color:white;color:gray"><strong>{{slug}}</strong> – {{name}}</p>')
        }
      });

      //Autocomplete products
      var searchAllProducts = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('slug'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: '/search.php?auto=1&type=product&q=%QUERY'
      });

      searchAllProducts.initialize();

      $('#remote-search-products .typeahead').typeahead(null, {
        name: 'all-products',
        displayKey: 'slug',
        source: searchAllProducts.ttAdapter(),
        templates: {
          empty: [
            '<div class="empty-message">',
            'Oops! No products match',
            '</div>'
          ].join('\n'),
          suggestion: Handlebars.compile('<p style="background-color:white;color:gray"><strong>{{slug}}</strong> – {{name}}</p>')
        }
      });
    </script>
    {/literal}
  </body>
</html>
