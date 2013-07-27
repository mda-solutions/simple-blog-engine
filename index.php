<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Simple Blog Engine</title>
  <meta name="description" content="Simple Blog Engine">
  <meta name="author" content="mda-solutions">

  <!--link rel="stylesheet" href="css/styles.css?v=1.0"-->
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <script type='text/javascript' src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
  <script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/knockout/2.3.0/knockout-min.js'></script>

</head>
<body>

	<div id="wrapper">
		<header>
			encabezado
		<header>
		<nav>
			<ul data-bind="foreach: items, visible: items().length > 0">
			    <li>
			        <a href="#" data-bind="text: title"></a> <span data-bind="text: date"></span>
			    </li> 
			</ul>
		</nav>

		<section id="content">

			<article>
				aqui va todo el contenido del post
			</article>

		</section>

		<footer>
			pie de pagina
		</footer>

	</div>

	<script type="text/javascript">

		function Item(data) 
		{
		    this.title   = ko.observable(data.title);
		    this.date    = ko.observable(data.date);
		}

		function MenuListViewModel() 
		{
		    // Data
		    var self = this;
		    self.items = ko.observableArray([]);
		    $.getJSON("engine.php?action=menu", function(data) 
		    {
		        var mappedItems = $.map(data, function(item) { return new Item(item) });
		        self.items(mappedItems);
		    }); 				  
		}

		ko.applyBindings(new MenuListViewModel());

	</script>

  <!--script src="js/scripts.js"></script-->

</body>
</html>