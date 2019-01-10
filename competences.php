<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 10-Jan-19
 * Time: 10:47 AM
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



function getInfos($conn)
{
    $id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

    $sql = "select
      	eleves.nom,
        eleves.prenom,
        eleves.id,
        eleves_competences.niveau,
        eleves_competences.niveau_ae

        from
        eleves_competences

        inner join eleves
        on eleves_competences.eleves_id = eleves.id

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
    <script src= "https://cdn.zingchart.com/zingchart.min.js"></script>
    <script>
        zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
        ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9","ee6b7db5b51705a13dc2339db3edaf6d"];
    </script>
    <style>
        html, body {
            height:100%;
            width:100%;
        }
        #myChart {
            height:100%;
            width:100%;
            min-height:250px;
        }
        .zc-ref {
            display:none;
        }
    </style>
    <title>Compétences de <?= $eleves_competences[0]['prenom']. ' ' .$eleves_competences[0]['nom']?></title>
</head>
<body>

<header>
    <h1>Compétences de <?= $eleves_competences[0]['prenom']. ' ' .$eleves_competences[0]['nom']?></h1>
</header>

<div class="container">

    <div id='myChart'></div>

    <a href="index.php" class="backButton">Retour</a>
    
</div>

<script>
    var myConfig = {
        type : 'radar',
        plot : {
            aspect : 'area',
            animation: {
                effect:3,
                sequence:1,
                speed:700
            }
        },
        scaleV : {
            visible : false
        },
        scaleK : {
            values : '0:3:1',
            labels : ['HTML','CSS','JS','PHP'],
            item : {
                fontColor : '#607D8B',
                backgroundColor : "white",
                borderColor : "#aeaeae",
                borderWidth : 1,
                padding : '5 10',
                borderRadius : 10
            },
            refLine : {
                lineColor : '#c10000'
            },
            tick : {
                lineColor : '#59869c',
                lineWidth : 2,
                lineStyle : 'dotted',
                size : 20
            },
            guide : {
                lineColor : "#607D8B",
                lineStyle : 'solid',
                alpha : 0.3,
                backgroundColor : "#c5c5c5 #718eb4"
            }
        },
        series : [
            {
                values : [
                    <?= $niveau['HTML']?>,
                    <?= $niveau['CSS']?>,
                    <?= $niveau['JS']?>,
                    <?= $niveau['PHP']?>,
                ],
                text:''
            },
            {
                values : [
                    <?= $niveau_ae['HTML']?>,
                    <?= $niveau_ae['CSS']?>,
                    <?= $niveau_ae['JS']?>,
                    <?= $niveau_ae['PHP']?>,
                ],
                lineColor : '#53a534',
                backgroundColor : '#689F38'
            }
        ]
    };

    zingchart.render({
        id : 'myChart',
        data : myConfig,
        height: '130%',
        width: '100%'
    });
</script>
</body>
</html>
