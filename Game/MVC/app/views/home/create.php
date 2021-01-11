
<!DOCTYPE html>
<html>

<?php include('../../Templates/header.php') ?>

<section class="container grey-text">
	<h4 class="center">Create New Character</h4>
	<form class="white" action="create" method="POST">
		<label>Character Name: </label>
		<input type="text" name="characterName" value="<?php echo htmlspecialchars($data['characterName'])?>">
		<div class="red-text"><?php echo $data['errors']['characterName']; ?></div>
		<label>Password: </label>
		<input type="password" name="password" value="<?php echo htmlspecialchars($data['password'])?>">
		<div class="red-text"><?php echo $data['errors']['password']; ?></div>
		<label>Confirm Password: </label>
		<input type="password" name="confirm" value="<?php echo htmlspecialchars($data['confirm'])?>">
		<div class="red-text"><?php echo $data['errors']['confirm']; ?></div>
		<div class="center">
			<input type="submit" name="submit" value="create", class="btn brand z-depth-0">
		</div>
	</form>


<?php include('../../Templates/footer.php') ?>


</html>