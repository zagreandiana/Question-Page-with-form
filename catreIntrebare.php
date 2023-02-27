<?php
require_once("config.php");
session_start();

$idIntrebare = $_GET["id"];
$host = "localhost";
$usernamesql = "root";
$passwordsql = "";
$db_name = "ExamDB";
$db = mysqli_connect($host, $usernamesql, $passwordsql, $db_name) or die ("could not connect");
if (!$db) die ("Connection failed: " . mysqli_connect_error());
echo "Connected successfully";

$intrebare=$_SESSION["intrebare"];
$nume1=$_SESSION["nume"];
$email=$_SESSION["email"];
$raspunpemail=$_SESSION["raspunspemail"];



if ($_SERVER["REQUEST_METHOD"] == "POST") {


// sanitizare date
$nume = $_POST["nume"];
$raspuns = $_POST["raspuns"];
$idIntrebareIndex = $_GET["id"];
$numeSanitized = htmlentities($nume);
$raspunsSanitized = htmlentities($raspuns);
$idIntrebareSanitized = htmlentities($idIntrebare);


//validare date
$numeerr = $raspunserr = "";
$nume1 = $raspuns1 = "";




    if (empty($_POST["nume"])) {
        $numeerr = "Numele este obligatoriu";
        $eroare = 1;
    } else {
        $nume1 = test_input($_POST["nume"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $nume1)) {
            $numeerr = "Numai litere si spatii sunt acceptate";
            $eroare = 1;
            echo $numeerr;
        }
    }

    if (empty($_POST["raspuns"])) {
        $raspunserr = "Raspunsul este obligatoriu";
        $eroare = 1;
    } else {
        $raspuns1 = test_input($_POST["raspuns"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $raspuns1)) {
            $intrebareerr = "Numai litere si spatii sunt acceptate";
            $eroare = 1;
            echo $intrebareerr;
        }
    }
    if ($eroare == 0) {
        echo "tot ok";
    }

    if ($eroare != 0) {
        echo $eroare;

    }
}

// exista raspunsuri la intrebare?
$raspunsIdentificat="";
$query="SELECT FROM Raspunsuri WHERE '$idIntrebareIndex'='$idIntrebare'";
$result=$db->query($query);
if ($result->num_rows > 0) {
while($row=$result->fetch_assoc()){
    $raspunsIdentificat.=$row["raspuns"]."<br>";
}

if(!$raspunsIdentificat ==""){
echo $raspunsIdentificat."<br>";}
} else {
    "Nu s-a gasit niciun raspuns la acea intrebare";
}





//  Introducere in baza de date
$host = "localhost";
$usernamesql = "root";
$passwordsql = "";
$db_name = "ExamDB";
$db = mysqli_connect($host, $usernamesql, $passwordsql, $db_name) or die ("could not connect");
if (!$db) die ("Connection failed: " . mysqli_connect_error());
echo "Connected successfully";

if (!$eroare == 1) {
    $sql = "INSERT INTO Raspunsuri (idIntrebare, nume, raspuns) VALUES ( $idIntrebareSanitized, '$numeSanitized', '$raspunsSanitized')";
    echo $sql;
}

if ($db->query($sql) === TRUE) {
    echo "New record created successfully";
    echo "<br>";
} else {
    echo "Not created";
}

$emaildestinatar = $_SESSION["email"];
$subiect = "";
$mesaj = "";
$emailexpeditor = "";
$raspunpemail = $_POST["raspunspemail"];
if ($raspunpemail === "Yes") {
    mail($emaildestinatar, "Subject: $subiect", $mesaj, "From: $emailexpeditor");
}
echo "Raspuns salvat" . "<a href='index.php'>Inapoi la index</a>";
session_destroy();

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" target="_self"
      enctype="multipart/form-data">
    <div class="container">
        <h1>Register</h1>
        <p>Please fill in this form.</p>
        <hr>


        <label for="nume"><b>Nume</b></label>
        <input type="text" placeholder="Introduceti numele" name="nume" id="nume" required><br>

        <label for="raspuns"><b>Raspuns</b></label>
        <input type="text" placeholder="Introduceti raspunsul" name="raspuns" id="raspuns" required><br>

        <button type="submit" class="registerbtn">Submit</button>
    </div>
</form>