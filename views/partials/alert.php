<?php

$alert = $app->Flash["alert"];
if ($alert): ?>
<div class="alert <?= $alert["type"] ?> anim-play">
	<span class="close"><i class="fa fa-close"></i></span>
	<?= $alert["message"]; ?>
</div>
<?php endif; ?>
