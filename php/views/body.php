<?php
include_once('php/models/horarios/horarios_model.php');

$horarios = new Horarios();


$allFamilies = $horarios->getAllFamilies();
?>
<table class="table table-dark table-striped my-table">
    <thead>
        <tr>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">CÓDIGO FAMILIA</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">FAMILIA</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">HIJOS ACTIVOS</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allFamilies as $family) : ?>
            <tr>
                <th style="background-color:rgba(23,43,77,1) !important; color: white !important;" scope="row"><?= $family->family_code ?></th>
                <td style="background-color:rgba(23,43,77,1) !important; color: white !important;"><?= $family->family_name ?></td>
                <td style="background-color:rgba(23,43,77,1) !important; color: white !important;"><button class="btn btn-success btnDesgloseHijos" type="button" data-bs-toggle="modal" data-bs-target="#desgloseHijos" title="Desglose de hijos activos" data-id-family="<?= $family->id_family ?>"><i class="fas fa-stream"></i></button></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



</tbody>
</table>

<?php
include_once('php\views\modals\desglose_hijos.php');

?>

<script src="js\horarios.js"></script>

<script>
    
    var tfConfig = {
        rows_counter: true,
        btn_reset: {
            text: 'Limpiar'
        },
        status_bar: true,
        col_0: 'input',
        col_1: 'input',
        col_2: 'none',
    };
    var tf = new TableFilter((document.querySelector('.my-table')), tfConfig);
    tf.init();
</script>