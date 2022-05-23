<?php
global $nl_logger;
$smoking_data = $nl_logger->get_chart_elements('smoking');
$craving_data = $nl_logger->get_chart_elements('craving');
$carving = get_option("logger_carving_form");
?>
<div class="smkNtgp-wrapper">
	<div class="smkNtgp-inner">
		<div class="smkNtgp-header">
			<h2>Smoking Notebook</h2>
			<?php //if(!empty($smoking_data) || !empty($craving_data)):?>
			<?php if($carving){ ?>
			<div class="toggleBtn-wrap">
				<input type="checkbox" id="switch" class="toggleBtn-input toggle_chart" />
				<label for="switch" class="toggleBtn-switch ">
					<span class="active cha_smk">Smoking</span>
					<span class="cha_cra">Carving</span>
				</label>
			</div>
			<?php } ?>
			<?php //endif; ?>
		</div>
		<?php //if(!empty($smoking_data) || !empty($craving_data)):?>
		<div class="smkNtgp-content">
			<div class="graph-wrapper">
				<div class="smoking_chart">
					<?php //if(!empty($smoking_data)):?>
					<div class="graph-wrapper-inner">
						<?php
							for( $i = 6; $i >= 0; $i-- )
							{
								$sdata = $nl_logger->get_chart_weeks(date( 'l', strtotime( "$i days ago" ) ),'smoking');
								?>
								<div class="graphBar-box">
									<span class="barval" style="bottom: <?= $sdata['percent']?$sdata['percent']*100:'0'; ?>%;"><?= $sdata['total']?$sdata['total'].' cigarette':''; ?><?= $nl_logger->plural($sdata['total']); ?></span>
									<div class="graphBar" style="transform: scaleY(<?= $sdata['percent']?$sdata['percent']:'0'; ?>);"></div>
									<span class="weekName"><?= date( 'D', strtotime( "$i days ago" ) ); ?></span>
								</div>
								<?php

							}
						?>

					</div>
				<?php //endif;?>
				</div>
				<div class="craving_chart" style="display:none;">
					<?php //if(!empty($craving_data)):?>
					<div class="graph-wrapper-inner">
						<?php
							for( $i = 6; $i >= 0; $i-- )
							{
								$cdata = $nl_logger->get_chart_weeks(date( 'l', strtotime( "$i days ago" ) ),'craving');
								?>
								<div class="graphBar-box">
									<span class="barval" style="bottom: <?= $cdata['percent']?$cdata['percent']*100:'0'; ?>%;"><?= $cdata['total']?$cdata['total'].' carving':''; ?><?= $nl_logger->plural($cdata['total']); ?></span>
									<div class="graphBar" style="transform: scaleY(<?= $cdata['percent']?$cdata['percent']:'0'; ?>);"></div>
									<span class="weekName"><?= date( 'D', strtotime( "$i days ago" ) ); ?></span>
								</div>
								<?php

							}
						?>
					</div>
					<?php //endif;?>
				</div>
			</div>
			<div class="graphDetails-wrapper">
				<div class="smoke_chart_stat">
					<?php //if(!empty($smoking_data)):?>
					<div class="graphDetails-inner">
						<div class="graphDetails-box">
							<h3 class="graphDetails-text blue">Weekly Total</h3>
							<h3 class="graphDetails-text dark"><?= isset($smoking_data['cigarettes'])?$smoking_data['cigarettes']:'-'; ?></h3>
						</div>
						<div class="graphDetails-box">
							<h3 class="graphDetails-text blue">Top Trigger</h3>
							<h3 class="graphDetails-text dark"><?= isset($smoking_data['triggers'])?array_search(max($smoking_data['triggers']), $smoking_data['triggers']):'-'; ?></h3>
						</div>
						<div class="graphDetails-box">
							<h3 class="graphDetails-text blue">Avg intensity</h3>
							<h3 class="graphDetails-text dark"><?= isset($smoking_data['intensity'])?array_search(max($smoking_data['intensity']), $smoking_data['intensity']):'-'; ?></h3>
						</div>
					</div>
					<?php //endif;?>
				</div>
				<?php if($carving){ ?>
				<div class="crav_chart_stat" style="display:none;">
					<?php //if(!empty($craving_data)):?>
					<div class="graphDetails-inner">
						<div class="graphDetails-box">
							<h3 class="graphDetails-text blue">Weekly Total</h3>
							<h3 class="graphDetails-text dark"><?= isset($craving_data['cigarettes'])?$craving_data['cigarettes']:'-'; ?></h3>
						</div>
						<div class="graphDetails-box">
							<h3 class="graphDetails-text blue">Top Trigger</h3>
							<h3 class="graphDetails-text dark"><?= isset($craving_data['triggers'])?array_search(max($craving_data['triggers']), $craving_data['triggers']):'-'; ?></h3>
						</div>
						<div class="graphDetails-box">
							<h3 class="graphDetails-text blue">Avg intensity</h3>
							<h3 class="graphDetails-text dark"><?= isset($craving_data['intensity'])?array_search(max($craving_data['intensity']), $craving_data['intensity']):'-'; ?></h3>
						</div>
					</div>
					<?php //endif;?>
				</div>
				<?php } ?>
			</div>
		</div>
		<div class="smkNtgp-foot">
			<a href="#" class="viewMore-btn">View More</a>
		</div>
		<?php //endif; ?>
	</div>
</div>