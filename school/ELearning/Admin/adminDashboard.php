<?php
if(!isset($_SESSION)){ 
  session_start(); 
}
define('TITLE', 'Dashboard');
define('PAGE', 'dashboard');
include('./adminInclude/header.php'); 
include('../dbConnection.php');

if(isset($_SESSION['is_admin_login'])){
  $adminEmail = $_SESSION['adminLogEmail'];
} else {
  echo "<script> location.href='../index.php'; </script>";
}

// Obtener estadísticas
$sql_courses = "SELECT * FROM course";
$result_courses = $conn->query($sql_courses);
$totalcourse = $result_courses->num_rows;

$sql_students = "SELECT * FROM student";
$result_students = $conn->query($sql_students);
$totalstu = $result_students->num_rows;

$sql_forms = "SELECT * FROM forms";
$result_forms = $conn->query($sql_forms);
$totalforms = $result_forms->num_rows;

$sql_orders = "SELECT * FROM courseorder";
$result_orders = $conn->query($sql_orders);
$totalsold = $result_orders->num_rows;

// Configuración de la paginación
$results_per_page = 5; // Número de resultados por página

// Determinar la página actual
if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1; // Página inicial por defecto
}

$start_from = ($page - 1) * $results_per_page; // Calcular el punto de inicio para la consulta SQL

$sql_courseorder = "SELECT * FROM courseorder LIMIT $start_from, $results_per_page";
$result_courseorder = $conn->query($sql_courseorder);

$sql_total = "SELECT COUNT(*) AS total FROM courseorder";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_records = $row_total['total'];
$total_pages = ceil($total_records / $results_per_page);

?>

<div class="col-sm-9 mt-5">
  <div class="row mx-5 text-center">

    <!-- Card de Cursos -->
    <div class="col-sm-3 mt-5">
      <div class="card text-white bg-danger mb-3" style="max-width: 15rem;">
        <div class="card-header">Courses</div>
        <div class="card-body">
          <h4 class="card-title">
            <?php echo $totalcourse; ?>
          </h4>
          <a class="btn text-white" href="courses.php">View</a>
        </div>
      </div>
    </div>

    <!-- Card de Estudiantes -->
    <div class="col-sm-3 mt-5">
      <div class="card text-white bg-success mb-3" style="max-width: 15rem;">
        <div class="card-header">Students</div>
        <div class="card-body">
          <h4 class="card-title">
            <?php echo $totalstu; ?>
          </h4>
          <a class="btn text-white" href="students.php">View</a>
        </div>
      </div>
    </div>

    <!-- Card de Ventas -->
    <div class="col-sm-3 mt-5">
      <div class="card text-white bg-info mb-3" style="max-width: 15rem;">
        <div class="card-header">Sold</div>
        <div class="card-body">
          <h4 class="card-title">
            <?php echo $totalsold; ?>
          </h4>
          <a class="btn text-white" href="sellreport.php">View</a>
        </div>
      </div>
    </div>

    <!-- Card de Comentarios -->
    <div class="col-sm-3 mt-5">
      <div class="card text-white bg-info mb-3" style="max-width: 15rem;">
        <div class="card-header">Comentarios</div>
        <div class="card-body">
          <h4 class="card-title">
            <?php echo $totalforms; ?>
          </h4>
          <a class="btn text-white" href="forms.php">View</a>
        </div>
      </div>
    </div>

  </div>

  <!-- Tabla de Órdenes de Cursos -->
  <div class="mx-5 mt-5 text-center">
    <p class=" bg-dark text-white p-2">Course Ordered</p>
    <?php
    if($result_courseorder->num_rows > 0){
      echo '<table class="table">
        <thead>
          <tr>
            <th scope="col">Order ID</th>
            <th scope="col">Course ID</th>
            <th scope="col">Student Email</th>
            <th scope="col">Order Date</th>
            <th scope="col">Amount</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>';
      while($row = $result_courseorder->fetch_assoc()){
        echo '<tr>';
        echo '<th scope="row">'.$row["order_id"].'</th>';
        echo '<td>'. $row["course_id"].'</td>';
        echo '<td>'.$row["stu_email"].'</td>';
        echo '<td>'.$row["order_date"].'</td>';
        echo '<td>'.$row["amount"].'</td>';
        echo '<td><form action="" method="POST" class="d-inline"><input type="hidden" name="id" value='. $row["co_id"] .'><button type="submit" class="btn btn-secondary" name="delete" value="Delete"><i class="far fa-trash-alt"></i></button></form></td>';
        echo '</tr>';
      }
      echo '</tbody>
      </table>';

      // Mostrar controles de paginación
      echo '<div class="d-flex justify-content-center">';
      echo '<ul class="pagination">';
      for ($page = 1; $page <= $total_pages; $page++) {
        echo '<li class="page-item"><a class="page-link" href="?page='.$page.'">'.$page.'</a></li>';
      }
      echo '</ul>';
      echo '</div>';

    } else {
      echo "<div class='alert alert-warning'>No hay resultados.</div>";
    }
    
    // Proceso para eliminar un registro de courseorder
    if(isset($_POST['delete'])){
      $delete_id = $_POST['id'];
      $sql_delete = "DELETE FROM courseorder WHERE co_id = '$delete_id'";
      if ($conn->query($sql_delete) === TRUE) {
        echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
      } else {
        echo "Error al eliminar el registro: " . $conn->error;
      }
    }
    ?>
  </div>

</div>

<?php include('./adminInclude/footer.php'); ?>
