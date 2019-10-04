<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <?php if (isset($category_name) && isset($category['name'])) {
                $current_category = ($category['name'] === $category_name) ? 'nav__item--current' : '';
            };
            ?>
            <li class="nav__item <?= $current_category; ?>">
                <a href="/categories.php?category=<?= isset($category['id']) ? $category['id'] : ''; ?>"><?= isset($category['name']) ? htmlspecialchars($category['name']) : ''; ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
