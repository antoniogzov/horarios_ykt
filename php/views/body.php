<?php
include_once('php/models/horarios/horarios_model.php');

$horarios = new Horarios();


$allFamilies = $horarios->getAllFamilies();
?>
<table class="table table-dark table-striped">
    <thead>
        <tr>
            <th scope="col">CÓDIGO FAMILIA</th>
            <th scope="col">FAMILIA</th>
            <th scope="col">HIJOS ACTIVOS</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allFamilies as $family) : ?>
            <tr>
                <th scope="row"><?= $family->family_code ?></th>
                <td><?= $family->family_name ?></td>
                <td><button class="btn btn-success btnDesgloseHijos" type="button" data-bs-toggle="modal" data-bs-target="#desgloseHijos" title="Desglose de hijos activos" data-id-family="<?= $family->id_family ?>"><i class="fas fa-stream"></i></button></td>
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