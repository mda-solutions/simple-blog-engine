simple-blog-engine
==================

"Minimal MDA-Blog Engine" es un motor para publicar un blog muy simple únicamente consta de 2 archivos PHP, las publicaciones se hacen mediante archivos de html individuales por post.

» Solo necesitas un servidor ejecutando PHP 5.3+ 
» La interfaz es totalmente AJAX hecho con Knockout.js
» URL Friendly
» Generador RSS
» No necesita base de datos
» No necesita motor de plantillas, puedes adaptar cualquier HTML

========================================================================
¿Cómo funciona?


Todos los posts se almacenan en un folder que podemos especificar en el archivo de configuración settings.ini (por default se llama "posts") la magia está en nombrarlos de la manera siguiente:

001_Nombre_de_Mi_Post.html

los guiones bajos ( _ ) son importante porque delimitan el orden de nuestros posts, en este caso el 001 nos indica la primer posición y el resto el engine se encarga de formatearlo y presentarlo como un título legíble :), por ejemplo este mismo item se llama "01_¿Cómo_funciona.html?". 

En estos posts puedes poner cualquier HTML válido o simplemente texto

========================================================================
Configuración


El archivo "Settings.ini" contiene los parámetros que podemos configurar, para la sección "behaviours":

» items_per_page: Indica el numero de items/posts por página.
» folder_entries: Indica el folder donde almacenamos nuestros items/posts
» date_format: Indica el formato de la fecha (puedes consultar los formatos en: la documentación PHP)
» order: Indica el órden en el que aparecen los items/posts, ascendente o descendente

Para la sección "RSS", estas únicamente afectan a las suscripciones vía RSS:

» base_url: El URL de nuestro blog.
» blog_name: Nombre de nuestro blog.
» language:Idioma en el que está nuestro blog.
» description: Descrición breve de los temas.
» author: Tu nombre! :)

========================================================================
¿Cómo lo personalizo?

Es MUY fácil, solo edita el index.html en tu editor favorito (hasta Dreamwaver vale!) y realiza los cambios que quieras,El template que se proporciona, esta basado en "Skeleton", por lo que te puedes dirigir a la documentación en: getskeleton.com o bien, poner tu propio HTML, para ello considera: 

» Incluir el archivo de JQuery (1.8+) en el HEAD
» Incluir el archivo de knockoutjs (2.3+) en el HEAD
» Incluir antes de cerrar la etiqueta BODY el archivo "engine.UI.js"
» Incluir el siguiente input: <input type="hidden" value="1" id="page" name="page" />
» Incluir el siguiente código en tu página:

		<!-- TEMPLATE PARA LOS POSTS-->
		<div data-bind="foreach: posts, visible: posts().length > 0">
			<a data-bind="attr: { id: id, title: title }">
			    <h2 data-bind="text: title"></h2>
			</a>			
			<h5 data-bind="text: date"></h5>
			<p data-bind="html: content"></p>
			<hr />
		</div>
		<!-- TERMINA TEMPLATE PARA LOS POSTS-->

		<div >
			<a id="more_items" href="javascript:void(0);" data-bind="click: getPosts" class="full-width button">Mostrar más Elementos</a>
			<hr>
		</div>		
	
» Para el menú utiliza el siguiente template:

			<ul data-bind="foreach: items, visible: items().length > 0" style="text-align: center;">
				<li style="display: inline-block; font-size:20px; margin-left:5px;">
					<a data-bind="click: $parent.goHash, attr: { href: hash, title: title, class: 'menu_item' }">
						<span data-bind="html: title"></span>
					</a>
				</li>
			</ul>
