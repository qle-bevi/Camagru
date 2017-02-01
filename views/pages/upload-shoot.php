<?php
$app->mustBeConfirmed();
header("csrf-token: ".csrf_token());
