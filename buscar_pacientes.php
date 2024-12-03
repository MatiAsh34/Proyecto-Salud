<?php
require 'Conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Pacientes</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST['nombre'], $_POST['apellido'], $_POST['matricula'], $_POST['especialidad'])) {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $matricula = $_POST['matricula'];
            $especialidad = $_POST['especialidad'];

            $sql = "SELECT id FROM medicos 
                    WHERE nombre = :nombre 
                      AND apellido = :apellido 
                      AND matricula = :matricula 
                      AND especialidad = :especialidad";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
            $stmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);
            $stmt->bindParam(':especialidad', $especialidad, PDO::PARAM_STR);
            $stmt->execute();
            
            $medico = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($medico) {
                $medico_id = $medico['id'];
 

                $sql_pacientes = "SELECT rela_paciente FROM medico_pacientes WHERE rela_medico = :medico_id";
                $stmt_pacientes = $pdo->prepare($sql_pacientes);
                $stmt_pacientes->bindParam(':medico_id', $medico_id, PDO::PARAM_INT);
                $stmt_pacientes->execute();
                $ids_pacientes = $stmt_pacientes->fetchAll(PDO::FETCH_COLUMN);

                if ($ids_pacientes) {
                    $sql_datos_pacientes = "SELECT nombre, apellido, fecha_nacimiento, celular FROM pacientes WHERE id IN (" . implode(',', array_fill(0, count($ids_pacientes), '?')) . ")";
                    $stmt_datos_pacientes = $pdo->prepare($sql_datos_pacientes);
                    $stmt_datos_pacientes->execute($ids_pacientes);
                    $datos_pacientes = $stmt_datos_pacientes->fetchAll(PDO::FETCH_ASSOC);

                    echo "<h3>Datos de los Pacientes:</h3>";
                    foreach ($datos_pacientes as $paciente) {
                        echo "<div class='results'>";
                        echo "<p><strong>Nombre y Apellido:</strong> " . htmlspecialchars($paciente['nombre']) . " " . htmlspecialchars($paciente['apellido']) . "</p>";
                        echo "<p><strong>Fecha de Nacimiento:</strong> " . htmlspecialchars($paciente['fecha_nacimiento']) . "</p>";
                        echo "<p><strong>Celular:</strong> " . htmlspecialchars($paciente['celular']) . "</p>";
                        echo "</div>";
                        echo "<hr>";
                    }
                } else {
                    echo "<p>No se encontraron pacientes para el médico especificado.</p>";
                }
            } else {
                echo "<p>No se encontró el médico con los datos proporcionados.</p>";
            }
        }
        ?>
        <button onclick="window.location.href='consultar_pacientes.php'" class="back-button"><</button>
    </div>
</body>
</html>