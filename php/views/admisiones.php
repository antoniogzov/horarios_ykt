<?php
include_once('php/models/horarios/horarios_model.php');

$horarios = new Horarios();


$allStudents = $horarios->getAllStudentsAdmisions();
?>
<h3>Total de alumnos: <?= count($allStudents); ?></h3>
<table class="table table-striped my-table" id="tablaHorarios">
    <thead>
        <tr>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">NOMBRE</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">FECHA DE NACIMIENTO</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">EDAD</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">GÉNERO</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">GRADO ACADÉMICO</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">CAMPUS</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">CICLO ESCOLAR</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">CÓDIGO iTEACH</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">ESTATUS</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">FECHA DE SOLICITUD</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">DIRECCIÓN FAMILIA</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allStudents as $student) :
            $gender = 'HOMBRE';
            if ($student->gender == 1) {
                $gender = 'MUJER';
            }
            switch ($student->id_status) {
                case '3':
                    $txt_status = "Rechazado";
                    break;
                case '4':
                    $txt_status = "Inscripción cancelada";
                    break;
                case '2':
                    $txt_status = "Inscrito";
                    break;
                case '7':
                    $txt_status = "No admitido";
                    break;
                case '8':
                    $txt_status = "Dado de Baja";
                    break;
                case '11':
                    $txt_status = "En proceso de baja";
                    break;

                default:
                    $txt_status = "En proceso de admisión";
                    break;
            }
            $class_boot = str_replace('label label-', '', $student->class);
            $status = 'ACTIVO';
            $class_html = 'secondary';
            /* if ($student->status_familia == 2) {
                $status = 'SUSPENCIÓN ADMINISTRATIVA';
                $class_html = 'danger';
            } */

        ?>
            <tr id="<?= $student->id_student ?>">
                <td scope="row"><?= strtoupper($student->name_student) ?></td>
                <td scope="row"><?= $student->birthday ?></td>
                <td scope="row"><?= $student->age ?></td>
                <td scope="row"><?= $gender ?></td>
                <td scope="row"><?= $student->academic_grade ?></td>
                <td scope="row"><?= $student->campus ?></td>
                <td scope="row"><?= $student->ciclo_escolar ?></td>
                <td scope="row"><?= $student->iTeach_code ?></td>
                <td scope="row"><span class="badge rounded-pill bg-<?= $class_boot ?>"><?= $txt_status ?></span></td>
                <td scope="row"><?= $student->date_in ?></td>
                <td scope="row"><?= $horarios->getAllStudentAddress($student->id_student) ?></td>
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
        col_3: 'select',
        col_4: 'none',
        col_5: 'select',
        col_6: 'select',
        col_8: 'select',
    };
    var tf = new TableFilter((document.querySelector('#tablaHorarios')), tfConfig);
    tf.init();

    $('#tablaHorarios').DataTable({
        paging: false,
        ordering: false,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf'
        ]
    });
</script>