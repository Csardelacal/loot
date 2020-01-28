
<div class="spacer medium"></div>

<div class="row l1">
	<div class="span l1">
		<div class="row l5 ng">
			<div class="span l4">
				<h1 style="margin: 0">Rewards / Incentives</h1>
			</div>
			<div class="span l1 align-right">
				<div class="spacer minuscule"></div>
				<a class="button borderless" href="<?= url('reward', 'edit') ?>">+ Create reward</a>
			</div>
		</div>
		
		<div class="spacer small"></div>
		
		<div class="material unpadded">
			<div class="spacer small"></div>
			<?php foreach ($rewards as $reward) : ?>
			<div class="padded a-little">
				<div class="row l9">
					<!-- Add an icon to make the list feel more hierarchical -->
					<div class="span l7">
						<div><a class="text:grey-100 no-decoration" href="<?= url('reward', 'edit', $reward->_id) ?>"><strong><?= __($reward->activityName) ?></strong></a></div>
						<div class="spacer smaller"></div>
						<div>
							<span class="text:grey-500"><?= __($reward->awardTo == RewardModel::AWARDTO_SOURCE? 'Source' : 'Target') ?> receives</span> 
							<strong class="text:grey-100"><?= __($reward->score) ?> &times; <?= __($reward->perValue? 'Value' : 'Amount') ?></strong>
						</div>
					</div>
					<div class="span l2 align-right">
						<div class="spacer small"></div>
						<a class="button" href="<?= url('reward', 'edit', $reward->_id) ?>">Edit</a>
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