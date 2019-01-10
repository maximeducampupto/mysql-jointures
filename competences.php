<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 10-Jan-19
 * Time: 10:47 AM
 */

require 'EasyPHPCharts-master/src/Antoineaugusti/EasyPHPCharts/Chart.php';


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



function getInfos($conn)
{
    $id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

    $sql = "select
      	eleves.nom,
        eleves.prenom,
        eleves.id,
        eleves_competences.niveau,
        eleves_competences.niveau_ae,
        competences.titre,
        competences.description

        from
        eleves_competences

        inner join eleves
        on eleves_competences.eleves_id = eleves.id
        
        inner join competences
        on competences.id = eleves_competences.competences_id

        where eleves.id = $id
        ";

    $result = [];

    if ($query = $conn->query($sql)) {

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($result, $row);
        }
    }
    return $result;
}

$eleves_competences = getInfos($conn);
$niveau = [
        "HTML" => $eleves_competences[0]['niveau'],
        "CSS" => $eleves_competences[1]['niveau'],
        "JS" => $eleves_competences[2]['niveau'],
        "PHP" => $eleves_competences[3]['niveau'],
];

$niveau_ae = [
    "HTML" => $eleves_competences[0]['niveau_ae'],
    "CSS" => $eleves_competences[1]['niveau_ae'],
    "JS" => $eleves_competences[2]['niveau_ae'],
    "PHP" => $eleves_competences[3]['niveau_ae'],
]
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <script src="src/ChartJS.min.js"></script>
    <title>Compétences de <?= $eleves_competences[0]['prenom']. ' ' .$eleves_competences[0]['nom']?></title>
</head>
<body class="competencesBody">

<div class="main">
    <h1 class="eleveCompetenceTitle">Compétences de <?= $eleves_competences[0]['prenom']. ' ' .$eleves_competences[0]['nom']?></h1>

    <div class="test">
        <div class="competencesChart">
            <h3>Niveau réel</h3>
            <?php

            $html = $niveau['HTML'];
            $css = $niveau['CSS'];
            $js = $niveau['JS'];
            $php = $niveau['PHP'];


            $PolarChart = new Antoineaugusti\EasyPHPCharts\Chart('polar', 'polar');
            $PolarChart->set('data', array($html, $css, $js, $php));
            $PolarChart->set('legend', array('HTML', 'CSS', 'JS', 'PHP'));
            $PolarChart->set('displayLegend', true);
            echo $PolarChart->returnFullHTML();

            ?>
        </div>


        <div class="competencesChart">
            <h3>Niveau auto-évalué</h3>
            <?php

            $html = $niveau_ae['HTML'];
            $css = $niveau_ae['CSS'];
            $js = $niveau_ae['JS'];
            $php = $niveau_ae['PHP'];


            $PolarChart2 = new Antoineaugusti\EasyPHPCharts\Chart('polar', 'polar2');
            $PolarChart2->set('data', array($html, $css, $js, $php));
            $PolarChart2->set('legend', array('HTML', 'CSS', 'JS', 'PHP'));
            $PolarChart2->set('displayLegend', true);
            echo $PolarChart2->returnFullHTML();

            ?>
        </div>
    </div>

    <a href="index.php" class="backButton">Retour</a>
</div>

</body>
</html>
