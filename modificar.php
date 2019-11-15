<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Modificar un departamento</title>
</head>
<body>
    <div class="container">       
        <?php
        require __DIR__ . '/auxiliar.php';
       
        const PAR = [
            'num_dep' => [
                'def' => '',
                'tipo' => TIPO_ENTERO,
                'etiqueta' => 'Número',
            ],
            'dnombre' => [
                'def' => '',
                'tipo' => TIPO_CADENA,
                'etiqueta' => 'Nombre',
            ],
            'localidad' => [
                'def' => '',
                'tipo' => TIPO_CADENA,
                'etiqueta' => 'Localidad',
            ],
        ];

        $errores = [];
        $args = comprobarParametros(PAR, REQ_POST, $errores);
        if (!isset($_GET['id'])) {
            header('Location: index.php?modificar-error=1');
            return;
        }
        $id = trim($_GET['id']);
        $pdo = conectar();
        comprobarValores($args, $id, $pdo, $errores);
        if (es_POST() && empty($errores)) {
            $sent = $pdo->prepare('UPDATE departamentos
                                      SET num_dep = :num_dep
                                        , dnombre = :dnombre
                                        , localidad = :localidad
                                    WHERE id = :id');
            $args['id'] = $id;
            $sent->execute($args);
            header('Location: index.php?modificado=1');
            return;
        }
        if (es_GET()) {
            $sent = $pdo->prepare('SELECT *
                                     FROM departamentos
                                    WHERE id = :id');
            $sent->execute(['id' => $id]);
            if (($args = $sent->fetch(PDO::FETCH_ASSOC)) === false) {
                header('Location: index.php?modificar-error=1');
                return;
            }
        }
        dibujarFormulario($args, PAR, 'Modificar', $errores);
        ?>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </div>
</body>
</html>