<?php
include_once('php/models/horarios/horarios_model.php');

$horarios = new Horarios();


$allFamilies = $horarios->getAllFamilies();
?>
<h3>Total de familias: <?= count($allFamilies); ?></h3>
<table class="table table-striped my-table">
    <thead>
        <tr>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">CÓDIGO FAMILIA</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">FAMILIA</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">ESTATUS</th>
            <th style="background-color:#f36e0f !important; color: white !important;" scope="col">HIJOS ACTIVOS</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allFamilies as $family) : 
            $status = 'ACTIVO';
            $class_html = 'secondary';
            if ($family->status_familia==2) {
                $status = 'SUSPENCIÓN ADMINISTRATIVA';
                $class_html = 'danger';
            }
            ?>
            <tr>
                <th class="table-<?=$class_html?>" scope="row"><?= $family->family_code ?></th>
                <td class="table-<?=$class_html?>"><?= $family->family_name ?></td>
                <td class="table-<?=$class_html?>" scope="row"><?= $status ?></td>
                <td class="table-<?=$class_html?>"><button class="btn btn-success btnDesgloseHijos" type="button" data-bs-toggle="modal" data-bs-target="#desgloseHijos" title="Desglose de hijos activos" data-id-family="<?= $family->id_family ?>"><i class="fas fa-stream"></i></button></td>
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
        paging: {
            results_per_page: ['Records: ', [10, 25, 50, 100]]
        },
        btn_reset: {
            text: 'Limpiar'
        },
        status_bar: true,
        col_0: 'input',
        col_1: 'input',
        col_2: 'select',
        col_3: 'none',
    };
    var tf = new TableFilter((document.querySelector('.my-table')), tfConfig);
    tf.init();
    $('.my-table').DataTable({
        paging: false,
        ordering: false,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf'
        ]
    });
</script>