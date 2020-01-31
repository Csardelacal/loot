

<div class="spacer large"></div>

<?php foreach ($testimonials as $testimonial): ?>
<div class="row l1">
	<div class="span l1">
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
							<?php if ($testimonial->recommendation  && $testimonial->client): ?>
							<span style="color: #090"><?= $client->getUserName() ?></span>
							<?php elseif ($testimonial->recommendation): ?>
							<span style="color: #090">Client</span>
							<?php else: ?>
							<span style="color: #900">Client</span>
							<?php endif; ?>
							<span class="text:grey-800">on</span>
							<span class="text:grey-500"><?= Strings::strToHTML($testimonial->product) ?></span>
						</div>
						<div class="span l1 align-right">
							<div class="spacer small"></div>
							<span class="text:grey-800"><?= Time::relative($testimonial->created) ?></span>
						</div>
					</div>
						
					<div class="spacer medium"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="spacer medium"></div>
<?php endforeach; ?>

<div class="row l1">
	<div class="span l1 align-center">
		<?= $pages ?>
	</div>
</div>

<div class="spacer large"></div>
