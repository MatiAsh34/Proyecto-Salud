<?php
require 'Conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Medicamentos</title>
    <link rel="stylesheet" href="styles.css"> <!-- Vinculación al archivo CSS externo -->
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST['nombre'], $_POST['apellido'], $_POST['celular'])) {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $celular = $_POST['celular'];

            // Consulta para obtener el ID del paciente
            $sql_paciente = "SELECT id FROM pacientes 
                             WHERE nombre = :nombre 
                               AND apellido = :apellido 
                               AND celular = :celular";
            $stmt_paciente = $pdo->prepare($sql_paciente);
            $stmt_paciente->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt_paciente->bindParam(':apellido', $apellido, PDO::PARAM_STR);
            $stmt_paciente->bindParam(':celular', $celular, PDO::PARAM_STR);
            $stmt_paciente->execute();
            
            $paciente = $stmt_paciente->fetch(PDO::FETCH_ASSOC);
            
            if ($paciente) {
                $paciente_id = $paciente['id'];

                // Obtener IDs de medicamentos para el paciente
                $sql_medicamentos = "SELECT rela_medicamento, dosis, frecuencia FROM medicamentos_pacientes WHERE rela_paciente = :paciente_id";
                $stmt_medicamentos = $pdo->prepare($sql_medicamentos);
                $stmt_medicamentos->bindParam(':paciente_id', $paciente_id, PDO::PARAM_INT);
                $stmt_medicamentos->execute();
                $medicamentos_paciente = $stmt_medicamentos->fetchAll(PDO::FETCH_ASSOC);

                if ($medicamentos_paciente) {
                    echo "<h3>Medicamentos del Paciente:</h3>";
                    foreach ($medicamentos_paciente as $medicamento_rel) {
                        // Obtener detalles del medicamento desde la tabla medicamentos
                        $sql_detalle_medicamento = "SELECT nombre_comercial, laboratorio_titular FROM medicamentos WHERE id_medicamento = :medicamento_id";
                        $stmt_detalle = $pdo->prepare($sql_detalle_medicamento);
                        $stmt_detalle->bindParam(':medicamento_id', $medicamento_rel['rela_medicamento'], PDO::PARAM_INT);
                        $stmt_detalle->execute();
                        $detalle_medicamento = $stmt_detalle->fetch(PDO::FETCH_ASSOC);

                        if ($detalle_medicamento) {
                            echo "<div class='results'>";
                            echo "<p><strong>Nombre Comercial:</strong> " . htmlspecialchars($detalle_medicamento['nombre_comercial']) . "</p>";
                            echo "<p><strong>Laboratorio:</strong> " . htmlspecialchars($detalle_medicamento['laboratorio_titular']) . "</p>";
                            echo "<p><strong>Dosis:</strong> " . htmlspecialchars($medicamento_rel['dosis']) . "</p>";
                            echo "<p><strong>Frecuencia:</strong> " . htmlspecialchars($medicamento_rel['frecuencia']) . "</p>";
                            echo "</div>";
                            echo "<hr>";
                        }
                    }
                } else {
                    echo "<p>No se encontraron medicamentos para el paciente especificado.</p>";
                }
            } else {
                echo "<p>No se encontró el paciente con los datos proporcionados.</p>";
            }
        }
        ?>
        <button onclick="window.location.href='consultar_medicamentos.php'" class="back-button"><</button>
    </div>
</body>
</html>
