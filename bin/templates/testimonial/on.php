

<div class="spacer large"></div>

<?php foreach ($testimonials as $testimonial): ?>
<div class="row l1">
	<div class="span l1">
		<div class="material" style="min-height: 10rem;">
			<div class="row l6">
				<div class="span l1 align-center">
					<div class="spacer medium"></div>
					<?php $seller = $sso->getUser($testimonial->user->_id) ?>
					<?php if ($testimonial->client): ?>
					<?php $client = $sso->getUser($testimonial->client->_id) ?>
					<img src="<?= $client->getAvatar(256) ?>" style="width: 90%; border-radius: 50%" >
					<?php endif; ?>
				</div>
				<div class="span l5">
					<div class="row l5 ng">
						<div class="span l5">
							<div class="align-right">
								<?php if ($authUser && $testimonial->client->_id == $authUser->id): ?>
								<a class="button outline small button-color-grey-500" href="<?= url('testimonial', 'edit', $testimonial->_id) ?>">Edit</a>
								<div class="h-spacer" style="display: inline-block; width: 20px;"></div>
								<?php endif ?>
								<span class="text:grey-800"><?= Time::relative($testimonial->created) ?></span>
							</div>
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
						</div>
					</div>
						
					<div class="spacer medium"></div>
				</div>
			</div>
			
			<?php if ($testimonial->response): ?>
			<div class="row l6">
				<div class="span l1 align-center desktop-only">
					<img src="<?= $seller->getAvatar(256) ?>" style="width: 70%; border-radius: 50%" >
				</div>
				<div class="span l4">
					<div class="spacer small"></div>
					<div class="text:grey-300"><?= __($testimonial->response) ?></div>
					<div class="text:grey-500"><?= __($seller->getUsername()) ?></div>
				</div>
			</div>
			<?php elseif($authUser && $authUser->id == $testimonial->user->_id) : ?>
			<div class="align-center">
				<a class="button small outline" href="<?= url('testimonial', 'reply', $testimonial->_id) ?>">Reply to this testimonial</a>
			</div>
			<?php endif; ?>
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
