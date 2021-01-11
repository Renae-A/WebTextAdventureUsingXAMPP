<?php

class Create extends Controller
{
	public function index()
	{

		include('../../Config/db_connect.php');

		// Write query for all characters
		$sql = 'SELECT `name` FROM existing_characters';

		// Make query and get result
		$result = mysqli_query($conn, $sql);

		// Fetch the resulting rows as an array
		$characters = mysqli_fetch_all($result, MYSQLI_ASSOC);

		$characterName = $password = $confirm = "";
		$errors = array('characterName' => '', 'password' => '', 'confirm' => '');

		
		// If the submit button has been pressed
		if(isset($_POST['submit']))
		{
			// Check character name is not empty and is valid
			if (empty($_POST['characterName']))
			{
				$errors['characterName'] = "A character name is required. <br />";
			}
			else
			{
				$characterName = $_POST['characterName'];
				if (!preg_match('/^[a-zA-Z\s]{2,15}+$/', $characterName))
				{
					$errors['characterName'] = "A valid character name is required. Must be between 2 to 15 character long and not contain numbers or special characters. <br />";
				}

				foreach ($characters as $character) 
				{
					if ($character['name'] == $_POST['characterName'])
					{
						$errors['characterName'] = "This character name already exists. <br />";
					}
				}
			}
			// Check password is not empty and is valid
			if (empty($_POST['password']))
			{
				$errors['password'] = "A password is required. <br />";
			}
			else
			{
				$password = $_POST['password'];
				if (!preg_match(('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,}$/'), $password))
				{
					$errors['password'] = "A valid password is required. Must be at least 8 characters long, include uppercase and lowercase letters, numbers, special characters and no spaces. <br />";
				}
			}
			// Check password confirmation is not empty and is valid
			if (empty($_POST['confirm']))
			{
				$errors['confirm'] = "Password confirmation is required. <br />";
			}
			else
			{
				$confirm = $_POST['confirm'];
				if ($confirm != $password)
				{
					$errors['confirm'] = "Password entered does not match previous field. <br />";
				}
			}
		
			// Check if there are any errors
			if (array_filter($errors))
			{
				// There are errors in the form.
			}
			else
			{
				$characterName = mysqli_real_escape_string($conn, $_POST['characterName']);
				$password = mysqli_real_escape_string($conn, $_POST['password']);
				$password = password_hash($password, PASSWORD_DEFAULT);

				// Create SQL
				$sql = "INSERT INTO existing_characters(`name`, `password`) VALUES('$characterName', '$password')";

				// Save to database and check
				if (mysqli_query($conn, $sql))
				{
					// Write query for all characters
					$sql = 'SELECT `id` FROM existing_characters';

					// Make query and get result
					$result = mysqli_query($conn, $sql);

					// Fetch the resulting rows as an array
					$characters = mysqli_fetch_all($result, MYSQLI_ASSOC);

					// Free result from memory
					mysqli_free_result($result);

					// Close connection
					mysqli_close($conn);

					// Setup the new singular character array by retreiving the last character created
					$characterDetails = array_pop($characters);
					//$characterID = password_hash($characterDetails['id'], PASSWORD_DEFAULT);

					// Success
					header('Location: main/'.$characterDetails['id']);
				}
				else
				{
					echo 'Query error: ' . mysqli_error($conn);
				}
			}
		}
		// End of POST check
		$this->view('home/create', ['characterName' => $characterName, 'password' => $password, 'confirm' => $confirm, 'errors' => $errors]);	
	}
}