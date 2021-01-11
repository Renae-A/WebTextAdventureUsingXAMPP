<?php

class Game extends Controller
{
	public function index($id = '', $location = '', $choice = '')
	{
		$character = $this->LoadGame($id);

		$save = $this->GetSave($character);

		if ($save != [])
		{
			if ($save['location'] != 'endingCottage' && $save['location'] != 'endingTree' && $save['location'] != 'endingShack' && $save['location'] != 'endingLake' && $save['location'] != 'goodEndingForest' && $save['location'] != 'goodEndingLake')
			{
			
				$location = $save['location'];
			}
		}

		$location = $this->UpdateLocation($location, $choice);

	

	
		$this->SetUp($location, $id, $character);

		$this->SaveGame($character, $location);

	}

	public function LoadGame($id)
	{
		include('../../Config/db_connect.php');

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

		// Make SQL
		//$sql = "SELECT `id` FROM existing_characters WHERE id = $idRaw";

		return $character;
	}

	public function GetSave($character)
	{
		include('../../Config/db_connect.php');
		
		$idRaw = $character['id'];

		// Write query for all characters
		$sql = "SELECT * FROM choices";
		$save = [];

		if (mysqli_query($conn, $sql)) 
		{
			// Make query and get result
			$result = mysqli_query($conn, $sql);

			// Fetch the resulting rows as an array
			$saves = mysqli_fetch_all($result, MYSQLI_ASSOC);

			foreach ($saves as $saveFile) 
			{
				if ($saveFile['id'] == $character['id'])
				{
					$save = $saveFile;
				}
			}

			// Free result from memory
			mysqli_free_result($result);

			// Close connection
			mysqli_close($conn);
		}
		else
		{
			echo 'Query error: ' . mysqli_error($conn);
		}

		return $save;
	}

	public function SaveGame($character, $location)
	{
		$saveExists = false;
		include('../../Config/db_connect.php');

		$idRaw = $character['id'];

		$sql = "SELECT * FROM choices";
		if (mysqli_query($conn, $sql))
		{
			// Make query and get result
			$result = mysqli_query($conn, $sql);

			// Fetch the resulting rows as an array
			$saves = mysqli_fetch_all($result, MYSQLI_ASSOC);
	
			foreach ($saves as $saveFile) 
			{
				if ($saveFile['id'] == $character['id'])
				{
					$saveExists = true;
				}
			}

			if ($saveExists)
			{
				$sql = "UPDATE choices SET location = '$location' WHERE id = $idRaw";
				// Save to database and check
				if (mysqli_query($conn, $sql))
				{
					// Write query for all characters
					$sql = 'SELECT `id` FROM choices';

					// Make query and get result
					$result = mysqli_query($conn, $sql);

					// Fetch the resulting rows as an array
					$save = mysqli_fetch_all($result, MYSQLI_ASSOC);

					// Free result from memory
					mysqli_free_result($result);

					// Close connection
					mysqli_close($conn);
				}
				else
				{
					echo 'Query error: ' . mysqli_error($conn);
				}
			}
			else
			{
				// Create SQL
				$sql = "INSERT INTO choices(`id`, `location`) VALUES('$idRaw', '$location')";

				// Save to database and check
				if (mysqli_query($conn, $sql))
				{
					// Write query for all characters
					$sql = 'SELECT `id` FROM choices';

					// Make query and get result
					$result = mysqli_query($conn, $sql);

					// Fetch the resulting rows as an array
					$save = mysqli_fetch_all($result, MYSQLI_ASSOC);

					// Free result from memory
					mysqli_free_result($result);

					// Close connection
					mysqli_close($conn);
				}
				else
				{
					echo 'Query error: ' . mysqli_error($conn);
				}
			}
		}
	}

