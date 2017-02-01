<?php
$app->mustBeConfirmed();
$title = "Gallerie";
require PARTIALS."header.php"
?>
<div class="page-title"><?= $title; ?></div>
<?php
require PARTIALS."footer.php";
