<!DOCTYPE html>
<html>
<body>
<h1>Analyse fichier de tickets d'appels</h1>
    <form enctype="multipart/form-data" action="import.php" method="post">
        <div>
            <label>Choisir un fichier CSV</label>
            <input type="file" name="file" id="file" accept=".csv">
            <button type="submit" name="import">Importer</button>
        </div>
    </form>
    <?php
    //creer une connection à la base de donnée
    include("connect.php");

    //Requete SQL: Retrouver la durée totale réelle des appels effectués après le 15/02/2012 en heure (inclus)
    $sql_duree_totale="SELECT sum(sec_to_time(duree_reel))/3600 as duree_tot_reel
    FROM Ticket
    WHERE STR_TO_DATE(Date, '%d/%m/%Y') >= '2012/02/15'
    AND type_datas like '%appel%'";
    
    //Requete SQL: Retrouver le TOP 10 des volumes data facturés en dehors de la tranche horaire 8h00-18h00, par abonné.
    $sql_volume_factures="SELECT num_abo, sum(volume_fact) as volume
    FROM Ticket
    WHERE type_datas not like '%appel%'
    AND (`heure`< '08:00:00'
    OR `heure`> '18:00:00')
    GROUP BY num_abo
    ORDER BY volume DESC
    LIMIT 10";
    
    //Requete SQL: Retrouver la quantité totale de SMS envoyés par l'ensemble des abonnés
    $sql_quant_sms="SELECT count(*) as quantite_sms
    FROM Ticket
    WHERE type_datas like '%sms%'";

    //On effectue chaque requete sur la base de donnée
    $result_duree_totale=mysqli_query($conn,$sql_duree_totale);
    $result_volume_factures=mysqli_query($conn,$sql_volume_factures);
    $result_quant_sms=mysqli_query($conn,$sql_quant_sms);

    echo "<h3>La durée totale réelle des appels effectués après le 15/02/2012 en heure</h3>";
    if ($result_duree_totale->num_rows > 0) {
        $row = $result_duree_totale->fetch_assoc();
            echo $row["duree_tot_reel"] . "<br>";
    } else {
        echo "0 results";
    }
?>
    <h3>TOP 10 des volumes data facturés en dehors de la tranche horaire 8h00-18h00, par abonné</h3>
    <?php if ($result_volume_factures->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Numéro abonné</th>
                    <th>Volume facturé</th>
                </tr>
            </thead>
        <?php while($row = $result_volume_factures->fetch_assoc()) { ?>
            <tbody>
                    <tr>
                        <td> <?php  echo $row['num_abo']; ?> </td>
                        <td> <?php  echo $row['volume']; ?> </td>
                    </tr>
        <?php } ?>
            </tbody>
        </table>
        <?php }?>

    <?php
    echo "<h3>Quantité totale de SMS envoyés par l'ensemble des abonnés</h3>";
    if ($result_quant_sms->num_rows > 0) {
        $row = $result_quant_sms->fetch_assoc();
            echo $row["quantite_sms"] . "<br>";
    } else {
        echo "0 results";
    }
    ?>
</body>
</html>