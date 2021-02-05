<?php include ROOT . '/views/layouts/header.php'; ?>
<section>
    <div class="container">
        <div class="row mb-3 mt-3">
            <div class="col-sm-12">
                <a href="/"><span class="oi oi-home"></span> На головну</a>
            </div>
        </div>
        <div class="row mt-3">
            <h4>Додати товар</h4>
        </div>

        <?php if (isset($errors) && is_array($errors)): ?>
            <ul>
                <?php foreach ($errors as $columnKey => $error): ?>
                    <li class="text-danger"><?php echo $error['text']; ?> <strong>"<?php echo $createProductColumns[$columnKey]['title']; ?>"</strong></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="/product/create" method="POST" enctype="application/x-www-form-urlencoded">

            <div class="row">
                <?php foreach ($createProductColumns as $columnKey => $createProductColumn) : ?>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label><?php echo $createProductColumn['title'] ?></label>
                        <input class="form-control" type="<?php echo $createProductColumn['type'] ?>" name="<?php echo $columnKey ?>" placeholder="" value="<?php echo isset($values[$columnKey]) ? $values[$columnKey] : ''; ?>">
                    </div>
                </div>
                <?php endforeach; ?>

                <input type="submit" name="submit" class="btn btn-success" value="Додати">

            </div>

        </form>

    </div>
</section>

<?php include ROOT . '/views/layouts/footer.php'; ?>

