
<div class="spacer" style="height: 20px"></div>

<div class="row l1">
	<div class="span l1">
		<div class="material">
			<div class="row l5">
				<div class="span l1">
					<h2 style="margin: 0">Your history</h2>
				</div>
				<div class="span l3">
					<canvas id="score-history" style="width: 100%; height: 60px"></canvas>
				</div>
				<div class="span l1 align-right" style="border-left: solid 1px #CCC">
					<span style="color: #ce8147; font-size: 3rem; font-weight: bold;">
						<?= \loot\ScoreFormatter::format($score) ?>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="spacer large"></div>


<div class="row l1">
	<div class="span l1">
		<div class="material" style="min-height: 10rem;">
			<div class="row l5">
				<div class="span l1">
					<h2 style="margin: 0">Your badges</h2>
				</div>
				<div class="span l4">
					<?php foreach ($badges as $badge): ?>
					<div>
						<div class="badge <?= $badge->quest->color ?>">
							<?php if ($badge->quest->icon): ?>
							<?php else: ?>
								<span class="icon-placeholder"></span>
							<?php endif; ?>
							<a class="caption" href="<?= url('quest', 'detail', $badge->quest->_id) ?>"><?= __($badge->quest->name) ?></a>
						</div>
						
						<span style="display: inline-block; width: 10px"></span>
						<span class="text:grey-700"><?= __($badge->quest->description, 140) ?></span>
						
						<div class="spacer small"></div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="spacer large"></div>

<?php foreach ($testimonials as $testimonial): ?>
<div class="row l1">
	<div class="span l1">
		<div class="material" style="min-height: 10rem;">
			<div class="row l6">
				<div class="span l1 align-center">
					<?php $seller = $sso->getUser($testimonial->user->_id) ?>
					<?php if ($testimonial->client): ?>
					<?php $client = $sso->getUser($testimonial->client->_id) ?>
					<img src="<?= $client->getAvatar(256) ?>" style="width: 90%; border-radius: 50%" >
					<?php endif; ?>
				</div>
				<div class="span l5">
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
						<div class="span l1 align-right desktop-only">
							<div class="spacer small"></div>
							<span class="text:grey-800"><?= Time::relative($testimonial->created) ?></span>
						</div>
					</div>
					
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
			<?php else : ?>
			<div class="align-center">
				<a class="button small outline" href="<?= url('testimonial', 'reply', $testimonial->_id) ?>">Reply to this testimonial</a>
			</div>
			<?php endif; ?>

			<div class="spacer medium"></div>
		</div>
	</div>
</div>

<div class="spacer medium"></div>
<?php endforeach; ?>

<div class="row l1">
	<div class="span l1 align-right">
		<?php $profile = $sso->getUser($authUser->id); ?>
		<a href="<?= url('testimonial', 'on', $profile->getUsername()) ?>" class="text:grey-400" style="text-decoration: none">More testimonials</a>
	</div>
</div>

<div class="spacer large"></div>

<div class="row l1">
	<div class="span l1">
		<div class="material" style="min-height: 10rem;">
			<div class="row l5">
				<div class="span l1">
					<h2 style="margin: 0">Recent history</h2>
				</div>
				<div class="span l4">
					<?php foreach ($recent as $change): ?>
					<div class="row l4 ng">
						<div class="span l3">
							<?php if ($change->score >= 0): ?>
							<span style="color: #090">+<?= $change->score ?></span>
							<?php else: ?>
							<span style="color: #900">-<?= abs($change->score) ?></span>
							<?php endif; ?>

							<span style="display: inline-block; width: 7px"></span>
							<span class="text:grey-500"><?= __($change->interaction->src? $sso->getUser($change->interaction->src->_id)->getUsername() : "A guest") ?></span>
							<span class="text:grey-400"><?= __($change->interaction->caption) ?></span>
						</div>
						<div class="span l1 align-right">
							<span class="text:grey-800"><?= Time::relative($change->created) ?></span>
						</div>
					</div>
						
					<div class="spacer medium"></div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$graph = [];
for ($i = 0; $i < 30; $i++) {
	$time = time() - $i * 86400;
	$graph[] = HistoryModel::snapshot($user, $time);
}
?>
<script type="text/javascript">
	depend(['graph'], function (graph) {
		graph(<?= json_encode(array_reverse($graph)) ?>, document.getElementById('score-history'));
	});
</script>