	public function SetUp($location, $id, $character)
	{
		$new = false;
		$end = false;

		if ($location == '' || $location == 'field')
		{
			$situation = "You are standing in a large unknown field. Unsure of why you are here and what had happened to you to forget, you look around you and see a forest in front of you and a lake to your right. \r\n
			Where are you going to look?";

			$option1 = "Forest Entrance";
			$option2 = "Lake";
			$new = true;
		}

		else if ($location == 'forestEntrance')
		{
			$situation = "The forest is dense and dark. You feel a sense of unease.. or maybe it was just a cold breeze? \r\n
			Do you enter the forest?";

			$option1 = "Forest";
			$option2 = "Go to Lake Instead";
		}

		else if ($location == 'lake')
		{
			$situation = "You can see the other side of the lake if you squint hard enough. The area around it looks rather lush and beautiful. \r\n
			Do you head towards the other side?";

			$option1 = "Go to Forest Instead";
			$option2 = "Walk Around";
		}

		else if ($location == 'forest')
		{
			$situation = "It was definitely unease you felt earlier.. but you have made it this far so you decide to push on. There is 
			a very rough path that looks used recently and a tall tree nearby. \r\n
			Should you follow the path or climb the tree to check your surroundings?";

			$option1 = "Path";
			$option2 = "Tree";
		}


		else if ($location == 'backOfLake')
		{
			$situation = "Flowers bloom around this side of the lake, you feel comfortable here. The lake becomes quite inviting as you see a nice spot to
			go swim. Connecting to the lake is a small stream that has a dirt track beside it. \r\n
			Should you relax and go for a swim first or follow the stream?";

			$option1 = "Stream";
			$option2 = "Swim";
		}

		else if ($location == 'cottage')
		{
			$situation = "A small cottage lies within the midst of dense greenery. It feels secluded here but there might be someone to contact for help inside. \r\n
			Do you knock at the door?";

			$option1 = "Go Back";
			$option2 = "Knock";
		}


		else if ($location == 'tree')
		{
			$situation = "You struggle to climb the tree, but it was worthwhile because now you can see a small cottage and a clear area within the forest. \r\n Considering that there might be a person nearby, you could call out to see if anyone responds or you can climb down and reevaluate your choices?";

			$option1 = "Climb";
			$option2 = "Call Out";
		}

		else if ($location == 'forest2')
		{
			$situation = "Now that you have scouted, you try to think what option would be best. \r\n
			Do you go to the cottage or clear area in the forest?";

			$option1 = "Clear Area";
			$option2 = "Cottage";
		}

		else if ($location == 'shack')
		{
			$situation = "You follow the stream to find a shack with a ute with fishing equipment parked out the front. This person might have some answers for you. You knock at the door and a scruffy man answers the door. He seems suspicious of you but proceeds to ask what you want. The look on his face makes you feel uncomfortable.. you consider making something up. \r\n
			Do you tell him you are lost or ask about how good the lake is for fishing?";

			$option1 = "Lost";
			$option2 = "Fishing";
		}

		// ENDINGS

		// BAD
		else if ($location == 'endingCottage')
		{
			$situation = "You knock at the door. Immediately you hear a noise behind you. But in the instant you attempt to look in that direction you get shot by a man behind some bushes. 'That's what ye get if ye snoop..' the man snickers as he spits on the ground.";

			$option1 = "Start Again";
			$option2 = "Return to Home";
			$end = true;
		}

		else if ($location == 'endingTree')
		{
			$situation = "'Hello?! Is there anyone out there? I need help!'' A gunshot fires at you and knocks you out of the tree. Your body lands in shrubbery. A man emerges from some bushes and says to himself 'Close.. If ye were to go to me cottage, ye would've seen too much...'.";

			$option1 = "Start Again";
			$option2 = "Return to Home";
			$end = true;
		}

		else if ($location == 'endingShack')
		{
			$situation = "'I'm lost and I don't know how I got here.' you say to the man. He frowns at you and says 'Is that so? Well, come on in then and we will try to figure it out together.' He steps back and beckons you inside. You follow him and take a seat at his dining table. You look around the house, there are pictures of creatures you have never seen before. 'I'm calling you out.. don't lie to me boy. I know a spy when I see one.' You look at him in shock but as you do, he hits your head with his kettle. You struggle to defend yourself as he grabs your arms and ties them together with rope. 'I ain't fooling for this again..' the man whispers.";

			$option1 = "Start Again";
			$option2 = "Return to Home";
			$end = true;
		}

		else if ($location == 'endingLake')
		{
			$situation = "You undress and slowly walk into to glistening water. A little cold but definitely refreshing. You dive under the water.. but when you try to emerge something grabs ahold of your leg. You scream and inhale the water while it drags you down. You try to look but all you can see is a large grey shadow. Slowly, you keep going deeper and deeper until you see only black.";

			$option1 = "Start Again";
			$option2 = "Return to Home";
			$end = true;
		}

		// GOOD
		else if ($location == 'goodEndingForest')
		{
			$situation = "You stand in the open area looking around you. Looking up you see what looks like a helicopter. 'Is it looking for me?' you think to yourself. You flail your arms around in hopes that it will see you. Something behind you makes a noise. '" . $character['name'] . ", is that you?!' says a familar voice. You turn around and see a guy your age.. you recognise him! In a instant, everything comes back to you.. why you were here.. 'Glen, I am so sorry.' you say softly. He holds you close to him and says 'Guess what.. I found what we were looking for..' his eyes glimmering. 'Really?!' you reply to him. He lets you go and nods. 'Follow me.' he commands and begins to run. You run after him..";

			$option1 = "Start Again";
			$option2 = "Return to Home";
			$end = true;
		}

		else if ($location == 'goodEndingLake')
		{
			$situation = "'Um, well I was curious to know if you had any luck fishing at the lake?' you lie through your teeth. The man's eyebrows raise. 'The lake..?' he asks, 'What do you know about the lake?' he continues. 'Not much. Just wanna fish there but don't wanna waste my time.' you say as nothing else comes to mind. The man looks at you silently for a bit then begins to chuckle. 'Boy, I would not recommend to you to go fishing in that lake. You will catch nothing but your death.' he cackles. He invites you inside but you feel a little nervous about it. He sees your hesitation and says 'It's fine boy, I'll show you a good place to go, I have a map of the better fishing locations. Made it myself' he grins. You follow him inside and he pulls out a sketched map from a bunch of books. He continues to show you a few places on the map, but you stopped listening after you saw the closest town. 'I need to get there,' you think to yourself. After he finishes, he says 'If you head north-west, you will reach the best one.' and walks you out the door, 'Thanks mate!' you say to the man and he waves as you leave.";

			$option1 = "Start Again";
			$option2 = "Return to Home";
			$end = true;
		}

		$this->view('home/game', ['characterID' => $id, 'situation' => $situation, 'location' => $location, 'option1' => $option1, 'option2' => $option2, 'new' => $new, 'end' => $end]);
	}

