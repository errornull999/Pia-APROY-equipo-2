<?php
// 2. Carga de recursos
require_once 'database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}


$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

$primer_dia = "$anio-$mes-01";
$dias_en_mes = date('t', strtotime($primer_dia));
$dia_semana_inicio = date('w', strtotime($primer_dia));

// Consulta de citas usando PDO
$stmt = $pdo->prepare("SELECT fecha, COUNT(*) as total FROM citas WHERE MONTH(fecha) = ? AND YEAR(fecha) = ? GROUP BY fecha");
$stmt->execute([$mes, $anio]);
$eventos = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Crea un array [fecha => total]

$nombres_meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
include 'header.php';  ?>

<div class="container">
    <div class="calendar-container">
        <div class="calendar-header">
            <h2>Agenda de Citas - <?php echo $nombres_meses[$mes - 1] . " " . $anio; ?></h2>
        </div>

        <div class="calendar-grid">
            <div class="day-name">Dom</div><div class="day-name">Lun</div><div class="day-name">Mar</div>
            <div class="day-name">Mié</div><div class="day-name">Jue</div><div class="day-name">Vie</div>
            <div class="day-name">Sáb</div>

            <?php
            for ($i = 0; $i < $dia_semana_inicio; $i++) {
                echo '<div class="day empty"></div>';
            }

            for ($dia = 1; $dia <= $dias_en_mes; $dia++) {
                $fecha_f = sprintf("%04d-%02d-%02d", $anio, $mes, $dia);
                $hoy = ($fecha_f == date('Y-m-d')) ? 'today' : '';
                $con_cita = isset($eventos[$fecha_f]) ? 'has-event' : '';
                
                echo "<div class='day $hoy $con_cita'>";
                echo "<span class='day-number'>$dia</span>";
                if (isset($eventos[$fecha_f])) {
                    echo "<span class='event-badge'>" . $eventos[$fecha_f] . " cita(s)</span>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>