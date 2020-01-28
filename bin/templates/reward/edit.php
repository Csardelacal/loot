
<div class="spacer small"></div>

<?php if ($messages?? false): ?>
<div class="row">
	<div class="span">
		<div class="message error">
			<span class="discard-message"></span>
			<ul><?php echo implode('', $messages); ?></ul>
		</div>
	</div>
</div>
<?php endif; ?>

<form method="POST" action="">
	<div class="row l1">
		<div class="span l1">
			
			<div class="row l5 ng">
				<div class="span l4">
					<h1 style="margin: 0">Reward</h1>
				</div>
			</div>
			
			<div class="spacer small"></div>
			
			<div class="material">
				<div class="row l3 ng-lr">
					<div class="span l2">
						<label class="frm-lbl">Activity identifier</label>
						<input class="frm-ctrl" name="activity" type="text" value="<?= $reward? $reward->activityName : '' ?>">
					</div>
					<div class="span l1">
						<label class="frm-lbl">Score</label>
						<input class="frm-ctrl" type="text" name="score" value="<?= $reward? $reward->score : '' ?>">
					</div>
				</div>
				<div class="row l2 ng-lr">
					<div class="span l1">
						<label class="frm-lbl">Award to</label>
						<select class="frm-ctrl" name="awardTo">
							<option value="<?= RewardModel::AWARDTO_SOURCE ?>" <?= $reward && $reward->awardTo == RewardModel::AWARDTO_SOURCE? 'selected' : '' ?> >Origin</option>
							<option value="<?= RewardModel::AWARDTO_TARGET ?>" <?= $reward && $reward->awardTo == RewardModel::AWARDTO_TARGET? 'selected' : '' ?> >Target</option>
						</select>
					</div>
					<div class="span l1">
						<label class="frm-lbl">Measure</label>
						<select class="frm-ctrl" name="value">
							<option value="true" <?= $reward && $reward->perValue? 'selected' : '' ?> >Value</option>
							<option value="false" <?= $reward && !$reward->perValue? 'selected' : '' ?> >Count</option>
						</select>
					</div>
				</div>

				<div class="spacer" style="height: 25px"></div>

				<div class="align-right">
					<input type="submit" class="button" value="Store">
				</div>
						
			</div>
		</div>
	</div>
</form>