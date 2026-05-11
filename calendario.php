<?php
require_once 'database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Obtener mes y año de la URL o usar los actuales
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

// Lógica para botones Anterior/Siguiente
$prev_mes = $mes - 1;
$prev_anio = $anio;
if ($prev_mes < 1) { $prev_mes = 12; $prev_anio--; }

$next_mes = $mes + 1;
$next_anio = $anio;
if ($next_mes > 12) { $next_mes = 1; $next_anio++; }

$primer_dia = "$anio-$mes-01";
$dias_en_mes = date('t', strtotime($primer_dia));
$dia_semana_inicio = date('w', strtotime($primer_dia));

// CONSULTA MEJORADA: Obtenemos los detalles completos de las citas del mes
$stmt = $pdo->prepare("
    SELECT c.*, s.nombre as servicio, f.nombre as fotografo, u.nombre_completo as cliente 
    FROM citas c 
    JOIN servicios s ON c.servicio_id = s.id 
    JOIN fotografos f ON c.fotografos_id = f.id 
    JOIN usuarios u ON c.usuario_id = u.id 
    WHERE MONTH(c.fecha) = ? AND YEAR(c.fecha) = ? AND c.estado != 'cancelada'
");
$stmt->execute([$mes, $anio]);
$citas = $stmt->fetchAll();

// Organizamos las citas por día para facilitar la lectura en el bucle
$citas_por_dia = [];
foreach ($citas as $cita) {
    $dia_cita = (int)date('d', strtotime($cita['fecha']));
    $citas_por_dia[$dia_cita][] = $cita;
}

$nombres_meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

include 'header.php'; 
?>

<div class="calendar-section">
    <div class="calendar-controls">
        <a href="?mes=<?php echo $prev_mes; ?>&anio=<?php echo $prev_anio; ?>" class="btn-nav">&laquo; Anterior</a>
        <h2 class="calendar-title"><?php echo $nombres_meses[$mes - 1] . " " . $anio; ?></h2>
        <a href="?mes=<?php echo $next_mes; ?>&anio=<?php echo $next_anio; ?>" class="btn-nav">Siguiente &raquo;</a>
    </div>

    <div class="calendar-grid">
        <div class="day-name">Dom</div><div class="day-name">Lun</div><div class="day-name">Mar</div>
        <div class="day-name">Mié</div><div class="day-name">Jue</div><div class="day-name">Vie</div><div class="day-name">Sáb</div>

        <?php
        for ($i = 0; $i < $dia_semana_inicio; $i++) {
            echo '<div class="day empty"></div>';
        }

        for ($dia = 1; $dia <= $dias_en_mes; $dia++) {
            $has_event = isset($citas_por_dia[$dia]) ? 'has-event' : '';
            $es_hoy = ($dia == date('d') && $mes == date('m') && $anio == date('Y')) ? 'today' : '';
            
            echo "<div class='day $has_event $es_hoy' onclick='showDetails($dia)'>";
            echo "<span class='day-number'>$dia</span>";
            if (isset($citas_por_dia[$dia])) {
                echo "<span class='event-dot'></span>";
            }
            echo "</div>";
        }
        ?>
    </div>

    <div id="details-panel" class="details-panel">
        <h3>Citas del día <span id="selected-day">-</span></h3>
        <div id="details-content">
            <p class="placeholder">Selecciona un día marcado con un punto para ver los detalles.</p>
        </div>
    </div>
</div>

<script>
// Pasamos los datos de PHP a una variable de JavaScript de forma segura
const citasData = <?php echo json_encode($citas_por_dia); ?>;

function showDetails(dia) {
    const content = document.getElementById('details-content');
    const dayLabel = document.getElementById('selected-day');
    dayLabel.innerText = dia;
    
    if (citasData[dia]) {
        let html = '<ul class="details-list">';
        citasData[dia].forEach(cita => {
            html += `
                <li>
                    <strong>${cita.hora.substring(0,5)} hrs</strong> - ${cita.cliente}<br>
                    <small>📸 ${cita.servicio} con ${cita.fotografo}</small>
                    <span class="status-tag ${cita.estado}">${cita.estado}</span>
                </li>`;
        });
        html += '</ul>';
        content.innerHTML = html;
    } else {
        content.innerHTML = '<p class="placeholder">No hay citas programadas para este día.</p>';
    }
}
</script>

<?php include 'footer.php'; ?>