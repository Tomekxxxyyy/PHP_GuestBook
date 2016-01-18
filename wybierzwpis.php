<?php
 $mysqli = new mysqli('localhost', 'tomek', '', 'guestbook');
 $mysqli->set_charset("utf8");
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
               </select>
               <p>
               <input type = "submit" value = "zobacz wybrany wpis">
               </p>
               </form>
LONG;
    }
}
else if($_POST){
    if(!$_POST["wybrany_id"]){
        header("Location: wybierzwpis.php");
        exit;
    }
    
    //imie nazwisko
    
    $pobierz_glowna_sql = "SELECT CONCAT_WS(' ', imie, nazwisko) as personalia_wysw FROM glowna_nazwiska WHERE id = '"
            .$_POST["wybrany_id"]."'";
    $pobierz_glowna_rez = $mysqli->query($pobierz_glowna_sql) or die(mysqli_error($mysqli));
    
    while($personalia_info = $pobierz_glowna_rez->fetch_array()){
        $personalia_wysw = stripslashes($personalia_info['personalia_wysw']);
    }
    $wyswietlany_blok = "<h1>Ukazywany wpis: ".$personalia_wysw."</h1>";
    $pobierz_glowna_rez->free_result();
    
    //adresy 
    
    $_pobierz_adresy_sql = "SELECT adres, miasto, wojewodztwo, kod, typ FROM adres WHERE id_glowny = '".$_POST["wybrany_id"]."'";
    $_pobierz_adresy_rez = $mysqli->query($_pobierz_adresy_sql) or die(mysqli_error($mysqli));
     
    if($_pobierz_adresy_rez->num_rows > 0){
        $wyswietlany_blok .= "<p><strong>"."adresy: "."</p></strong>"
                . "<ul>";
        while($add_info = $_pobierz_adresy_rez->fetch_array()){
            $adres = stripslashes($add_info['adres']);
            $miasto = stripslashes($add_info['miasto']);
            $wojewodztwo = stripslashes($add_info['wojewodztwo']);
            $kod = stripslashes($add_info['kod']);
            $adres_typ = $add_info['typ'];
            
            $wyswietlany_blok .= "<li>$adres $miasto $wojewodztwo $kod ($adres_typ)</li>";
        }
        $wyswietlany_blok .= "</ul>";
    }
    $_pobierz_adresy_rez->free_result();
    
    // telefon
    
    $pobierz_tel_sql = "SELECT telefon, typ FROM telefon WHERE id_glowny ='".$_POST["wybrany_id"]."'";
    $pobierz_tel_rez = $mysqli->query($pobierz_tel_sql);
    
    if($pobierz_tel_rez->num_rows > 0){
        $wyswietlany_blok .= "<p><strong>"."Telefon: "."</strong></p>"
                ."<ul>";
        while($tel_info = $pobierz_tel_rez->fetch_array()){
            $telefon = stripslashes($tel_info['telefon']);
            $tel_typ = $tel_info['typ'];
            
            $wyswietlany_blok .= "<li>$telefon ($tel_typ)</li>";
        }
        $wyswietlany_blok .= "</ul>";
    }
    $pobierz_tel_rez->free_result();
    
    // faks
    
    $pobierz_faks_sql = "SELECT faks, typ FROM faks WHERE id_glowny = '".$_POST["wybrany_id"]."'";
    $pobierz_faks_rez = $mysqli->query($pobierz_faks_sql) or die(mysqli_error($mysqli));
    
    if($pobierz_faks_rez->num_rows > 0){
        $wyswietlany_blok .= "<p><strong>Faks:</strong></p>"
                ."<ul>";
        while($faks_info = $pobierz_faks_rez->fetch_array()){
            $faks = stripslashes($faks_info['faks']);
            $faks_typ = $faks_info['typ'];
            $wyswietlany_blok .= "<li>$faks ($faks_typ)</li>";
        }
        $wyswietlany_blok .= "</ul>";
    }
    $pobierz_faks_rez->free_result();
    
    //email
    
    $pobierz_email_sql = "SELECT email, typ FROM email WHERE id_glowny ='".$_POST["wybrany_id"]."'";
    $pobierz_email_rez = $mysqli->query($pobierz_email_sql);
    
    if($pobierz_email_rez->num_rows > 0){
        $wyswietlany_blok .= "<p><strong>"."E-mail"."<strong></p>"
                ."<ul>";
        while($email_info = $pobierz_email_rez->fetch_array()){
            $email = stripslashes($email_info['email']);
            $email_typ = $email_info['typ'];
            
            $wyswietlany_blok .= "<li>$email ($email_typ)</li>";
        }
        $wyswietlany_blok .= "</ul>";
    }
    $pobierz_email_rez->free_result();
    
    $pobierz_notes_sql = "SELECT notatka FROM notatki WHERE id_glowny ='".$_POST["wybrany_id"]."'";
    $pobierz_notes_rez = $mysqli->query($pobierz_notes_sql) or die(mysqli_error($mysqli));
    
    if($pobierz_notes_rez->num_rows > 0){
        while($note_info = $pobierz_notes_rez->fetch_array()){
            $note = nl2br(stripslashes($note_info['notatka']));
        }
        $wyswietlany_blok .= "<p><strong>Notatki:</strong><br />$note</p>";
    }
    
    $pobierz_notes_rez->free_result();
    $wyswietlany_blok .= "<p><a href = 'dodajwpis.php?id_glowny=".$_POST["wybrany_id"]."'>"."Dodaj dane</a></p>"
            ."<p><a href='".$_SERVER["PHP_SELF"]."'/>Wybierz inny</a></p>";
    $wyswietlany_blok .= "<p><a href='mojemenu.html'>Powrot do glownej</a>";
}
  $mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dodawanie rekordów</title>
    <meta charset = "utf-8">
</head>    
<body>
    <?php
        echo $wyswietlany_blok;
    ?>
</body>
</html>

