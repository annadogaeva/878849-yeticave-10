<?php if ($error === 404): ?>
    <section class="lot-item container">
        <h2>404 Страница не найдена</h2>
        <p>Данной страницы не существует на сайте.</p>
    </section>
<?php elseif ($error === 403): ?>
    <section class="lot-item container">
        <h2>403 Доступ запрещен</h2>
        <p>У вас нет прав доступа к этой странице.</p>
    </section>
<?php else: ?>
    <section class="lot-item container">
        <h2>Произошла ошибка <?= http_response_code(); ?></h2>
    </section>
<?php endif; ?>
