<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($my_bids as $bid): ?>

            <?php
            $remaining_time = calculate_remaining_time($bid['end_date']);
            $is_winner = $bid['winner_id'] === $_SESSION['user']['id'] && $bid['SUM'] === $bid['start_price'];
            $is_dead = $remaining_time[3] == 'end' && !$is_winner;
            $is_finishing = $remaining_time[0] === '00' && !$is_dead;

            $timer_class = $is_finishing ? 'timer--finishing' : '';

            $win_item_class = $is_winner ? 'rates__item--win' : '';
            $win_timer_class = $is_winner ? 'timer--win' : '';

            $dead_item_class = $is_dead ? 'rates__item--end' : '';
            $dead_timer_class = $is_dead ? 'timer--end' : '';
            ?>

            <tr class="rates__item <?= $win_item_class; ?> <?= $dead_item_class; ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="/<?= $bid['image']; ?>" width="54" height="40" alt="Сноуборд">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="/lot.php?lot=<?= $bid['id']; ?>"><?= $bid['name']; ?></a></h3>
                        <?php if ($is_winner): ?>
                            <p><?= $bid['lot_author_contact'] ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?= $bid['NAME']; ?>
                </td>
                <td class="rates__timer">
                    <div class="timer <?= $win_timer_class; ?> <?= $dead_timer_class; ?> <?= $timer_class; ?>">
                        <?php if (!empty($win_item_class)): ?>
                            <?= 'Ставка выиграла'; ?>
                        <?php elseif (!empty($dead_timer_class)): ?>
                            <?= 'Торги окончены'; ?>
                        <?php else: ?>
                            <?= $remaining_time[0] . ':' . $remaining_time[1] . ':' . $remaining_time[2]; ?>
                        <?php endif ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?= format_price($bid['SUM'], ' р') ?>
                </td>
                <td class="rates__time">
                    <?= date_to_words(htmlspecialchars($bid['DATE'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
