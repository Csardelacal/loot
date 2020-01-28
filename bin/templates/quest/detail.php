

<div class="spacer large"></div>


<div class="row l1">
	<div class="span l1">
		<div class="material" style="min-height: 10rem;">
			
			<div class="row l3">
				<div class="span l1">
					<div class="align-right">
						<div class="badge <?= $badge->color ?>">
							<?php if ($badge->icon): ?>
							<?php else: ?>
								<span class="icon-placeholder"></span>
							<?php endif; ?>
							<a class="caption" href="<?= url('quest', 'detail', $badge->_id) ?>"><?= __($badge->name) ?></a>
						</div>
					</div>
				</div>
				
				<div class="span l2">
					<div class="spacer minuscule"></div>
					<div>
						<span class="text:grey-700"><?= __($badge->description) ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="spacer large"></div>