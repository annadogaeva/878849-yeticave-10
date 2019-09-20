<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
            </li>
        <?php endforeach?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
    <?php if($lots): ?>
        <h2>Результаты поиска по запросу «<span><?=$search ?></span>»</h2>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($lot['image']); ?>" width="350" height="260" alt="<?=$lot['name'] ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($lot['name']); ?></span>
                        <h3 class="lot__title"><a class="text-link" href="/lot.php?lot=<?= $lot['id']; ?>"><?= htmlspecialchars($lot['NAME']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= format_price(htmlspecialchars($lot['start_price'])); ?></span>
                            </div>
                            <div class="lot__timer timer
                        <?php if((calculate_remaining_time($lot['end_date']))[0] === '00'){
                                print('timer--finishing');
                            };
                            ?>">
                                <?= (calculate_remaining_time($lot['end_date']))[0] . ':' . (calculate_remaining_time($lot['end_date']))[1]; ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach?>
        </ul>
    </section>
    <?php if ($pages_count > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>"><a href="search.php/?page=<?=$page;?>"><?=$page;?></a></li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
    </ul>
    <?php endif; ?>
    <?php else: ?>
    <h2>Ничего не найдено по вашему запросу</h2>
    <?php endif ?>
</div>
