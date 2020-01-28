
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
			
			<h2>General settings</h2>
			<div class="material">
				<div class="row l4">
					<div class="span l1">
					</div>
					<div class="span l3">
						<label class="frm-lbl" for="name">Name</label>
						<input type="text" class="frm-ctrl" name="name" id="name" placeholder="Name of the quest" value="<?= __($record? $record->name : '') ?>">
						
						<label class="frm-lbl" for="name">Description</label>
						<textarea name="description" id="description" class="frm-ctrl" placeholder="A basic description"><?= __($record? $record->description : '') ?></textarea>
						
						<label class="frm-lbl">Color</label>
						<input type="hidden" name="color" id="input-color" value="<?= __($record? $record->color : '') ?>">
						<div class="row l5 ng-lr">
							<?php $colors = ['bronze', 'silver', 'gold', 'green', 'red']; ?>
							<?php foreach ($colors as $color): ?>
							<div class="span l1">
								<div class="badge selectable fluid <?= $color ?> <?= __($record && $record->color == $color? 'selected' : '') ?>" data-value="<?= $color ?>">
									<span class="icon-placeholder"></span>
									<span class="badge-body"><?= $color ?></span>
								</div>
							</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="spacer small"></div>
			
			<h2>Technical settings</h2>
			<div class="material">
				<div class="row l4">
					<div class="span l1">
					</div>
					<div class="span l3">
						<label class="frm-lbl" for="identifier">Listen for the following event</label>
						<div class="frm-ctrl-grp">
							<input type="text" class="frm-ctrl" name="activityName" id="identifier" placeholder="Identifier (for matching events from third party websites)" value="<?= __($record? $record->activityName : '') ?>">
							<input type="text" class="frm-ctrl fixed-width narrow" name="threshold" id="amt" placeholder="Amount" value="<?= __($record? $record->threshold : '') ?>">
						</div>
						
						<label class="frm-lbl" for="name">Expire after</label>
						<select class="frm-ctrl" name="ttl">
							<option value="86400">One day</option>
							<option value="604800">One week</option>
							<option value="2592000">One Month</option>
							<option value="7776000">Three Months</option>
							<option value="15552000">Six Months</option>
							<option value="31536000">One year</option>
							<option value="63072000">Two years</option>
						</select>
						
						<div class="spacer small"></div>
						
						<label class="frm-lbl">
							<input class="frm-ctrl" name="perValue" type="checkbox" <?= $record && $record->perValue? 'checked' : '' ?>>
							<span class="frm-ctrl-chk toggle"></span>
							Grant reward for the interaction value rather than count 
						</label>
						
						<label class="frm-lbl">
							<input class="frm-ctrl" name="birthRight" type="checkbox" <?= $record && $record->birthRight? 'checked' : '' ?>>
							<span class="frm-ctrl-chk"></span>
							Award automatically at registration
						</label>
						
					</div>
				</div>
			</div>
						
			<div class="spacer large"></div>
			
			<div class="row l1">
				<div class="span l1 align-right">
					<input type="submit" class="button" value="Store">
				</div>
			</div>
			
			<div class="spacer large"></div>
		</div>
	</div>
</form>

<script type="text/javascript">
(function () {
	/**
	 * This should be moved to a generic mechanism inside _SCSS.
	 * 
	 * @type NodeList
	 */
	var badges = document.querySelectorAll('.badge.selectable');
	
	for (var i = 0; i < badges.length; i++) {
		badges[i].addEventListener('click', function (e) {
			
			for (var j = 0; j < badges.length; j++) {
				badges[j].classList.remove('selected');
			}
			this.classList.add('selected');
			document.getElementById('input-color').value = this.getAttribute('data-value');
		}, false);
	}
}());
</script>
	