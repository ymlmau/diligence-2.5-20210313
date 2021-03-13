<?php
set_time_limit(0);
ini_set("memory_limit","512M");
session_start();
include './config.php';
$conn = getDb();

function importar_ruc(){
  echo "<h3>Importando Ruc</h3>";
  $conn = getDb();
  $filename = $_FILES['ruc_file']['tmp_name'];

  if($_FILES['ruc_file']['size'] > 0){
    $file = fopen($filename,'r');
    echo "<h5>Eliminando datos antiguos...</h5>";
    mysqli_query($conn, "TRUNCATE TABLE IF EXISTS ruc");
    echo "<h5>Insertando datos por favor espere ...</h5>";
    while(($getData = fgetcsv($file, 1000, ",")) !== false){
      $sql = "INSERT INTO ruc(razonsocial,ruc,observaciones) VALUES('" . $getData[0] . "','" . $getData[1] . "','" . $getData[2] . "')";
      $result = mysqli_query($conn,$sql);
      if(!isset($result)){
        echo "<script>alert(\"Archivo no valido!\");</script>";
      }
      }
      fclose($file);
      mysqli_query($conn,"INSERT INTO sys_logs (log_modulo,log_descripcion,log_empreado,log_usuario,log_fecha) VALUES ('ruc-dir.php','Se ha modificado la tabla direccion.','S/N','".$_SESSION['Usuario']."',NOW())");
     echo "<script>alert(\"Archivo fue importado satisfactoriamente!\");
        window.location = \"ruc-dir.php\"  
           </script>";
    
  }
}


function importar_dir(){
  echo "<h3>Importando Direcciones</h3>";
  $conn = getDb();
  $filename = $_FILES['dir_file']['tmp_name'];

  if($_FILES['dir_file']['size'] > 0){
    $file = fopen($filename,'r');
    echo "<h5>Eliminando datos antiguos...</h5>";
    mysqli_query($conn, "TRUNCATE TABLE IF EXISTS direccion");
    echo "<h5>Insertando datos por favor espere ...</h5>";
    while(($getData = fgetcsv($file, 1000, ",")) !== false){
      $sql = "INSERT INTO direccion(num_patronal,razon_so,nom_comercial,direccion,tel1,tel2) VALUES('" . $getData[0] . "','" . $getData[1] . "','" . $getData[2] . "','" . $getData[3] . "','" . $getData[4] . "','" . $getData[5] . "')";
      $result = mysqli_query($conn,$sql);
      if(!isset($result)){
        echo "<script>alert(\"Archivo no valido!\");</script>";
      }
      }
      fclose($file);
      mysqli_query($conn,"INSERT INTO sys_logs (log_modulo,log_descripcion,log_empreado,log_usuario,log_fecha) VALUES ('ruc-dir.php','Se ha modificado la tabla direccion.','S/N','".$_SESSION['Usuario']."',NOW())");
     echo "<script>alert(\"Archivo fue importado satisfactoriamente!\");
        window.location = \"ruc-dir.php\"  
           </script>";
    
  }
}

if(isset($_POST['import'])){
  $action = $_POST['import'];

  if($action == 'ruc'){
      importar_ruc();
  }
  
  elseif($action == 'dir'){

    importar_dir();
  }

  else{
      echo "<script>
        alert(\"Por favor elija una opción valida\");
        window.location = \"ruc-dir.php\"
      </script>
      ";
    }

} else{
  echo "<script>
        alert(\"Por favor Seleccione un archivo .csv\");
        window.location = \"ruc-dir.php\"
      </script>
      ";
}

?>
