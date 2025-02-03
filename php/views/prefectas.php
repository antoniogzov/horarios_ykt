<?php
include_once('php/models/horarios/horarios_model.php');

use App\Models\EncryptionHandler; // O el namespace correcto
require_once dirname(__FILE__, 4) . '/vendor/autoload.php';

$encrypt = new EncryptionHandler();
$horarios = new Horarios();


$alList_ccos = $horarios->getAllPrefectas();
$getAllColabs = $horarios->getAllColabs();
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="card mb-4">
    <div class="row">
        <div class="card col-md-8">
            <div class="table-responsive">

                <table class="table table-striped my-table" id="tablaColabTransp">
                    <thead>
                        <tr>
                            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">N° Colab.</th>
                            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">NOMBRE</th>
                            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">CORREO INSTITUCIONAL</th>
                            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">CONTRASEÑA</th>
                            <th style="background-color:#f36e0f !important; color: white !important;" scope="col"></th>
                            <!-- <th style="background-color:#f36e0f !important; color: white !important;" scope="col">CÓDIGO iTEACH</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">ESTATUS</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">DESGLOSE DE HORARIOS</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alList_ccos as $student) :
                            $password_reversible = $student->atl_password;
                            $password = $encrypt->decryptData($password_reversible);
                        ?>
                            <tr>
                                <th title="<?= $student->no_colaborador ?>" style="background-color: #<?= $student->color_html ?> !important;" class="table-<?= $class_html ?>"><?= $student->no_colaborador ?></td>
                                <th title="<?= $student->no_colaborador ?>" style="background-color: #<?= $student->color_html ?> !important;" class="table-<?= $class_html ?>"><?= $student->colab_name ?></td>
                                <td style="background-color: #<?= $student->color_html ?> !important;" class="table-<?= $class_html ?>" scope="row"><?= $student->correo_institucional ?></td>
                                <td style="background-color: #<?= $student->color_html ?> !important;" class="table-<?= $class_html ?>" scope="row"><?= $password ?></td>
                                <td>
                                    <button class="btn btn-danger updateColab" data-no-colaborador="<?= $student->no_colaborador ?>" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z" />
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card col-md-4">
            <div class="card-header border-0">
                <h2>Agregar</h2>

                <div class="card-body">
                    <h6 class="card-title mb-3">Seleccionar colaborador</h6>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="js-example-basic-single form-select" id="select_colaborator" style="width: 95%" name="state">
                                <option disabled selected>**SELECCIONAR**</option>
                                <?php foreach ($getAllColabs as $colab) : ?>
                                    <option value="<?= $colab->no_colaborador ?>"><?= $colab->colab_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <button class="btn btn-primary btn-block confirm-button saveNewRouteTeacherRel">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<?php
include_once('php\views\modals\desglose_hijos.php');
include_once('php\views\modals\desglose_alumno.php');

?>

<script src="js\horarios.js"></script>

<script>
    var tfConfig = {
        rows_counter: true,
        paging: {
            results_per_page: ['Records: ', [20, 50, 100]]
        },
        btn_reset: {
            text: 'Limpiar'
        },
        status_bar: true,
        col_0: 'input',
        col_1: 'input',
        col_2: 'input',
        col_3: 'input',
        col_4: 'none',

    };
    var tf = new TableFilter((document.querySelector('#tablaColabTransp')), tfConfig);
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