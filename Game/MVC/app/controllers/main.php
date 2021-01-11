<?php

class Main extends Controller
{
	public function index($id)
	{
		include('../../Config/db_connect.php');

		// Make SQL
		$sql = "SELECT * FROM existing_characters WHERE id = $id";

		// Get the query result
		$result = mysqli_query($conn, $sql);
		$character;

		if (mysqli_query($conn, $sql))
		{
			// Fetch result in array format
			$character = mysqli_fetch_assoc($result);
		}
		else
		{
			echo 'Query error: ' . mysqli_error($conn);
		}

		if (isset($_POST['delete']))
		{
			$id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);

			$sql = "DELETE FROM existing_characters WHERE id = $id_to_delete";

			if (mysqli_query($conn, $sql))
			{
				header('Location: index');
			}
			{
				echo 'Query error: ' . mysqli_error($conn);
			}


			if (isset($_GET['id']))
			{
				//$id = mysqli_real_escape_string($conn, $_GET['id']);
				$idRaw = "";

				// Write query for all characters
				$sql = 'SELECT * FROM existing_characters';

				// Make query and get result
				$result = mysqli_query($conn, $sql);

				// Fetch the resulting rows as an array
				$characters = mysqli_fetch_all($result, MYSQLI_ASSOC);

				// Free result from memory
				mysqli_free_result($result);

				foreach ($characters as $character) 
				{
					if ($character['id'] == $id)
					{
						$idRaw = $character['id'];
					}
				}
				// Make SQL
				$sql = "SELECT * FROM existing_characters WHERE id = $idRaw";

				// Get the query result
				$result = mysqli_query($conn, $sql);

				// Fetch result in array format
				$character = mysqli_fetch_assoc($result);

				// Free result from memory
				mysqli_free_result($result);

				// Close connection
				mysqli_close($conn);
			}
		}
		// End of POST check
		$this->view('home/main', ['character' => $character]);	
	}
}