<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?= htmlspecialchars($lot_info['NAME']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot_info['image']; ?>" width="730" height="548"
                     alt="<?= htmlspecialchars($lot_info['NAME']); ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot_info['name']); ?></span></p>
            <p class="lot-item__description"><?= htmlspecialchars($lot_info['description']); ?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($is_auth): ?>
                <div class="lot-item__state">
                    <div
                        class="lot-item__timer timer <?php if ((calculate_remaining_time($lot_info['end_date']))[0] === '00') {
                            print('timer--finishing');
                        };
                        ?>">
                        <?= (calculate_remaining_time($lot_info['end_date']))[0] . ':' . (calculate_remaining_time($lot_info['end_date']))[1]; ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= format_price(htmlspecialchars($lot_info['start_price']),
                                    ''); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= format_price(htmlspecialchars($lot_info['bid_step']), ' р'); ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="lot.php?lot=<?= $lot_info['id']; ?>" method="post"
                          autocomplete="off">
                        <?php $classname = isset($errors['cost']) ? "form__item--invalid" : ""; ?>
                        <p class="lot-item__form-item form__item <?= $classname; ?>">
                            <label for="cost">Ваша ставка</label>
                            <?php $placeholder = format_price($lot_info['start_price'] + $lot_info['bid_step'], ''); ?>
                            <input id="cost" type="text" name="cost" placeholder="<?= $placeholder ?>">
                            <span class="form__error"><?= $errors['cost'] ?></span>
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
                            <td class="history__name"><?= htmlspecialchars($bid['name']); ?></td>
                            <td class="history__price"><?= format_price(htmlspecialchars($bid['SUM']), ' p'); ?></td>
                            <td class="history__time"><?= date_to_words(htmlspecialchars($bid['DATE'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
