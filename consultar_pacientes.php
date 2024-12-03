<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Pacientes</title>
    <link rel="stylesheet" href="styles.css">
</head>
    <body>
        <h1>Lista de Médicos</h1>
        <div class="conteiner">
            <?php
            require 'Conexion.php';

            $sql = "SELECT nombre, apellido, matricula, especialidad FROM medicos";
            $result = $pdo->query($sql);

            $medicos = $result->fetchAll(PDO::FETCH_ASSOC);

            if ($medicos) {
                foreach ($medicos as $row) {
                    echo "<div class='datos'>";
                    echo "<div class='info-datos'>";
                    echo "<p><strong>Nombre y Apellido:</strong> " . htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellido']) . "</p>";
                    echo "<p><strong>Matrícula:</strong> " . htmlspecialchars($row['matricula']) . "</p>";
                    echo "<p><strong>Especialidad:</strong> " . htmlspecialchars($row['especialidad']) . "</p>";
                    echo "</div>"; 

                    // Crear el formulario para enviar los datos del médico
                    echo "<form action='buscar_pacientes.php' method='POST' class='boton-formulario'>";
                    echo "<input type='hidden' name='nombre' value='" . htmlspecialchars($row['nombre']) . "'>";
                    echo "<input type='hidden' name='apellido' value='" . htmlspecialchars($row['apellido']) . "'>";
                    echo "<input type='hidden' name='matricula' value='" . htmlspecialchars($row['matricula']) . "'>";
                    echo "<input type='hidden' name='especialidad' value='" . htmlspecialchars($row['especialidad']) . "'>";
                    echo "<button type='submit'>Buscar Pacientes</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No se encontraron médicos</p>";
            }
            ?>
        </div>
        <button onclick="window.location.href='index.html'" type='submit'><</button>
    </body>
</html>
