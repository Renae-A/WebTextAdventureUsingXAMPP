
<!DOCTYPE html>
<html>

<?php include('../../Templates/header.php') ?>

<h4 class="center grey-text">Text Adventure!</h4>
<div class="container">
	<div class="row">
		<div class="card z-depth-0">
			<div class="card-content center">
				<div class="container center">
				<?php if ($data['character']): ?>
					<h4><?php echo htmlspecialchars($data['character']['name']); ?></h4>
					<p>Created: <?php echo date($data['character']['created_at']); ?></p>
					<div class="card-content center">
							<a href="/Game/MVC/Public/game/<?php echo $data['character']['id']; ?>" class="btn brand z-depth-0">Play Game</a>	
	
									
					<!-- DELETE FORM -->
					<form action="main" method="POST">
						<input type="hidden" name="id_to_delete" value="<?php echo $data['character']['id'] ?>">
						<input type="submit" name="delete" value="Delete This Character" class="btn brand z-depth-0">
					</form>

					<?php else: ?>
						<h5>No such character exists!</h5>
					<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include('../../Templates/footer.php') ?>


</html>
