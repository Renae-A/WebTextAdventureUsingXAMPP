
<!DOCTYPE html>
<html>

<?php include('../../Templates/header.php') ?>

<h4 class="center grey-text">Text Adventure!</h4>
<div class="container">
	<div class="row">
		<div class="card z-depth-0">
			<div class="card-content center">
				<h6>
					<h6> <?php echo nl2br($data['situation']); ?> </h6>
					<ul class= "center">
						<li>
							<?php if ($data['new']): ?>
								<a href="<?php echo $data['characterID']; ?>/<?php echo $data['location'];?>/1" class="btn brand z-depth-0"> <?php echo $data['option1']; ?> </a>
								<a href="<?php echo $data['characterID']; ?>/<?php echo $data['location'];?>/2" class="btn brand z-depth-0"> <?php echo $data['option2']; ?> </a>
							<?php elseif (!$data['new'] && !$data['end']): ?>

								<a href="/Game/MVC/Public/game/<?php echo $data['characterID']; ?>/<?php echo $data['location'];?>/1" class="btn brand z-depth-0"><?php echo $data['option1']; ?></a>
								<a href="/Game/MVC/Public/game/<?php echo $data['characterID']; ?>/<?php echo $data['location'];?>/2" class="btn brand z-depth-0"><?php echo $data['option2']; ?></a>
							<?php else: ?>

								<a href="/Game/MVC/Public/game/<?php echo $data['characterID']; ?>" class="btn brand z-depth-0"><?php echo $data['option1']; ?></a>
								<a href="/Game/MVC/Public/" class="btn brand z-depth-0"><?php echo $data['option2']; ?></a>
							<?php endif; ?>
						</li>
					</div>
				</h6>
			</div>
		</div>
	</div>
</div>

<?php include('../../Templates/footer.php') ?>


</html>
