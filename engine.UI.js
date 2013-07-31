
		var page = parseInt($('#page').val());

		function Item(data) 
		{
		    this.title   = ko.observable(data.title);
		    this.date    = ko.observable(data.date);
		    this.hash    = ko.observable(data.hash);
		}

		function Post(data) 
		{
		    this.title     = ko.observable(data.title);
		    this.content   = ko.observable(data.content);
		    this.date      = ko.observable(data.date);
		    this.id        = ko.observable(data.id);
		    this.hash      = ko.observable(data.hash);
		}			

		function BlogViewModel() 
		{		    
		    var self   = this;
		    self.items = ko.observableArray([]);		    
		    self.posts = ko.observableArray([]);

		    self.goHash = function()
		    {
		        var this_hash = (this.hash == undefined) ? window.location.hash : this.hash();
		        self.init();
		        if(this_hash == "") return

		        location.hash = this_hash;

		    	var hash = this_hash.split("/");
		    	var post = hash[0].replace('#','');

		    	if ($("#" + post).length > 0)
		    	{
 					$('html,body').animate({scrollTop: $("#" + post).offset().top},'slow');
 					return;
				}

				//load 'til post exists				
				var nums      = post.split('_');
				var page_to   = nums[1];
				var page_from = parseInt($('#page').val());

			    $.getJSON("engine.php?action=range&from="+page_from+"&to="+page_to, function(data) 
			    {			        
			        var mappedPosts = $.map(data, function(_post) { return new Post(_post) });

			        if(mappedPosts.length > 0)
			        {			        	
				        $.each(mappedPosts, function(key, value)
			        	{
			        		console.log(value.id());
			        		self.posts.push(value);
			        	});					
			        }

			        console.log($("#" + post).length);

			    	if ($("#" + post).length > 0)
			    	{
	 					$('html,body').animate({scrollTop: $("#" + post).offset().top},'slow');
	 					$('#page').val(page_to);
	 					return;
					}
			    });  
		    }

		    self.getPosts = function() 
		    {		    	
		    	page = parseInt($('#page').val());
		    	page ++;

			    $.getJSON("engine.php?action=posts&page=" + page, function(data) 
			    {			        
			        var ids = new Array();
			    	$.each(data, function(key, value)
		    		{
		    			ids.push(value.id);
		    		});

			        var mappedPosts = $.map(data, function(post) { return new Post(post) });

			        if(mappedPosts.length > 0)
			        {
				        $.each(mappedPosts, function(key, value)
			        	{
			        		self.posts.push(value);
			        	});

			        	$('html,body').animate({scrollTop: $("#"+ids[0]).offset().top},'slow');
				    	$('#page').val(page);
			        	return;
			        }

			        $('#more_items').html('Ops! ya no hay más elementos.');
			    }); 		    	
		    };

		    self.init = function()
		    {
			    $.getJSON("engine.php?action=menu", function(data) 
			    {
			        var mappedItems = $.map(data, function(item) { return new Item(item) });
			        self.items(mappedItems);
			    });

			    $.getJSON("engine.php?action=posts&page=" + page, function(data) 
			    {
			        var mappedPosts = $.map(data, function(post) { return new Post(post) });
			        self.posts(mappedPosts);
			    }); 
		    }

		    self.goHash();				  
		}	

		ko.applyBindings(new BlogViewModel());

