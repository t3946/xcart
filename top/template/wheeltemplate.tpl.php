<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
	html {height:100%;}
	body
	{
		margin: 0;
		padding: 0;
		height:100%;
		overflow:hidden;
	}
	
	div#iframediv
	{
		width: 100%;
		height: -webkit-calc(100% - 54px);
		height: -moz-calc(100% - 54px);
		height: calc(100% - 54px);
		margin-top: 54px;
	}
	
	iframe
	{
		width: 100%;
		height:100%;
		border: 0;
	}
	#header {
		background: #ffffff none repeat scroll 0 0;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.72);
		color: #000000;
		height: 54px;
		left: 0;
		min-width: 990px;
		position: fixed;
		top: 0;
		width: 100%;
		z-index: 3;
	}
	#back_link, #forward_link {
		width:70px;
		height: 50px;
		position: absolute;
		background-size: cover;
		top:2px;
		outline: none;
	}
	 #back_link {
		 background-image: url("images/r2.png");
		 left:50px;
	 }
	 #forward_link {
		 background-image: url("images/r1.png");
		 right:50px;
	 }
	</style>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			// <![CDATA[
			<?php
			global $gSitesArrayJS;
			?>

			var trans = <?php echo $gSitesArrayJS;?>;
			idx = 0;
			firstLoad = true;

			function getLocationId() {
				var slocation = location.href,
						str_param = slocation.match(/w=([^&]+)/),
						id = 0;
				if (str_param != null && str_param.length > 0)
					id = str_param[1];
				return (id);
			}

			var spathName = location.pathname;

			$( document ).ready(function() {
				loadRemoteFrame('ifrm', false, getLocationId());
			});

			window.addEventListener("popstate", function() {
				loadRemoteFrame('ifrm', false, getLocationId());
			});

			function loadRemoteFrame(frame_Name, url, idx_force = false) {
				var i_frame = document.getElementById(frame_Name),
						len = trans.length;

					switch (url) {
						case 'frv' :
							(idx >= len-1) ? idx=0 : idx++;
						break;
						case 'rev' :
							(idx == 0) ? idx = len-1: idx--;
						break;
						case false :
							idx = idx_force;
					}

					if ($(i_frame).length) {
						var frame = i_frame.cloneNode(false);
						frame.src = trans[idx][0];
						i_frame.parentNode.replaceChild(frame, i_frame);
						
					} else {
						$('<iframe>', {
							src: trans[idx][0],
							id:  'ifrm',
							frameborder: 0
						}).appendTo('#iframediv');
					}
					if (!idx_force && !firstLoad) {
						if (idx > 0) {
							history.pushState(null, null, spathName+'?w=' + idx);
						}
						if (idx == 0) {
							history.pushState(null, null, spathName);
						}
					}
					firstLoad = false;

				    document.title = trans[idx][1];

					if (idx > 0) $('#back_link').show(); else $('#back_link').hide();

					return false;
			}
			// ]]>
		</script>
	</head>
	<body>
		<header id="header">
			<a style="display:none;" id="back_link" href="/" onclick="return loadRemoteFrame('ifrm', 'rev')"></a>
			<a id="forward_link" href="/" onclick="return loadRemoteFrame('ifrm', 'frv')"></a>
		</header>
		<div id="iframediv">

		</div>
	</body>
</html>