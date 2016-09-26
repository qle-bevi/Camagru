            </div>
            <footer class="footer">
                Made with <i style="color: #ff00eb" class="fa fa-heart"></i> by <a href="#">qle-bevi</a>
            </footer>
		</div>
            <script type="text/javascript" src="/public/js/alert.js"></script>
            <script type="text/javascript" src="/public/js/user-menu.js"></script>
            <?php foreach ($scripts as $script): ?>
                <script type="text/javascript" src="<?= $script; ?>"></script>
            <?php endforeach; ?>
	</body>
</html>
