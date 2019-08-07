<!DOCTYPE html>
<html>
	<head>
		<title><?= isset(${'page.title'}) && ${'page.title'}? ${'page.title'} : 'LOOT - User reputation' ?></title>
		<meta name="twitter:card"  content="summary_large_image">
		<meta name="og:title" value="<?= isset(${'page.title'}) && ${'page.title'}? ${'page.title'} : 'Commishes portfolio' ?>">
		<meta name="twitter:title" value="<?= isset(${'page.title'}) && ${'page.title'}? ${'page.title'} : 'Commishes portfolio' ?>">
		<meta name="twitter:site" value="@commishes">
		
		<!-- Meta viewport for the mobile and tablet users-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="_scss" content="<?= \spitfire\SpitFire::baseUrl() ?>/assets/scss/_/js/">
		
		<?php if (isset(${'robots.index'}) && !${'robots.index'}): ?> 
		<!--Robots crawling settings -->
		<meta name="robots" content="noindex">
		<?php endif; ?> 
		
		<?php if (isset(${'page.description'}) && ${'page.description'}): ?> 
		<!--The page's description -->
		<meta name="description" content="<?= __(str_replace(["\n", "\r"], '', ${'page.description'}), 175) ?>">
		<meta name="og:description" content="<?= __(str_replace(["\n", "\r"], '', ${'page.description'}), 175) ?>">
		<meta name="twitter:description" content="<?= __(str_replace(["\n", "\r"], '', ${'page.description'}), 140) ?>">
		<?php endif; ?> 
		
		<?php if (isset(${'page.image'}) && ${'page.image'}): ?> 
		<!--The page's image. Please make sure that this contains only URL escaped characters -->
		<meta name="og:image" content="<?= ${'page.image'} ?>">
		<meta name="twitter:image" content="<?= ${'page.image'} ?>">
		<?php endif; ?> 
		
		<link rel="stylesheet" type="text/css" href="<?= spitfire\core\http\URL::asset('css/app.css') ?>">
		
		<!-- Include the dependency injector -->
		<script type="text/javascript" src="<?= spitfire\core\http\URL::asset('js/depend.js') ?>"></script>
		
		<script type="text/javascript">
			window.baseURL = '<?= url() ?>';
			window.depend.setBaseURL(window.baseURL + 'assets/js/');
		</script>
		
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-63756475-2', 'auto');
			ga('send', 'pageview');

		 </script>
		 
		
		<script src="<?= spitfire\core\http\URL::asset('js/m3/depend.js') ?>" type="text/javascript"></script>
		<script src="<?= spitfire\core\http\URL::asset('js/m3/depend/router.js') ?>" type="text/javascript"></script>
		
		<script type="text/javascript">
		(function () {
			depend(['m3/depend/router'], function(router) {
				router.all().to(function(e) { return '<?= \spitfire\SpitFire::baseUrl() . '/assets/js/' ?>' + e + '.js'; });
				router.equals('_scss').to( function() { return '<?= \spitfire\SpitFire::baseUrl() ?>/assets/scss/_/js/_.scss.js'; });
				
				
				var location = document.querySelector('meta[name="_scss"]').getAttribute('content') || '/assets/scss/_/js/';

				router.startsWith('_scss/').to(function(str) {
					return location + str.substring(6) + '.js';
				});
			});
		}());
		</script>
	</head>
	<body>
		
		<div class="navbar">
			<div class="left">
				<span class="toggle-button dark"></span>
				<a href="<?= url() ?>">
					<img src="<?= spitfire\core\http\URL::asset('img/logo.png') ?>" width="17" style="margin-right: 5px; vertical-align: -3px"> 
				</a>
			</div>
			<div class="right">
				<?php if(isset($authUser) && $authUser): ?>
					<span class="h-spacer"></span>
					<a class="menu-item not-mobile" href="<?= $ping->getURL() ?>/user/authorize/<?= $authToken->getId() ?>?returnto=/feed">
						<span class="badge" data-ping-counter="0">0</span>
					</a>
					<span class="h-spacer"></span>
					<a class="menu-item not-mobile" href="<?= url('user', $authUser->username) ?>">
						<img src="<?= $authUser->avatar ?>" height="20"  style="margin-right: 5px; vertical-align: -5px">
						<span class="not-mobile"><?= $authUser->username ?></span>
					</a>
					<span class="h-spacer"></span>
					<div class="has-dropdown" style="display: inline-block">
						<span class="app-switcher toggle" data-toggle="app-drawer"></span>
						<div class="dropdown right-bound unpadded" data-dropdown="app-drawer">
							<div class="app-drawer" id="app-drawer"></div>
						</div>
					</div>
					<span class="h-spacer"></span>
				<?php else: ?>
					<a class="menu-item" href="<?= url('account', 'login', Array('returnto' => $_SERVER['REQUEST_URI'])) ?>">Login</a>
				<?php endif; ?>
			</div>
		</div>
		
		<!--Sidebar -->
		<div class="contains-sidebar collapsed">
			<div class="sidebar">
				<div class="navbar">
					<div class="left">
						<a href="<?= url() ?>">
							<img src="<?= spitfire\core\http\URL::asset('img/logo.png') ?>" width="17" style="margin-right: 5px; vertical-align: -3px"> 
							<span class="not-mobile">Loot</span>
						</a>
					</div>
				</div>

				<?php if(isset($authUser) && $authUser): ?>
				<div class="menu-title"> Loot</div>
				<div class="menu-entry"><a href="<?= url() ?>">Overview</a></div>

				<div class="menu-title"> Settings</div>
				<div class="menu-entry"><a href="<?= url('settings') ?>">Settings</a></div>
				<?php else: ?>
				<div class="menu-title"> Account</div>
				<div class="menu-entry"><a href="<?= url('user', 'login') ?>"   >Login</a></div>
				<?php endif; ?>
				
				<?php if(isset($authUser) && $authUser && isset($privileged) && $privileged): ?>
				<div class="menu-title"> Administration</div>
				<div class="menu-entry"><a href="<?= url('quest') ?>">Quests</a></div>
				<div class="menu-entry"><a href="<?= url('reward') ?>">Rewards</a></div>
				<?php endif; ?>
			</div>
		</div>
		
		<div class="auto-extend" data-sticky-context>
			<?= $this->content() ?>
		</div>
		
		<footer>
			<div class="row l2">
				<div class="span l1">
					Commishes.Portfolio &copy; <?= date('Y') ?> - All rights reserved
				</div>
				<div class="span l1" style="text-align: right">
					
				</div>
			</div>
		</footer>
		<?php if( $authUser ): ?>
		<script src="<?= $ping->getURL() ?>/feed/counter.js?token=<?= $authToken->getTokenInfo()->token ?>"></script>
		<?php endif; ?>
		
		
		<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			var ae = document.querySelector('.auto-extend');
			var wh = window.innerheight || document.documentElement.clientHeight;
			var dh = document.body.clientHeight;
			
			ae.style.minHeight = Math.max(ae.clientHeight + (wh - dh), 0) + 'px';
		});
		</script>
		<script type="text/javascript">
		(function () {
			depend(['ui/dropdown'], function (dropdown) {
				dropdown('.app-switcher');
			});
			
			depend(['phpas/app/drawer'], function (drawer) {
				console.log(drawer);
			});
			
			depend(['_scss'], function() {
				console.log('Loaded _scss');
			});
			
			setTimeout(function () {
				document.body.appendChild(document.createElement('script')).src="<?= url('cron') ?>";
			}, 800);
		}());
		</script>
		
		<script type="text/javascript">
			depend(['sticky'], function (sticky) {
				
				/*
				 * Create elements for all the elements defined via HTML
				 */
				var els = document.querySelectorAll('*[data-sticky]');

				for (var i = 0; i < els.length; i++) {
					sticky.stick(els[i], sticky.context(els[i]), els[i].getAttribute('data-sticky'));
				}
			});
		</script>
	</body>
</html>