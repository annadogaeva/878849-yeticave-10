<?php $classname = isset($errors) ? "form--invalid" : ""; ?>
<form class="form form--add-lot container <?= $classname; ?>" action="add.php" method="post"
      enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $classname = isset($errors['name']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $classname; ?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота"
                   value="<?= getPostVal('name'); ?>">
            <span class="form__error"><?= isset($errors['name']) ? $errors['name'] : ''; ?></span>
        </div>
        <?php $classname = isset($errors['category_id']) ? 'form__item--invalid' : ''; ?>
        <div class="form__item <?= $classname; ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category_id">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $category): ?>
                    <?php
                    $id = isset($category['id']) ? htmlspecialchars($category['id']) : '';
                    $name = isset($category['name']) ? htmlspecialchars($category['name']) : '';
                    $post_id = isset($_POST['category_id']) ? htmlspecialchars($_POST['category_id']) : '';
                    ?>
                    <option value="<?= $id; ?>"
                            <?php if ($post_id === $id) : ?>selected<?php endif; ?>><?= $name; ?></option>
                <?php endforeach ?>
            </select>
            <span class="form__error"><?= isset($errors['category_id']) ? $errors['category_id'] : ''; ?></span>
        </div>
    </div>
    <?php $classname = isset($errors['description']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item form__item--wide <?= $classname; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="description"
                  placeholder="Напишите описание лота"><?= getPostVal('description'); ?></textarea>
        <span class="form__error"><?= isset($errors['description']) ? $errors['description'] : ''; ?></span>
    </div>
    <?php $classname = isset($errors['file']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item form__item--file <?= $classname; ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" value="" name="image"
                   value="<?= getPostVal('image'); ?>">
            <label for="lot-img">
                Добавить
            </label>
        </div>
        <span class="form__error"><?= isset($errors['file']) ? $errors['file'] : ''; ?></span>
    </div>
    <div class="form__container-three">
        <?php $classname = isset($errors['start_price']) ? 'form__item--invalid' : ''; ?>
        <div class="form__item form__item--small <?= $classname; ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="start_price" placeholder="0"
                   value="<?= getPostVal('start_price'); ?>">
            <span class="form__error"><?= isset($errors['start_price']) ? $errors['start_price'] : ''; ?></span>
        </div>
        <?php $classname = isset($errors['bid_step']) ? 'form__item--invalid' : ""; ?>
        <div class="form__item form__item--small <?= $classname; ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="bid_step" placeholder="0" value="<?= getPostVal('bid_step'); ?>">
            <span class="form__error"><?= isset($errors['bid_step']) ? $errors['bid_step'] : ''; ?></span>
        </div>
        <?php $classname = isset($errors['end_date']) ? 'form__item--invalid' : ""; ?>
        <div class="form__item <?= $classname; ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="end_date"
                   placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= getPostVal('end_date'); ?>">
            <span class="form__error"><?= isset($errors['end_date']) ? $errors['end_date'] : '' ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
