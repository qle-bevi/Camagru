<?php
$app->mustBeConfirmed();

if (isset($_POST["shoot-upload"])) {
	dd($_POST);
}

$assets = scandir(PUBLIK."assets/");
$assets = array_diff($assets, [".", ".."]);
$scripts[] = "/js/shoot.js";
$title = "Nouvelle photo";
require PARTIALS."header.php"
?>
<div class="page-title"><?= $title; ?> </div>
<div class="shoot-outer">
	<div class="shoot-left">
		<div id="mode-btns">
			<button href="#" class="awesome medium orange hide" id="btn-cam">
				<i class="fa fa-video-camera"></i> WEBCAM
			</button>
			<button href="#" class="awesome medium blue hide" id="btn-image">
				<i class="fa fa-image"></i> IMAGE
			</button>
			<input type="file" id="btn-upload">
			<span class="hide" id="infos-upload">
				<i class="fa fa-upload"></i> <span id="percent-upload"></span>
			</span>
		</div>
		<div id="editor" class="shoot-editor hide">
			<canvas id="canvas" width="1280" height="720"></canvas>
			<div class="shoot-assets">
				<?php foreach ($assets as $asset): ?>
					<img data-asset src="/assets/<?= $asset ?>" width="50" alt="<?= $asset ?>">
				<?php endforeach; ?>
			</div>
			<div class="shoot-actions">
				<ul>
					<li><strong data-key="82">R</strong> rotation</li>
					<li><strong data-key="83">S</strong> taille</li>
					<li><strong data-key="17">CTRL</strong> boost</li>
					<li>
						<strong>
							<i class="fa fa-arrow-left"></i>
							<i class="fa fa-arrow-right"></i>
							<i class="fa fa-arrow-up"></i>
							<i class="fa fa-arrow-down"></i>
						</strong>
					</li>
				</ul>
				<a href="#" class="awesome large orange" id="shoot-btn">
					<i class="fa fa-camera"></i> CAPTURER (espace) !
				</a>
				<div id="shoot-upload" class="hide">
				</div>
			</div>
		</div>

	</div>
	<div class="shoot-photos">
		<div class="page-title sub">Dernieres photos</div>
	</div>
</div>
<?php
require PARTIALS."footer.php";
