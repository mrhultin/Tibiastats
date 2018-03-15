<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" type="image/png" href="/favicon.png"/>
	<link rel="shortcut icon" type="image/png" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/favicon.png"/>
<title><?php echo $siteTitle; ?></title>
	<script src="/app/media/pace.min.js"></script>
	<link type="text/css" rel="stylesheet" href="/app/media/pace-theme-minimal.css">
<link rel="stylesheet" type="text/css" href="/app/media/semantic.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/reset.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/site.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/container.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/grid.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/header.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/image.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/menu.css">

	<link rel="stylesheet" type="text/css" href="/app/media/components/divider.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/list.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/segment.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/dropdown.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/icon.css">
	<link rel="stylesheet" type="text/css" href="/app/media/components/transition.css">

	<link rel="stylesheet" type="text/css" href="/app/media/components/search.css">

	<script src="/app/media/jquery.js"></script>
	<script src="/app/media/semantic.min.js"></script>
	<script src="/app/media/components/api.min.js"></script>

	<script src="/app/media/components/transition.js"></script>
	<script src="/app/media/components/dropdown.js"></script>
	<script src="/app/media/components/visibility.js"></script>
	<script src="/app/media/components/popup.min.js"></script>
	<script src="/app/media/components/search.min.js"></script>
	<style>
		body {
		
		}
		.change-negative {
			color:#B52C2C;
		}
		.change-positive {
			color:#1CAD1C;
		}
		#content-container {
			margin:0 auto;
		}
		.ui.inverted.menu .header.item {
			color:#EC7A2E;
		}
		/* Search */
		.ui.search > .results {
			margin-left: -9.83em;
			margin-top: -2px;
			width: 25em;
			background: #1b1c1d;
			border: 1px solid transparent;
			box-shadow: none;
			border-bottom-right-radius: 3px;
			border-bottom-left-radius: 3px;
		}
		.ui.search > .results > :first-child {
			border-radius: 0;
		}
		.ui.search > .results > .message .header, .ui.search > .results > .message .description {
			color: rgba(255, 255, 255, 0.9);
		}

		.ui.search > .results .result.active, .ui.category.search > .results .category .result.active {
			background: #2b2a2a;
			color: rgba(255, 255, 255, 0.9);
		}
		.ui.search > .results .result.active .title, .ui.search > .results .result.active .description {
			color: rgba(255, 255, 255, 0.9);
		}
		.ui.search > .results > .action {
			display: block;
			border-top: none;
			background: #141415;
			padding: 0.92857143em 1em;
			color: #CCC;
			font-weight: bold;
			text-align: center;
		}
		.ui.search .action:hover {
			background: #2b2a2a;
		}
		.ui.search > .results .result .title, .ui.search > .results .result .description {
			color: rgba(255, 255, 255, 0.9);
		}
		.ui.search > .results .result:hover, .ui.category.search > .results .category .result:hover {
			background: #313131;
		}

	</style>
	<script>
		$(document).ready(function() {
			// fix main menu to page on passing
			// Menu and content container rewrite needed before enabling this
			/*$("#mb").visibility({
				type: 'fixed'
			});
			*/
			$('.popup').popup({hoverable: true, position: "bottom center"});
			$('.info-popup').popup({hoverable: true, position: "top center"});
			// Search UI.
			$('.ui.search').search({
					maxResults: 6,
					debug: true,
					apiSettings: {
						url: '//<?php echo $_SERVER['HTTP_HOST']; ?>/character/search_ajax/{query}'
					},
					fields: {
						results : 'items',
						title   : 'name',
						url     : 'html_url',
						description: 'description'
					},
					error: {
						noResults: "<p>We failed to find any character by that name</p>"
					},
					minCharacters: 2,
			});
			
			// Calculators
			$("#party-calc").keyup(function(){
				var element = $("#party-calc");
				var target  = $("#party-calc-results")
				var mylevel = element.val();
				if(mylevel === ""){
					target.hide();
				} else {
					var max = Math.floor(+mylevel + +mylevel/2);
					var min = Math.ceil((mylevel/3)*2);
					
					target.html("A character of level <strong>"+mylevel+"</strong> can share experience with players as low as <strong>"+min+"</strong> and as high as <strong>"+max+"</strong>");
					target.show();
				}
			});
		});
	</script>

</head>
<body>

<div id="content-container" class="ui grid container">
 <div class="sixteen wide column">
	<div class="ui pointing inverted menu" id="mb">
		<div class="header item">
		<?php echo $siteHeader; ?>
		</div>
		<a class="<?php if($page == "home"){ echo 'active ';}?>item" href="/"><strong>Home</strong></a>
		<a class="<?php if($page == "toplist"){ echo 'active ';}?>item" href="/toplist/"><strong>Top 500</strong></a>
		<a class="<?php if($page == "worlds"){ echo 'active ';}?>item" href="/worlds/"><strong>Worlds</strong></a>
		<a class="<?php if($page == "character"){ echo 'active ';}?>item" href="/character/"><strong>Characters</strong></a>
		<a class="<?php if($page == "deleted"){ echo 'active ';}?>item" href="/deleted/"><strong>Deleted characters</strong></a>
		<!--<a class="<?php if($page == "demographics"){ echo 'active ';}?>item" href="/demographics/"><strong>Demographics</strong></a>-->
		<div class="right inverted menu ui search">
			<div class="item">
				<form action="/character/search/" method="post">
					<div class="ui inverted transparent icon input">
						<input name="charactername" id="charactername" class="prompt" type="text" autocomplete="off" placeholder="Character search...">
						<i class="search link icon"></i>
					</div>
				</form>

			</div>
			<div class="results ui inverted pointing"></div>
		</div>
	</div>

	 <div class="ui success message">
		 <div class="header">
			 We're live!
		 </div>
		 <p>The site has just gone live, so there may be bugs. Please report bugs <a href="/contact/">here</a>.</p>
	 </div>
<!-- Add menu -->