<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 10-Jan-19
 * Time: 9:27 AM
 */

$host = "localhost";
$db = 'mysql-jointures';
$username = "root";
$password = "";


try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}


function getEleves($conn)
{
    $sql = "select 
            eleves.*,
            eleves_informations.age,
            eleves_informations.avatar
            from eleves, eleves_informations
            where eleves_informations.id = eleves.id";


    if ($query = $conn->query($sql)) {

       return $query->fetchAll(PDO::FETCH_ASSOC);

    }
}

$eleves = getEleves($conn);


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Up To Fourmies, promo 2018/2019</title>
</head>
<body>

<div class="main">

    <div class="elevesContainer">

        <?php

        if (!empty($eleves))
        {
            foreach($eleves as $eleve)
            {
                ?>
                <div class="eleve">
                    <p><?= $eleve['prenom'].' '.$eleve['nom']. ', '.$eleve['age'].' ans'?></p>
                    <button>
                        <a href="<?= 'competences.php?id='.$eleve['id'] ?>">
                            Compétences
                        </a>
                    </button>
                </div>
                <?php
            }
        }

        ?>

    </div>

</div>


</body>
</html>
