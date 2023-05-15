<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Početna</title>
</head>
<style>.error {color: #FF0000;}</style>
<body>
    <?php
        $na_srpskom = "";
        $na_engleskom = "";
        $eErr = $sErr = $oErr = "";
        $greska = false;
        $name = $result = "";

        $conn = mysqli_connect("localhost", "root", "", "erecnik");

        if($conn === false)
        {
            die("ERROR: Could not connect. "
                . mysqli_connect_error());
        }
        
        function test_input($data) 
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $rec = filter_input(INPUT_POST, "smer", FILTER_SANITIZE_STRING);
            //srpsko-engleski
            if($rec=="se"){
                if (empty($_POST["sr"])) {
                    $sErr = " * Obavezno polje!";
                    $greska=true;
                } 
                else {
                    $na_srpskom = test_input($_POST["sr"]);
                    $result = mysqli_query( $conn, "Select * from `reci` where `na_srpskom`='$na_srpskom'");
                    $name=( $result ) ? mysqli_fetch_assoc( $result ) : false;        
                }
            }
            //englesko-srpski
            else if($rec=="es"){
                if (empty($_POST["er"])) {
                    $eErr = " * Obavezno polje!";
                    $greska=true;
                } 
                else {
                    $na_engleskom = test_input($_POST["er"]);
                    $result = mysqli_query( $conn, "Select * from `reci` where `na_engleskom`='$na_engleskom'" );
                    $name=( $result ) ? mysqli_fetch_assoc( $result ) : false;        
                }
            }
   
        }
        mysqli_close($conn);
        ?>
    <span class="naslov">
        <h1 style="padding: 20px; background-color: #617eaa;">Elektronski rečnik</h1>
    </span>
    <div>
        <ul>
            <li><a class="active" href="index.php">Rečnik</a></li>
            <li><a href="dodavanje.php">Dodavanje novih reči</a></li>
            <li><a href="uputstvo.html">Uputstvo</a></li>
        </ul> 
    </div>
    <div class="container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="row">
                <div class="col-25">
                    <label for="smer">Smer:</label>
                </div>
                <div class="col-75">
                <select name="smer" id="smer">
                    <option value="">Selektuj smer prevođenja</option>
                    <option value="se">Srpsko - Engleski</option>
                    <option value="es">Englesko - Srpski</option>
                </select>
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="er">Engleska reč:</label>
                </div>
                <div class="col-75">
                    <input type="text" id="er" name="er" placeholder="" <?php  if( !empty( $name ))
                    echo "value='{$name['na_engleskom']}'";?>><span class="error"><?php echo $eErr;?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="sr">Srpska reč:</label>
                </div>
                <div class="col-75">
                    <input type="text" id="sr" name="sr" placeholder="" <?php  if(!empty( $name ))
                    echo "value='{$name['na_srpskom']}'";?>><span class="error"><?php echo $sErr;?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="opis">Opis:</label>
                </div>
                <div class="col-75">
                    <textarea id="opis" name="opis" placeholder="" style="height:200px" ><?php  if(!empty( $name ))
                    echo "{$name['opis']}";?></textarea><span class="error"><?php echo $oErr;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <input type="submit" name="submit" value="Prevedi" onclick=validacija()>
            </div>
        </form>
    </div>
    <div class="footer">
        <p>&copy Osnovna škola "Sonja Marinković"</p>
    </div>
    <script>
        function validacija(){
            var smer=document.getElementById("smer").value;
            if(smer == ""){alert("Niste selektovali smer pevodjenja!");}
        }
    </script>
</body>
</html>