<?php
    include_once "router.php";
    $router = new Router();
    $pageName = $router->getRoutePageName()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jagofr - Personal Site<?php echo $pageName ?></title>
</head>
<body>
    <?php echo $router->returnPage(); ?>
</body>
</html>