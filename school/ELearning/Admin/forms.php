<?php
if(!isset($_SESSION)){ 
  session_start(); 
}
define('TITLE', 'Contactos');
define('PAGE', 'contactos');
include('./adminInclude/header.php'); 
include('../dbConnection.php');

if(isset($_SESSION['is_admin_login'])){
  $adminEmail = $_SESSION['adminLogEmail'];
} else {
  echo "<script> location.href='../index.php'; </script>";
}

?>
<div class="col-sm-9 mt-5">
    <!--Tabla-->
    <p class=" bg-dark text-white p-2">Contactos</p>
    <?php
      $sql = "SELECT * FROM forms";
      $result = $conn->query($sql);
      if($result->num_rows > 0){
       echo '<table class="table">
       <thead>
        <tr>
         <th scope="col">ID</th>
         <th scope="col">Fecha</th>
         <th scope="col">Nombre</th>
         <th scope="col">Asunto</th>
         <th scope="col">Correo Electrónico</th>
         <th scope="col">Comentario</th>
         <th scope="col">Acción</th>
        </tr>
       </thead>
       <tbody>';
        while($row = $result->fetch_assoc()){
          echo '<tr>';
          echo '<th scope="row">'.$row["id"].'</th>';
          echo '<td>'. $row["fecha"].'</td>';
          echo '<td>'.$row["name"].'</td>';
          echo '<td>'.$row["subject"].'</td>';
          echo '<td>'.$row["email"].'</td>';
          echo '<td>'.$row["comentario"].'</td>';
          echo '<td><form action="" method="POST" class="d-inline"><input type="hidden" name="id" value='. $row["id"] .'><button type="submit" class="btn btn-secondary" name="delete" value="Delete"><i class="far fa-trash-alt"></i></button></form></td>';
          echo '</tr>';
        }

        echo '</tbody>
        </table>';
      } else {
        echo "<div class='alert alert-warning'>No hay resultados.</div>";
      }
      
      // Proceso para eliminar un registro
      if(isset($_POST['delete'])){
        $delete_id = $_POST['id'];
        $sql = "DELETE FROM forms WHERE id = '$delete_id'";
        if ($conn->query($sql) === TRUE) {
          // Reiniciar el contador autoincremental después de borrar todos los registros
          $reset_auto_increment_sql = "ALTER TABLE forms AUTO_INCREMENT = 1";
          if ($conn->query($reset_auto_increment_sql) === TRUE) {
            echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
          } else {
            echo "Error al reiniciar el contador autoincremental: " . $conn->error;
          }
        } else {
          echo "Error al eliminar el registro: " . $conn->error;
        }
      }
    ?>
  </div>
 </div>  <!-- Cierre de la fila desde el encabezado -->
</div>  <!-- Cierre del contenedor-fluid desde el encabezado -->
<?php
include('./adminInclude/footer.php'); 
?>
