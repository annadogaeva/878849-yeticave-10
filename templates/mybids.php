
<section class="rates container">
    <h2>Мои ставки</h2>
    <?php if($my_bids): ?>
    <table class="rates__list">
        <?php foreach ($my_bids as $bid): ?>

            <?php
            $remaining_time = calculate_remaining_time($bid['end_date']);
            $is_winner = $bid['winner_id'] === $_SESSION['user']['id'] && $bid['SUM'] === $bid['start_price'];
            $is_dead = $remaining_time['status'] === 'end' && !$is_winner;
            $is_finishing = $remaining_time['hours'] === '00' && !$is_dead;

            $timer_class = $is_finishing ? 'timer--finishing' : '';

            $win_item_class = $is_winner ? 'rates__item--win' : '';
            $win_timer_class = $is_winner ? 'timer--win' : '';

            $dead_item_class = $is_dead ? 'rates__item--end' : '';
            $dead_timer_class = $is_dead ? 'timer--end' : '';
            ?>

            <tr class="rates__item <?= $win_item_class; ?> <?= $dead_item_class; ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="/<?= htmlspecialchars($bid['image']); ?>" width="54" height="40" alt="Сноуборд">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="/lot.php?lot=<?= $bid['id']; ?>"><?= htmlspecialchars($bid['name']); ?></a></h3>
                        <?php if ($is_winner): ?>
                            <p><?= htmlspecialchars($bid['lot_author_contact']); ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?= $bid['NAME']; ?>
                </td>
                <td class="rates__timer">
                    <div class="timer <?= $win_timer_class; ?> <?= $dead_timer_class; ?> <?= $timer_class; ?>">
                        <?php if ($is_winner): ?>
                            <?= 'Ставка выиграла'; ?>
                        <?php elseif ($is_dead): ?>
                            <?= 'Торги окончены'; ?>
                        <?php else: ?>
                            <?= $remaining_time['hours'] . ':' . $remaining_time['minutes'] . ':' . $remaining_time['seconds']; ?>
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
    <?php else: ?>
    <p>Пока вы не сделали ни одной ставки</p>
    <?php endif; ?>
</section>
