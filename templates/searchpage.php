<div class="container">
    <section class="lots">
        <?php if ($lots): ?>
        <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search); ?></span>»</h2>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>

                <?php
                $remaining_time = calculate_remaining_time($lot['end_date']);

                $is_dead = $remaining_time['status'] === 'end';
                $is_finishing = $remaining_time['hours'] === '00' && !$is_dead;

                $timer_class = $is_finishing ? 'timer--finishing' : '';
                $dead_timer_class = $is_dead ? 'timer--end' : '';
                ?>

                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="/<?= htmlspecialchars($lot['image']); ?>" width="350" height="260"
                             alt="<?= htmlspecialchars($lot['name']); ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($lot['name']); ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="/lot.php?lot=<?= $lot['id']; ?>"><?= htmlspecialchars($lot['NAME']); ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span
                                    class="lot__cost"><?= format_price(htmlspecialchars($lot['start_price'])); ?></span>
                            </div>
                            <div class="lot__timer timer <?= $timer_class; ?> <?= $dead_timer_class; ?>">
                                <?php if ($is_dead): ?>
                                <?= 'Торги окончены'; ?>
                                <?php else: ?>
                                <?= $remaining_time['hours'] . ':' . $remaining_time['minutes'] ; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    </section>
    <?php if ($pages_count > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a
                    <?php if ($cur_page > 1): ?>href="/search.php/?search=<?= htmlspecialchars($search); ?>&find=Найти&page=<?= $cur_page - 1; ?>"<?php endif; ?>>Назад</a>
            </li>
            <?php foreach ($pages as $page): ?>
                <li class="pagination-item <?php if ($page === $cur_page): ?>pagination-item-active<?php endif; ?>"><a
                        href="/search.php/?search=<?= htmlspecialchars($search); ?>&find=Найти&page=<?= $page; ?>"><?= $page; ?></a></li>
            <?php endforeach; ?>
            <li class="pagination-item pagination-item-next"><a
                    <?php if ($cur_page < count($pages)): ?>href="/search.php/?search=<?= htmlspecialchars($search); ?>&find=Найти&page=<?= $cur_page + 1; ?>"<?php endif; ?>>Вперед</a>
            </li>
        </ul>
    <?php endif; ?>
    <?php else: ?>
        <h2>Ничего не найдено по вашему запросу</h2>
    <?php endif ?>
</div>
