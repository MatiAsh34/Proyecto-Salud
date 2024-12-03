<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Medicamentos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Consultar Medicamentos del Paciente</h1>

    <div class="conteiner">
        <?php
        require 'Conexion.php';

        $sql = "SELECT nombre, apellido, celular FROM pacientes";
        $result = $pdo->query($sql);

        $pacientes = $result->fetchAll(PDO::FETCH_ASSOC);

        if ($pacientes) {
            foreach ($pacientes as $row) {
                echo "<div class='datos'>";
                echo "<div class='info-datos'>";
                echo "<p><strong>Nombre y Apellido:</strong> " . htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellido']) . "</p>";
                echo "<p><strong>Celular:</strong> " . htmlspecialchars($row['celular']) . "</p>";
                echo "</div>"; 

                echo "<form action='buscar_medicamentos.php' method='POST' class='boton-formulario'>";
                echo "<input type='hidden' name='nombre' value='" . htmlspecialchars($row['nombre']) . "'>";
                echo "<input type='hidden' name='apellido' value='" . htmlspecialchars($row['apellido']) . "'>";
                echo "<input type='hidden' name='celular' value='" . htmlspecialchars($row['celular']) . "'>";
                echo "<button type='submit'>Buscar Medicamentos</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No se encontraron pacientes</p>";
        }
        ?>
    </div>
    <button onclick="window.location.href='index.html'" type='submit'><</button>

</body>
</html>
