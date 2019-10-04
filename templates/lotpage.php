<section class="lot-item container">
    <h2><?= isset($lot_info['NAME']) ? htmlspecialchars($lot_info['NAME']) : ''; ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= isset($lot_info['image']) ? htmlspecialchars($lot_info['image']) : ''; ?>" width="730" height="548"
                     alt="<?= isset($lot_info['NAME']) ? htmlspecialchars($lot_info['NAME']) : ''; ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= isset($lot_info['name']) ? htmlspecialchars($lot_info['name']) : ''; ?></span></p>
            <p class="lot-item__description"><?= isset($lot_info['description']) ? htmlspecialchars($lot_info['description']) : ''; ?></p>
        </div>
        <div class="lot-item__right">

            <?php
            $remaining_time = isset($lot_info['end_date']) ? calculate_remaining_time($lot_info['end_date']) : '';

            $is_dead = isset($remaining_time['status']) ? $remaining_time['status'] === 'end' : '';
            $is_finishing = isset($remaining_time['hours']) ? $remaining_time['hours'] === '00' && !$is_dead : '';

            $timer_class = $is_finishing ? 'timer--finishing' : '';

            if ($_SESSION && isset($_SESSION['user']['id'])) {
                $is_author = isset($lot_info['author_id']) ? $lot_info['author_id'] === $_SESSION['user']['id'] : '';
                $is_last_bid_mine = isset($last_bid['id']) ? $last_bid['id'] === $_SESSION['user']['id'] : '';
            };

            ?>

            <?php if ($is_auth && !$is_dead && !$is_author && !$is_last_bid_mine) : ?>
                <div class="lot-item__state">
                    <div
                        class="lot-item__timer timer <?= $timer_class; ?> <?= $dead_timer_class; ?>">
                        <?= (isset($remaining_time['hours']) ? $remaining_time['hours'] : '') . ':' . (isset($remaining_time['minutes']) ? $remaining_time['minutes'] : ''); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= isset($lot_info['start_price']) ? format_price(htmlspecialchars($lot_info['start_price']), '') : ''; ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= isset($lot_info['bid_step']) ? format_price(htmlspecialchars($lot_info['bid_step']), ' р') : ''; ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="lot.php?lot=<?= isset($lot_info['id']) ? $lot_info['id'] : ''; ?>" method="post"
                          autocomplete="off">
                        <?php $classname = isset($errors['cost']) ? "form__item--invalid" : ""; ?>
                        <p class="lot-item__form-item form__item <?= $classname; ?>">
                            <label for="cost">Ваша ставка</label>
                            <?php $placeholder = isset($lot_info['start_price']) && isset($lot_info['bid_step']) ? format_price(htmlspecialchars($lot_info['start_price'] + $lot_info['bid_step']),
                                '') : ''; ?>
                            <input id="cost" type="text" name="cost" placeholder="<?= $placeholder; ?>">
                            <span class="form__error"><?= isset($errors['cost']) ? $errors['cost'] : ''; ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php endif ?>
            <div class="history">
                <h3>История ставок (<span><?= count($bids); ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bids as $bid): ?>
                        <tr class="history__item">
                            <td class="history__name"><?= isset($bid['name']) ? htmlspecialchars($bid['name']) : ''; ?></td>
                            <td class="history__price"><?= isset($bid['SUM']) ? format_price(htmlspecialchars($bid['SUM']), ' p') : ''; ?></td>
                            <td class="history__time"><?= isset($bid['DATE']) ? date_to_words(htmlspecialchars($bid['DATE'])) : ''; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
