<?php
session_start();

include("config.php");

$cod=1;
$fileerr='';

if($_SERVER["REQUEST_METHOD"]=="POST") {
    $id = $_POST["id"];
    $intrebare = $_POST["intrebare"];
    $nume = $_POST["nume"];
    $email = $_POST["email"];
    $poza = $_POST["poza"];
    $raspunspemail = $_POST["checkbox"];
    $max_file_size = "500000";
    $file_dir = "poze/";


// verificare daca a fost uploadat un fisier
    if ($_FILES["fileupload"]["size"] == 0) {
        $fileerr = "Poza este obligatorie";
        $eroare = 1;
    } // verifica sa nu fie prea mare fisierul
    elseif ($_FILES["fileupload"]["size"] > $max_file_size) {
        $fileerr = "Fisierul este prea mare";
        $eroare = 1;
    } // verifica daca fisierul este o poza
    elseif (getimagesize($_FILES["fileupload"]["tmp_name"]) == false) {
        $fileerr = "Fisierul nu este o imagine";
        $eroare = 1;
    } // verifica daca fisierul este .jpg
    else {
        $target_file = $file_dir . basename($_FILES["fileupload"]["name"]);
        $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if ($image_type != "jpg") {
            $fileerr = "Fisierul trebuie sa fie jpg";
            echo "nu e ok";
            echo "<br>";
            $eroare = 1;
        }
    }


    $image = $_POST['fileupload'];
    echo "<p align='left'><img src='" . $image . "' width='150'></p>";


// se declara o variabila pentru locatia si denumirea fisierului de salvat (cu numele original al fisierului)
    $target_file = $file_dir . basename($_FILES["fileupload"]["name"]);
//se scoate extensia din denumirea fisierului
    $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// se declara o variabila pentru locatia si denumirea fisierului de salvat (cu username-ul introdus si extensia originala)
    $target_file = $file_dir . $image_type;
    echo $target_file;
    echo "<br>";


//// se uploadeaza poza
    move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file);


// sanitizarea datelor introduse in formular

    $idSanitized = htmlentities($id);
    $intrebareSanitized = htmlentities($intrebare);
    $numeSanitized = htmlentities($nume);
    $emailSanitized = htmlentities($email);
    $pozaSanitized = htmlentities($poza);
    $raspunspemailSanitized = htmlentities($raspunspemail);


// validarea datelor introduse in formular

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlentities($data);
        return $data;
    }

    $intrebareerr = $numeerr = $emailerr = $pozaerr = $raspunspemailerr = "";
    $intrebare1 = $nume1 = $email1 = $poza1 = $raspunspemail1 = "";
    $eroare = 0;


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST["intrebare"])) {
            $intrebareerr = "Intrebarea este obligatorie";
            $eroare = 1;
        } else {
            $intrebare1 = test_input($_POST["intrebare"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $intrebare1)) {
                $intrebareerr = "Numai litere si spatii sunt acceptate";
                $eroare = 1;
                echo $intrebareerr;
            }
        }

        if (empty($_POST["nume"])) {
            $prenumeerr = "Numele este obligatoriu";
            $eroare = 1;
        } else {
            $nume1 = test_input($_POST["nume"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $nume1)) {
                $numeerr = "Numai litere si spatii sunt acceptate";
                $eroare = 1;
                echo $numeerr;
            }
        }

        if (filter_var($email1, FILTER_VALIDATE_EMAIL)) {
            $emailerr = "Format adresa e-mail invalid";
            echo $emailerr;
            $eroare = 1;
        }



        if ($eroare == 0) {
            echo "tot ok";
        }

        if ($eroare != 0) {
            echo $eroare;
            exit();
        }
    }


        //  Introducere in baza de date
        $host = "localhost";
        $usernamesql = "root";
        $passwordsql = "";
        $db_name = "ExamDB";
        $db = mysqli_connect($host, $usernamesql, $passwordsql, $db_name) or die ("could not connect");
        if (!$db) die ("Connection failed: " . mysqli_connect_error());
        echo "Connected successfully";


        $sql = "INSERT INTO Intrebari (intrebare, nume, email, raspunspemail) VALUES ( '$intrebareSanitized', '$numeSanitized', '$emailSanitized' ,'$raspunspemailSanitized')";
        echo "<br>";
        echo $sql;
        echo "<br>";


        if ($db->query($sql) === TRUE) {
            echo "New record created successfully";
            echo "<br>";
        } else {
            echo "Not created";
        }




}

?>


<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" target="_self" enctype="multipart/form-data">
    <div class="container">
        <h1>Register</h1>
        <p>Please fill in this form.</p>
        <hr>


        <label for="intrebare"><b>Intrebare</b></label>
        <input type="text" placeholder="Introduceti intrebarea" name="intrebare" id="intrebare" required><br>

        <label for="nume"><b>Nume</b></label>
        <input type="text" placeholder="Introduceti numele" name="nume" id="nume" required><br>

        <label for="email"><b>Email</b></label>
        <input type="email" placeholder="Enter email" name="email" id="email" required><br>

        <label for="fileupload"><b>Fotografie</b></label>
        <input type='file' name='fileupload'><br>

        <input type="checkbox" name="checkbox" value="Yes"  ><b>doresc sa primesc raspunsurile pe mail</b><br>
        <span class="error">* <?php echo $fileerr;?></span>

        <button type="submit" class="registerbtn">Register</button>
    </div>
