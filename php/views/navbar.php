<nav style=" background-color: #172b4d !important;" class="navbar navbar-expand-xl navbar-dark bg-dark" aria-label="Sixth navbar example">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">HORARIOS DE TRANSPOTES YKT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample06" aria-controls="navbarsExample06" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample06">
            <ul class="navbar-nav me-auto mb-2 mb-xl-0">

                <?php if (isset($_GET['module'])) : ?>
                    <?php if (($_GET['module'] == 'familias') && isset($_GET['module'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="?module=familias">Familias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?module=alumnos">Alumnos</a>
                        </li>

                    <?php elseif (($_GET['module'] == 'alumnos') && isset($_GET['module'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?module=familias">Familias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="?module=alumnos">Alumnos</a>
                        </li>

                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="?module=familias">Familias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?module=alumnos">Alumnos</a>
                        </li>
                    <?php endif; ?>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="?module=familias">Familias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?module=alumnos">Alumnos</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main>
    <div class="container py-4">
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-5">