{if $type == "frames"}
<frameset cols="50%,50%">
  <frame src="/aph/posts/" name="posts">
  <frame src="/" name="makerbase">
</frameset>

{else}

<html>
<head>

  <!-- Handlebars templating framework -->
  <script src="/assets/ph/js/handlebars-v4.0.5.js"></script>

  <!-- jQuery -->
  <script src="/assets/ph/js/jquery-1.12.0.min.js"></script>

  <!-- Clipboard.js -->
  <script src="/assets/ph/js/clipboard.min.js"></script>

  <meta name="viewport" content="width=device-width">

  {literal}
  <!-- Product -->
  <script id="product-template" type="text/x-handlebars-template">
  {{#posts}}
  {{#if makers}}
  <li class="product"><a onclick="window.open('{{redirect_url}}','fullUrl','width=720,height=800');window.open('/add/product/?q='+escape('{{name}}')+'&description='+escape('{{tagline}}')+'&avatar_url='+escape('{{thumbnail.image_url}}'), 'makerbase', '');return false;" href="#">{{name}}</a> - {{tagline}}
  <ul>
    {{#makers}}
    {{#if twitter_username}}
      <li><input id="{{twitter_username}}" value="@{{twitter_username}}">
          <button class="btn" data-clipboard-target="#{{twitter_username}}">
              <img src="/assets/ph/img/clippy.svg" alt="Copy to clipboard" width="15" height="15">
          </button>
      </li>
    {{/if}}
    {{/makers}}
  </ul>
  </li>
  {{/if}}
  {{/posts}}
  </script>
  <style type="text/css">
    li.product { margin-bottom: 20px;}
  </style>
{/literal}

</head>

<body>

  <h1>New products</h1>

  <ul id="product-list"></ul>
  {literal}
   <!-- Fetch, compile and display product data-->
    <script type="text/javascript">
    var productList = {

      handleData: function(resultJSON) {
        var templateSource = document.getElementById('product-template').innerHTML,
          template = Handlebars.compile(templateSource),
          employeeHTML = template(resultJSON);
        $('#product-list').html(employeeHTML);
      },

      loadProductData: function() {
        $.ajax({
          //For development and testing
          //url: "/assets/ph/js/test-data.json",
          url: "https://api.producthunt.com/v1/posts?access_token={/literal}{$ph_token}{literal}",
          method: 'get',
          success: this.handleData
        })
      }
    };

    $(document).ready(function() {
      productList.loadProductData();
      new Clipboard('.btn');
    });

    </script>
  {/literal}

</body>

</html>
{/if}