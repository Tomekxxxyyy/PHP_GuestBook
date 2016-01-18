<?php
 $mysqli = new mysqli('localhost', 'tomek', '', 'guestbook');
 
 if(!$_POST){
     $wyswietlany_blok = "<h1>"."Wybierz wpis"."</h1>";
     $pobieranie_listy_sql = "SELECT id, CONCAT_WS(',',nazwisko, imie) AS personalia_wysw FROM glowna_nazwiska ORDER BY nazwisko, imie";
     $pobieranie_listy_rez = $mysqli->query($pobieranie_listy_sql) or die(mysqli_error($mysqli));
     if($pobieranie_listy_rez->num_rows < 1){
         $wyswietlany_blok .= "<p><em>"."Niestety baza jest pusta"."</em></p>";
         $wyswietlany_blok .= "<p><a href='mojemenu.html'>Powrot do glownej</a>";
     }
 
    else{
        $wyswietlany_blok .= <<<LONG
                <form method = "POST" action = "{$_SERVER["PHP_SELF"]}" \>
                <p><strong>Wybierz rekord:</strong><br />
                <select name = "wybrany_id">
                <option value = "">--Wybierz jeden--</option>
LONG;
                while($rek = $pobieranie_listy_rez->fetch_array()){
                    $id = $rek['id'];
                    $personalia_wysw = stripslashes($rek['personalia_wysw']);
                    $wyswietlany_blok .= "<option value = '".$id."' />".$personalia_wysw."</option>";
                }
       $wyswietlany_blok .= <<<LONG
               <select>
               <p>
               <input type = "submit" value = "usun wybrany wpis">
               </p>
               </form>
LONG;
    }
}
else if($_POST){
    if(!$_POST["wybrany_id"]){
        header("Location: usunwpis.php");
        exit;
    }
    $usun_glowna_sql = "DELETE FROM glowna_nazwiska WHERE id='".$_POST['wybrany_id']."'";
    $usun_glowna_rez = $mysqli->query($usun_glowna_sql) or die($mysqli->error);
    
    $usun_adres_sql = "DELETE FROM adres WHERE id='".$_POST['wybrany_id']."'";
    $usun_adres_rez = $mysqli->query($usun_adres_sql) or die($mysqli->error);
    
    $usun_tel_sql = "DELETE FROM telefon WHERE id='".$_POST['wybrany_id']."'";
    $usun_tel_rez = $mysqli->query($usun_tel_sql) or die($mysqli->error);
    
    $usun_faks_sql = "DELETE FROM faks WHERE id='".$_POST['wybrany_id']."'";
    $usun_faks_rez = $mysqli->query($usun_faks_sql) or die($mysqli->error);
    
    $usun_email_sql = "DELETE FROM email WHERE id='".$_POST['wybrany_id']."'";
    $usun_email_rez = $mysqli->query($usun_email_sql) or die($mysqli->error);
    
    $usun_notatka_sql = "DELETE FROM notatki WHERE id='".$_POST['wybrany_id']."'";
    $usun_notatka_rez = $mysqli->query($usun_notatka_sql) or die($mysqli->error);
    
    $wyswietlany_blok = "<h1>"."Rekordy zostały usunięte"."</h1>"
        ."<p>"."Czy chcesz usunąć <a href ='usunwpis.php'>Następne</a>"."</p>";
    $wyswietlany_blok .= "<p><a href='mojemenu.html'>Powrot do glownej</a>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Usuwanie</title>
<meta charset="utf-8">
</head>
<body>
<?php echo $wyswietlany_blok; ?>        
</body>
</html>