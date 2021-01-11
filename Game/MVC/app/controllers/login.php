<?php

class Login extends Controller
{
	public function index()
	{
		include('../../Config/db_connect.php');

		// Write query for all characters
		$sql = 'SELECT * FROM existing_characters';

		// Make query and get result
		$result = mysqli_query($conn, $sql);

		// Fetch the resulting rows as an array
		$characters = mysqli_fetch_all($result, MYSQLI_ASSOC);

		$characterName = $password = "";
		$errors = array('characterName' => '', 'password' => '');
		$indexNum = 0;

		$characterArrayVerified = [];

		// If the submit button has been pressed
		if(isset($_POST['login']))
		{
			// Check character name is not empty and is valid
			if (empty($_POST['characterName']))
			{
				$errors['characterName'] = "A character name is required. <br />";
			}
			else
			{
				$characterName = $_POST['characterName'];
				foreach ($characters as $character) 
				{
					$errors['characterName'] = "Character does not exist. <br />";
					$indexNum++;
					if ($characterName != $character['name'])
					{
						continue;
					}
					else
					{
						$errors['characterName'] = "";
						$characterArrayVerified = $character;
						break;
					}
				}
			}
			// Check password is valid
			if ($characterArrayVerified != "")
			{
				$password = $_POST['password'];
				if(!password_verify($password, $character['password']))
				{
					$errors['password'] = "Password is not correct.";
				}
			}


			// Check if there are any errors
			if (array_filter($errors))
			{
				//There are errors in the form.
			}
			else
			{
				// Free result from memory
				mysqli_free_result($result);

				// Close connection
				mysqli_close($conn);


				// Setup the new singular character array by retreiving the last character created
				$characterDetails = $characters[$indexNum-1];
				//$characterID = password_hash($characterDetails['id'], PASSWORD_DEFAULT);


				// Success
				header('Location: main/'.$characterDetails['id']);
			}
		}// End of POST check
		$this->view('home/login', ['characterName' => $characterName, 'password' => $password, 'errors' => $errors]);	
	}
}
