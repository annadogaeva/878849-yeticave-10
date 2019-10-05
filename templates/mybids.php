<section class="rates container">
    <h2>Мои ставки</h2>
    <?php if ($my_bids): ?>
        <table class="rates__list">
            <?php foreach ($my_bids as $bid): ?>

                <?php
                $remaining_time = isset($bid['end_date']) ? calculate_remaining_time($bid['end_date']) : '';
                $is_winner = isset($bid['winner_id']) && isset($_SESSION['user']['id']) && isset($bid['SUM']) && isset($bid['start_price']) ? $bid['winner_id'] === $_SESSION['user']['id'] && $bid['SUM'] === $bid['start_price'] : '';
                $is_dead = isset($remaining_time['status']) ? $remaining_time['status'] === 'end' && !$is_winner : '';
                $is_finishing = isset($remaining_time['hours']) ? $remaining_time['hours'] === '00' && !$is_dead : '';

                $timer_class = $is_finishing ? 'timer--finishing' : '';

                $win_item_class = $is_winner ? 'rates__item--win' : '';
                $win_timer_class = $is_winner ? 'timer--win' : '';

                $dead_item_class = $is_dead ? 'rates__item--end' : '';
                $dead_timer_class = $is_dead ? 'timer--end' : '';
                ?>

                <tr class="rates__item <?= $win_item_class; ?> <?= $dead_item_class; ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="/<?= isset($bid['image']) ? htmlspecialchars($bid['image']) : ''; ?>" width="54"
                                 height="40"
                                 alt="<?= isset($bid['name']) ? htmlspecialchars($bid['name']) : ''; ?>">
                        </div>
                        <div>
                            <h3 class="rates__title"><a
                                        href="/lot.php?lot=<?= isset($bid['id']) ? $bid['id'] : ''; ?>"><?= isset($bid['name']) ? htmlspecialchars($bid['name']) : ''; ?></a>
                            </h3>
                            <?php if ($is_winner): ?>
                                <p><?= isset($bid['lot_author_contact']) ? htmlspecialchars($bid['lot_author_contact']) : ''; ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="rates__category">
                        <?= isset($bid['NAME']) ? $bid['NAME'] : ''; ?>
                    </td>
                    <td class="rates__timer">
                        <div class="timer <?= $win_timer_class; ?> <?= $dead_timer_class; ?> <?= $timer_class; ?>">
                            <?php if ($is_winner): ?>
                                <?= 'Ставка выиграла'; ?>
                            <?php elseif ($is_dead): ?>
                                <?= 'Торги окончены'; ?>
                            <?php else: ?>
                                <?= (isset($remaining_time['hours']) ? $remaining_time['hours'] : '') . ':' . (isset($remaining_time['minutes']) ? $remaining_time['minutes'] : '') . ':' . (isset($remaining_time['seconds']) ? $remaining_time['seconds'] : ''); ?>
                            <?php endif ?>
                        </div>
                    </td>
                    <td class="rates__price">
                        <?= isset($bid['SUM']) ? format_price($bid['SUM'], ' р') : ''; ?>
                    </td>
                    <td class="rates__time">
                        <?= isset($bid['DATE']) ? date_to_words(htmlspecialchars($bid['DATE'])) : ''; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Пока вы не сделали ни одной ставки</p>
    <?php endif; ?>
</section>
