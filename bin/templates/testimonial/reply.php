

<div class="spacer large"></div>

<div class="row l1">
	<div class="span l1">
			<div class="spacer large"></div>
			<h2>Replying to this testimonial</h2>

			<p>
				On this page you can respond to the testimonial the customer has 
				given about the experience with you.
			</p>

			<div class="spacer small"></div>
			
			<div class="material" style="min-height: 10rem;">
				<div class="row l5">
					<div class="span l1">
						<?php if ($testimonial->client): ?>
						<?php $client = $sso->getUser($testimonial->client->_id) ?>
						<img src="<?= $client->getAvatar(256) ?>" style="width: 70%; border-radius: 50%" >
						<?php endif; ?>
					</div>
					<div class="span l4">
						<div class="row l4 ng">
							<div class="span l3">
								<div class="spacer small"></div>
								<p style="font-size: 1.2rem; margin: 0" class="text:grey-500">
									<?= __($testimonial->body) ?>
								</p>

								<div class="spacer small"></div>
								<?php if ($testimonial->recommendation && $testimonial->client): ?>
								<span style="color: #090"><?= $client->getUserName() ?></span>
								<?php elseif ($testimonial->recommendation): ?>
								<span style="color: #090">Client</span>
								<?php else: ?>
								<span style="color: #900">Client</span>
								<?php endif; ?>
								<span class="text:grey-800">on</span>
								<span class="text:grey-500"><?= Strings::strToHTML($testimonial->product) ?></span>

								<div class="spacer large"></div>
								
							</div>
							<div class="span l1 align-right">
								<div class="spacer small"></div>
								<span class="text:grey-800"><?= Time::relative($testimonial->created) ?></span>
							</div>
						</div>
						
						<div class="row s7 ng-lr">
							<div class="span s1">
								<img src="<?= $authUser->avatar ?>" style="width: 70%; border-radius: 50%" >
							</div>
							<div class="span s6">
								<form method="POST" action="">
									<textarea class="frm-ctrl" name="body" id="response-body" placeholder="Your response..."></textarea>
									<div class="spacer small"></div>
									<div class="align-right">
										<span class="text:grey-500" id="character-counter">300</span>
										<input type="submit" class="button">
									</div>
								</form>
							</div>
						</div>

						<div class="spacer medium"></div>
					</div>
				</div>
			</div>
			
			<div class="spacer huge"></div>
			
			<h2>Replying to feedback from customers</h2>
			
			<div class="row l3">
				<div class="span l2">
					<div class="text:grey-300">
						<?php if ($testimonial->recommendation): ?>
						<p>
							The customer gave you a positive rating, this offers you a great chance
							to use your response to thank the customer for their business.
						</p>
						<?php else: ?>
						<p>
							The customer indicated that they would be unlikely to recommend you for
							later business. This happens, everything will be okay. Just try to follow
							these steps:
						</p>
						<div class="spacer small"></div>
						<ul>
							<li>Apologize for the bad experience.</li>
							<li>Do not shift blame onto the customer.</li>
							<li>Do not argue with them.</li>
						</ul>
						<div class="spacer small"></div>
						<p>
							This is not the place to fight with the customer that was unhappy,
							it is your chance to show potential new customers that you are pleasant
							to work with and will do your best to resolve negative situations.
						</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="span l1">
					<div class="material less-padded">
						<div>
							<strong>
								<span class="text:red-300">Tip! </span>
								<span class="text:grey-300">Always deescalate</span>
							</strong>
						</div>
						<div>
							<p class="text:grey-500">
								Even if a customer was unfair and left a scolding review out
								of spite, do not engage them. Be nice, apologize and move to
								greener pastures.
							</p>
						</div>
					</div>
				</div>
			</div>
			
			<div class="spacer large"></div>
			<div class="spacer large"></div>
	</div>
</div>

<script type="text/javascript">
(function() {
	var counter = document.getElementById('character-counter');
	var textarea = document.getElementById('response-body');
	
	textarea.addEventListener('input', function () {
		counter.innerHTML = parseInt(300 - this.value.length);
	});
}());
</script>