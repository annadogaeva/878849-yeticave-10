<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
        <?php if(isset($category_name)) {
            $current_category = ($category['name'] === $category_name) ? 'nav__item--current' : '';
        };
         ?>
            <li class="nav__item <?= $current_category ?>">
                <a href="/categories.php?category=<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
