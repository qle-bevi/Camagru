<?php

$alert = $app->Flash["alert"];
if ($alert): ?>
<div class="alert <?= $alert["type"] ?> anim-play" data-delay="<?= $alert["delay"] ?? 0 ?>">
	<span class="close"><i class="fa fa-close"></i></span>
	<?= $alert["message"]; ?>
</div>
<?php endif; ?>
