<?php
// Connection àla base de donnée
include("connect.php");
if (isset($_POST["import"])) {

    $fileName = $_FILES["file"]["tmp_name"];

    if ($_FILES["file"]["size"] > 0) {

        $file = fopen($fileName, "r");
        // Boucle afin de récupérer dans la table ticket les données utiles jusqu'à a la fin du fichier csv
        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
            //On verifie si le type est un sms. Dans ce cas la on ajoute tous les champs excepte les champs duree_reel et volume_fact car les sms ont une valeur NULL sur ces deux champs
            if (stristr($column[7], "sms")) {
                $sql = "INSERT into Ticket (num_abo,Date,heure,type_datas)
                values ('" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[7] ."')";
                $result = mysqli_query($conn, $sql);
                //On verifie si le type est un appel. Si c'est un appel on ajoute la ligne qui va donc contenir une durée et non pas le volume facturé
                }elseif(stristr($column[7], "appel") or stristr($column[7], "appels")){
                    $sql = "INSERT into Ticket (num_abo,Date,heure,duree_reel,type_datas)
                    values ('" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[6] . "','" . $column[7] ."')";
                    $result = mysqli_query($conn, $sql);
                    // On ajoute toutes les autres lignes du fichier
                }else{
                    $sql = "INSERT into Ticket (num_abo,Date,heure,volume_fact,type_datas)
                    values ('" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[6] . "','" . $column[7] ."')";
                    $result = mysqli_query($conn, $sql);
                }
            }
        }
    }
//Retourner à la page index.php
header('Location: index.php');
exit;
?>