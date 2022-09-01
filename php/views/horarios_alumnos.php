<?php
include_once('php/models/horarios/horarios_model.php');

$horarios = new Horarios();


$allStudents = $horarios->getAllStudents();
?>
<h3>Total de alumnos: <?= count($allStudents); ?></h3>
<table class="table table-striped my-table">
    <thead>
        <tr>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">CÓDIGO ALUMNO</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">NOMBRE</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">CÓDIGO iTEACH</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">HIJOS ACTIVOS</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allStudents as $student) : ?>
            <tr>
                <th class="table-secondary"><?= $student->student_code ?></td>
                <td class="table-secondary" scope="row"><?= $student->name_student ?></td>
                <td class="table-secondary" scope="row"><?= $student->group_code ?></td>
                <td class="table-secondary"><button class="btn btn-success btnDesgloseAlumno" type="button" data-bs-toggle="modal" data-bs-target="#desgloseAlumno" title="Desglose de hijos activos" data-id-student="<?= $student->id_student ?>"><i class="fas fa-stream"></i></button></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



</tbody>
</table>

<?php
include_once('php\views\modals\desglose_hijos.php');
include_once('php\views\modals\desglose_alumno.php');

?>

<script src="js\horarios.js"></script>

<script>
    var tfConfig = {
        rows_counter: true,
        paging: {
            results_per_page: ['Records: ', [10, 25, 50, 100]]
        },
        btn_reset: {
            text: 'Limpiar'
        },
        status_bar: true,
        col_0: 'input',
        col_1: 'input',
        col_2: 'input',
        col_3: 'none',
    };
    var tf = new TableFilter((document.querySelector('.my-table')), tfConfig);
    tf.init();
</script>