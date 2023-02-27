<p><a href="intrebareNoua.php">Intrebare noua</a></p>

<?php

    $sname = "localhost";
    $unmae = "root";
    $password = "";
    $db_name = "ExamDB";
    $intrebare = $_POST["intrebare"];

    $conn = mysqli_connect($sname, $unmae, $password, $db_name);
    if ($conn->connect_error) {
        die ("Connection failed: " . $conn->connect_error);
    } else {

    }

    $query = "SELECT id, intrebare FROM Intrebari";
//    echo $query;
    $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
             $intrebare=$row['intrebare'];
             $idIntrebare=$row['id'];
             echo $intrebare;
            echo '<a href="catreIntrebare.php?id='.$idIntrebare.'">Catre intrebare</a>'. ";<br>";

        }


?>