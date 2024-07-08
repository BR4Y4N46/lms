<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Incluir archivo de conexión a la base de datos
    include('dbConnection.php');

    // Recoger los datos del formulario
    $name = $_POST['name'];
   $subject = $_POST['subject'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Escapar los valores para prevenir inyecciones SQL (opcional, depende del contexto)
    $name = mysqli_real_escape_string($conn, $name);
    $subject = mysqli_real_escape_string($conn, $subject);
    $email = mysqli_real_escape_string($conn, $email);
    $message = mysqli_real_escape_string($conn, $message);

    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO forms (name,subject,email, comentario) VALUES ('$name','$subject','$email', '$message')";

    // Ejecutar la consulta
    if (mysqli_query($conn, $sql)) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Formulario enviado correctamente.
              </div>';
        // Puedes redirigir a otra página después de la inserción si lo deseas
        header('Location: index.php');
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error al enviar el formulario: ' . mysqli_error($conn) . '
              </div>';
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>