	public function UpdateLocation($location, $choice)
	{
		if ($location == '')
		{
			$location = 'field';
		}

		else if ($location == 'field')
		{
			if ($choice == '1')
			{
				$location = 'forestEntrance';
			}
			else if ($choice == '2')
			{
				$location = 'lake';
			}
		}

		else if ($location == 'forestEntrance')
		{
			if ($choice == '1')
			{
				$location = 'forest';
			}
			else if ($choice == '2')
			{
				$location = 'lake';
			}
		}

		else if ($location == 'forest')
		{
			if ($choice == '1')
			{
				$location = 'cottage';
			}
			else if ($choice == '2')
			{
				$location = 'tree';
			}
		}

		else if ($location == 'cottage')
		{
			if ($choice == '1')
			{
				$location = 'forest';
			}
			else if ($choice == '2')
			{
				$location = 'endingCottage';
			}
		}

		else if ($location == 'tree')
		{
			if ($choice == '1')
			{
				$location = 'forest2';
			}
			else if ($choice == '2')
			{
				$location = 'endingTree';
			}
		}

		else if ($location == 'forest2')
		{
			if ($choice == '1')
			{
				$location = 'goodEndingForest';
			}
			else if ($choice == '2')
			{
				$location = 'cottage';
			}
		}

		else if ($location == 'lake')
		{
			if ($choice == '1')
			{
				$location = 'forestEntrance';
			}
			else if ($choice == '2')
			{
				$location = 'backOfLake';
			}
		}

		else if ($location == 'backOfLake')
		{
			if ($choice == '1')
			{
				$location = 'shack';
			}
			else if ($choice == '2')
			{
				$location = 'endingLake';
			}
		}

		else if ($location == 'shack')
		{
			if ($choice == '1')
			{
				$location = 'endingShack';
			}
			else if ($choice == '2')
			{
				$location = 'goodEndingLake';
			}
		}

		return $location;
	}
}
