<?php include ROOT . '/views/layouts/header.php'; ?>
<section>
    <div class="container">
        <div class="row mb-3 mt-3">
            <div class="col-sm-12">
                <a href="/"><span class="oi oi-home"></span> На головну</a>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-12 col-md-6">
                            <img class="img-fluid" src="<?php echo $product->getImage(); ?>" alt="" />
                    </div>
                    <div class="col-12 col-md-6">
                            <h2><?php echo $product->name; ?></h2>
                            <span>
                                <span><?php echo $product->price; ?> ₴</span>
                            </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">                                
            <div class="col-12">
                <h5 class="text-center">Відгуки</h5>
                <?php if ($productAverageRating) : ?>
                    <h5 class="float-right"><small>Середня оцінка: </small><?php echo $productAverageRating; ?> <span class="text-warning oi oi-star"></span></h5>
                <?php else : ?>
                    <h5 class="float-right"><small>Оцінок ще немає </small><span class="text-warning oi oi-star"></span></h5>
                <?php endif; ?>
            </div>
        </div>
        <div class="row mb-3">                                
            <?php foreach ($productReviews as $key => $productReview) : ?>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                            <strong><?php echo $productReview->getUserName(); ?> <small><small><?php echo $productReview->getDate(); ?></small></small></strong>
                            <span class="float-right"><?php echo $productReview->getRating(); ?> <span class="text-warning oi oi-star"></span></span>
                        <div class="clearfix"></div>
                        <p><?php echo $productReview->getComment(); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (isset($errors) && is_array($errors)): ?>
            <ul>
                <?php foreach ($errors as $fieldKey => $error): ?>
                    <li class="text-danger"><?php echo $error['text']; ?> <strong><?php echo (isset($postReviewFields[$fieldKey]['title'])) ? '"' . $postReviewFields[$fieldKey]['title'] . '"' : ''; ?></strong></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="/product/<?php echo $product->product_id; ?>" method="POST" enctype="application/x-www-form-urlencoded">
            <div class="row mb-3">
                <div class="col-12">
                    <h5 class="text-center">Залишити відгук</h5>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-xl-6">
                    <div class="form-group">
                        <label>Ім'я</label>
                        <input class="form-control" type="text" name="user_name" value="<?php echo isset($values['user_name']) ? $values['user_name'] : ''; ?>">
                    </div>
                </div>
                <div class="col-12 col-xl-6">
                    <div class="form-group">
                        <label>Рейтинг</label>
                        <div class="clearfix"></div>
                        <?php for ($i = 1; $i <= 10; $i++) : ?>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label"><input class="form-check-input radio-inline" type="radio" name="rating" <?php echo (isset($values['rating']) && (int)$values['rating'] === $i) ? 'checked' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></label>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="form-group">
                        <label>Коментар</label>
                        <textarea class="form-control" name="comment"><?php echo isset($values['comment']) ? $values['comment'] : ''; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row pb-3">
                <div class="col-12 text-center">
                    <input type="submit" name="submit" class="btn btn-success" value="Додати відгук">
                </div>
            </div>
        </form>
    </div>
</section>

<?php include ROOT . '/views/layouts/footer.php'; ?>