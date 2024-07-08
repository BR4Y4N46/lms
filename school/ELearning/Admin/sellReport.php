<?php
if(!isset($_SESSION)){ 
  session_start(); 
}
define('TITLE', 'Sell Report');
define('PAGE', 'sellreport');
include('./adminInclude/header.php'); 
include('../dbConnection.php');

if(isset($_SESSION['is_admin_login'])){
  $adminEmail = $_SESSION['adminLogEmail'];
} else {
  echo "<script> location.href='../index.php'; </script>";
}
?>

<div class="col-sm-9 mt-5">
  <!-- Encabezado con logo, título y fecha/hora -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <img src="https://cdn.vectorstock.com/i/500p/68/18/lms-icon-learning-management-system-flat-vector-46176818.jpg" alt="Logo de Tu Empresa" style="max-height: 50px;">
    </div>
    <div>
      <h2 class="text-center">Factura Electrónica</h2>
      <p class="text-center">Fecha y Hora: <?php echo date('d/m/Y H:i:s'); ?></p>
    </div>
    <div>
      <!-- Opciones adicionales en el encabezado si las necesitas -->
    </div>
  </div>

  <!-- Formulario de búsqueda de facturas -->
  <form action="" method="POST" class="d-print-none">
    <div class="form-row">
      <div class="form-group col-md-2">
        <input type="date" class="form-control" id="startdate" name="startdate">
      </div> <span> a </span>
      <div class="form-group col-md-2">
        <input type="date" class="form-control" id="enddate" name="enddate">
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-secondary" name="searchsubmit" value="Buscar">
      </div>
    </div>
  </form>

  <?php
  if(isset($_REQUEST['searchsubmit'])){
    $startdate = $_REQUEST['startdate'];
    $enddate = $_REQUEST['enddate'];
    $sql = "SELECT * FROM courseorder WHERE order_date BETWEEN '$startdate' AND '$enddate'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
      echo '
      <p class="bg-dark text-white p-2 mt-4">Detalles de Factura</p>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">ID de Pedido</th>
            <th scope="col">ID de Curso</th>
            <th scope="col">Correo Electrónico del Estudiante</th>
            <th scope="col">Estado de Pago</th>
            <th scope="col">Fecha del Pedido</th>
            <th scope="col">Monto</th>
          </tr>
        </thead>
        <tbody>';
        while($row = $result->fetch_assoc()){
          echo '<tr>
            <th scope="row">'.$row["order_id"].'</th>
            <td>'.$row["course_id"].'</td>
            <td>'.$row["stu_email"].'</td>
            <td>'.$row["status"].'</td>
            <td>'.$row["order_date"].'</td>
            <td>'.$row["amount"].'</td>
          </tr>';
        }
        echo '<tr>
          <td colspan="6" class="text-center">
            <form class="d-print-none">
              <input class="btn btn-danger" type="submit" value="Imprimir" onClick="window.print()">
            </form>
          </td>
        </tr>
        </tbody>
      </table>';
    } else {
      echo "<div class='alert alert-warning col-sm-6 ml-5 mt-2' role='alert'>¡No se encontraron registros!</div>";
    }
  }
  ?>
</div>

<!-- Footer y Copyright -->
<?php include('./adminInclude/footer.php'); ?>
<div class="text-center mt-5">
  <p>&copy; <?php echo date('Y'); ?> Tu Empresa. Todos los derechos reservados.</p>
</div>
