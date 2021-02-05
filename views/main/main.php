<?php include ROOT . '/views/layouts/header.php'; ?>
<section>
    <div class="container">
        <div class="row mb-3 mt-3">
            <div class="col-sm-12">
                <a href="/product/create"><span class="oi oi-plus"></span> Додати товар</a>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <?php foreach ($headerColumns as $columnKey => $headerColumn): ?>
                        <?php if ($headerColumn['sortable']) : ?>
                            <th><a href="/sort/<?php echo $columnKey . (isset($headerColumn['next_sort_order']) ? '/' . $headerColumn['next_sort_order'] : ''); ?>"><?php echo $headerColumn['title']; ?>
                            <?php if (isset($headerColumn['sorted_by'])) : ?>
                                <span class="<?php echo $headerColumn['sord_order_icon']; ?> float-right"></span>
                            <?php endif; ?>
                            </a></th>
                            <?php else: ?>
                            <th><a href="#"><?php echo $headerColumn['title']; ?></a></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($productsList as $product_id): 
                $product = new Product($product_id);
            ?>
                <tr>
                    <td><a href="/product/<?php echo $product->product_id; ?>"><?php echo $product->name; ?></a></td>
                    <td><img src="<?php echo $product->getThumb(); ?>" class="img-thumbnail" alt="" /></td>
                    <td><?php echo $product->date; ?></td>
                    <td><?php echo $product->user_name; ?></td>
                    <td><?php echo $product->reviews_total; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        </div>
    </div>

</section>

<?php include ROOT . '/views/layouts/footer.php'; ?>