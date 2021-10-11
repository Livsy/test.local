<? header('Content-Type: text/html; charset=utf-8'); ?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

    <title><?= $data['title'] ?? ''; ?></title>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <h1><?= $data['title'] ?? ''; ?></h1>
        </div>
        <div class="col-md-2">
            <? if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == true): ?>
                <a href="/admin/logout" class="btn btn-primary mt-2">LogOut</a>
            <? else: ?>
                <a href="/admin/login" class="btn btn-primary mt-2">Login</a>
            <? endif; ?>
        </div>
    </div>
    <? include $data['params']['PATH_VIEW'].$template.'.php'; ?>
</div>
</body>
</html>