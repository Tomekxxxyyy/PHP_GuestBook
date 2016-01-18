<?php
    if(!$_POST || isset($_GET["id_glowny"])){
        $wyswietlany_blok = "<form method = 'POST' action ='".$_SERVER["PHP_SELF"]."'>";
        if(isset($_GET["id_glowny"])){
            $mysqli = new mysqli('localhost', 'tomek', '', 'guestbook');
            $pobierz_personalia_sql = "SELECT CONCAT_WS(' ', imie, nazwisko) AS wysw_personalia FROM glowna_nazwiska"
                    ." WHERE id = '".$_GET["id_glowny"]."'";
            $pobierz_personalia_rez = $mysqli->query($pobierz_personalia_sql) or die(mysqli_error($mysqli));
            if($pobierz_personalia_rez->num_rows > 0){
                while($personalia_info = $pobierz_personalia_rez->fetch_array()){
                    $wysw_personalia = stripslashes($personalia_info['wysw_personalia']);
                }
            }
        }
        if(isset($wysw_personalia)){
            $wyswietlany_blok .= "<p>Dodawanie informacji dla <strong>$wysw_personalia</strong>:</p>";
        }
        else{
            $wyswietlany_blok .= "
            <p>
            <strong>Imie i nazwisko</strong><br>
            <input type = 'text' name = 'imie' size = '30' maxlength = '75'>
            <input type = 'text' name = 'nazwisko' size = '30' maxlength = '75'>
            </p>
            ";
        }
        
            $wyswietlany_blok .= "
            <p>
            <strong>Adres:</strong><br>
            <input type = 'text' name = 'adres' size = '30'>
            </p>
            <p>
            <strong>Miasto Wojewodztwo Kod</strong><br>
            <input type = 'text' name = 'miasto' size = '30' maxlength = '50'>
            <input type = 'text' name = 'wojewodztwo' size = '5' maxlength = '2'>
            <input type = 'text' name = 'kod' size = '10' maxlength = '10'>
            </p>
            <p>
            <strong>Typ adresu:</strong><br>
            <input type = 'radio' name = 'adres_typ' value='dom' checked>dom
            <input type = 'radio' name = 'adres_typ' value='praca'>praca
            <input type = 'radio' name = 'adres_typ' value='inny'>inny
            </p>
            <p>
            <strong>Numer telefonu:</strong><br>
            <input type = 'text' name = 'tel_numer' size='30' maxlength='25'>dom
            <input type = 'radio' name = 'tel_typ' value='dom' checked>dom
            <input type = 'radio' name = 'tel_typ' value='praca'>praca
            <input type = 'radio' name = 'tel_typ' value='inny'>inny
            </p>
            <p>
            <strong>Numer faksu:</strong><br>
            <input type = 'text' name = 'faks_numer' size='30' maxlength='25'>dom
            <input type = 'radio' name = 'faks_typ' value='dom' checked>dom
            <input type = 'radio' name = 'faks_typ' value='praca'>praca
            <input type = 'radio' name = 'faks_typ' value='inny'>inny
            </p>
            <p>
            <strong>Adres e-mail:</strong><br>
            <input type = 'text' name = 'email' size='30' maxlength='25'>dom
            <input type = 'radio' name = 'email_typ' value='dom' checked>dom
            <input type = 'radio' name = 'email_typ' value='praca'>praca
            <input type = 'radio' name = 'email_typ' value='inny'>inny
            </p>
            <p>
            <strong>Notatki</strong><br>
            <textarea name = 'notatki' cols = '35' rows = '3' wrap = 'soft'></textarea>
            </p>
            ";                  
      
   if($_GET){
            $wyswietlany_blok .= "<input type = 'hidden' name = 'id_glowny' value = '".$_GET["id_glowny"]."'>";
        }
        $wyswietlany_blok .= "            
            <p>
            <input type = 'submit' value = 'Dodaj wpis'>
            </p>
        </form>
        ";
    }
    else if($_POST){
        if((!isset($_POST["imie"]) || !isset($_POST["nazwisko"])) && (!isset($_POST["id_glowny"]))){
            header("Location: dodajwpis.php");
            exit;
        }
        $mysqli = new mysqli('localhost', 'tomek', '', 'guestbook');
        $mysqli->set_charset("utf8");
        if(!isset($_POST["id_glowny"])){
            $dod_glowna_sql = "INSERT INTO glowna_nazwiska(data_dodania, data_modyfikacji, imie, nazwisko) VALUES (now(), now(),'"
                    .$_POST["imie"]."','".$_POST["nazwisko"]."')";
            $dod_glowna_rezul = $mysqli->query($dod_glowna_sql) or die(mysqli_error($mysqli));
            $glowny_id = $mysqli->insert_id;
        }
        else{
            $glowny_id = $_POST["id_glowny"];
        }
        
        if($_POST["adres"] || $_POST["miasto"] || $_POST["wojewodztwo"] || $_POST["kod"]){
            $dod_adres_sql = "INSERT INTO adres (id_glowny, data_dodania, data_modyfikacji, adres, miasto, wojewodztwo, kod, typ) VALUES('"
                    .$glowny_id."', now(), now(), '".$_POST["adres"]."','".$_POST["miasto"]."','".$_POST["wojewodztwo"]."','".$_POST["kod"]."','".$_POST["adres_typ"]."')";
            $dod_adres_rez = $mysqli->query($dod_adres_sql) or die(mysqli_error($mysqli));
        }
        if($_POST["tel_numer"]){
            $dod_tel_sql = "INSERT INTO telefon (id_glowny, data_dodania, data_modyfikacji, telefon, typ) VALUES('"
                    .$glowny_id."', now(), now(), '".$_POST["tel_numer"]."','".$_POST["tel_typ"]."')";
            $dod_tel_rez = $mysqli->query($dod_tel_sql) or die(mysqli_error($mysqli));
        }
        if($_POST["faks_numer"]){
            $dod_faks_sql = "INSERT INTO faks (id_glowny, data_dodania, data_modyfikacji, faks, typ) VALUES('"
                    .$glowny_id."', now(), now(), '".$_POST["faks_numer"]."','".$_POST["faks_typ"]."')";
            $dod_faks_rez = $mysqli->query($dod_faks_sql) or die(mysqli_error($mysqli));
        }
         if($_POST["email"]){
            $dod_email_sql = "INSERT INTO email (id_glowny, data_dodania, data_modyfikacji, email, typ) VALUES('"
                    .$glowny_id."', now(), now(), '".$_POST["email"]."','".$_POST["email_typ"]."')";
            $dod_faks_rez = $mysqli->query($dod_email_sql) or die(mysqli_error($mysqli));
        }
        if($_POST["notatki"]){
            if(!isset($_POST['id_glowny'])){
                $dod_notatki_sql = "INSERT INTO notatki (id_glowny, data_dodania, data_modyfikacji, notatka) VALUES('"
                        .$glowny_id."', now(), now(), '".$_POST["notatki"]."')";
                $dod_notatki_rez = $mysqli->query($dod_notatki_sql) or die(mysqli_error($mysqli));
            }
            else{
                $dod_notatki_sql = "UPDATE notatki SET notatka ='".$_POST["notatki"]."' WHERE id_glowny = '".$glowny_id."'";
                $dod_notatki_rez = $mysqli->query($dod_notatki_sql) or die(mysqli_query($mysqli));
            }
        }
        mysqli_close($mysqli);
        $wyswietlany_blok = "<p>"."Twój wpis został dodany. "."<a href = 'dodajwpis.php'>Czy chcesz dodać jeszcze jeden ?</a>"."</p>";
        $wyswietlany_blok .= "<p><a href='mojemenu.html'>Powrot do glownej</a>";
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Dodawanie wpisu</title>
<meta charset = "utf-8">
</head>
<body>
    <h1>Dodaj wpis</h1>
    <?php echo $wyswietlany_blok; ?>
</body>
</html>