<article>
	<?php if (!empty($userMessage)): ?>
		<h2 class="item title"><?= $userMessage; ?></h2>
    <h3 class="item title"><?= $exception->getMessage(); ?></h3>
	<?php else: ?>
		<h2 class="item title"><?= $exception->getMessage(); ?></h2>
	<?php endif; ?>
</article>
