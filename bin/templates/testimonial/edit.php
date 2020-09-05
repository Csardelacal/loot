

<div class="spacer large"></div>

<div class="row l1">
	<div class="span l1">
			<div class="spacer large"></div>
			<h2>Edit your review.</h2>

			<p>
				You can use this form to edit the review you're giving to the seller.
			</p>

			<div class="spacer small"></div>
			
			<form method="POST" action="">
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

									<textarea class="frm-ctrl" name="body" id="response-body" placeholder="Your experience..."><?= __($testimonial->body) ?></textarea>

									<div class="spacer small"></div>
									
									<label>
										<input type="checkbox" class="frm-ctrl" name="recommendation" <?= $testimonial->recommendation? 'checked' : '' ?>>
										<span class="frm-ctrl-chk"></span> Recommend this user
									</label>
									
								</div>
								<div class="span l1 align-right">
									<span class="text:grey-800"><?= Time::relative($testimonial->created) ?></span>
								</div>
							</div>

							<div class="spacer medium"></div>
						</div>
					</div>
				</div>
				<div class="spacer small"></div>
				<div class="align-right">
					<span class="text:grey-500" id="character-counter">300</span>
					<input type="submit" class="button">
				</div>
			</form>
			
			<div class="spacer huge"></div>
			<div class="spacer large"></div>
	</div>
</div>

<script type="text/javascript">
(function() {
	var counter = document.getElementById('character-counter');
	var textarea = document.getElementById('response-body');
	
	var cb = function () {
		counter.innerHTML = parseInt(300 - textarea.value.length);
	};
	
	textarea.addEventListener('input', cb);
	cb();
}());
</script>