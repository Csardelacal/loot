
<div class="spacer medium"></div>

<div class="row l1">
	<div class="span l1">
		<div class="row l5 ng">
			<div class="span l4">
				<h1 style="margin: 0">Quests</h1>
			</div>
			<div class="span l1 align-right">
				<div class="spacer minuscule"></div>
				<a class="button borderless" href="<?= url('quest', 'edit') ?>">+ Create quest</a>
			</div>
		</div>
		
		<div class="spacer small"></div>
		
		<div class="material unpadded">
			<div class="spacer small"></div>
			<?php foreach ($quests as $quest) : ?>
			<div class="padded a-little">
				<div class="row l4">
					<div class="span l3">
						<div><a class="text:grey-100 no-decoration" href="<?= url('quest', 'edit', $quest->_id) ?>"><strong><?= __($quest->name) ?></strong></a></div>
						<div class="spacer smaller"></div>
						<div class="text:grey-500"><?= __($quest->description?? 'No description provided') ?></div>
						<div class="spacer smaller"></div>
						<div>
							<strong><?= __($quest->threshold) ?></strong> <span class="text:grey-500"><?= __($quest->activityName) ?></span>
							<div class="horizontal-spacer medium"></div>
							<span class="text:grey-500">Expires </span><strong><?= Time::relative($quest->ttl, 0) ?></strong>
						</div>
					</div>
					<div class="span l1 align-right">
						<div class="spacer small"></div>
						<a class="button" href="<?= url('quest', 'edit', $quest->_id) ?>">Edit</a>
					</div>
				</div>
			</div>
			<div class="spacer small"></div>
			<div class="separator"></div>
			<div class="spacer small"></div>
			<?php endforeach; ?>
			<div class="spacer small"></div>
			
			<div class="align-center">
				<?= $pages ?>
			</div>
			
			<div class="spacer small"></div>
		</div>
	</div>
</div>