<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0,user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageTitle ?></title>
    <?php $this->load->view("basic/headLoad") ?>
    <?php echo $headComponents ?? "" ?>
</head>

<body>
    <?php echo $components ?? "" ?>
</body>

</html>