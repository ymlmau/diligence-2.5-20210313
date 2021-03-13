<?php
set_time_limit(0);
ini_set("memory_limit","2048M");
session_start();
include './config.php';
$conn = getDb();

if(isset($_POST['delete_ruc'])){
    
    $result = mysqli_query($conn, "TRUNCATE TABLE IF EXISTS ruc");
    
    if(!isset($result)){
        echo "<script type=\"text/javascript\">
                alert(\"No se pudo limpiar la tabla RUC!\")
                window.location = \"ruc-dir.php\"
              </script>";
        
    }
    else{
        echo "<script type=\"text/javascript\">
                alert(\"Se limpio la tabla RUC!\")
                window.location = \"ruc-dir.php\"
              </script>";
    }
    
}