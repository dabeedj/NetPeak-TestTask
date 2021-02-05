<?php

/**
 * Контроллер ProductController
 * Товар
 */
class ProductController
{

    /**
     * Action для страницы просмотра товара
     * @param integer $productId <p>id товара</p>
     */
    public function actionView($product_id)
    {
        // Get product info
        $product = new Product($product_id);
        if (!isset($product->name)) { // If product not found
            http_response_code(404);
            die;
        }
        // Get review input fields
        $postReviewFields = Review::getPostReviewFields();

        if (isset($_POST['submit'])) {// On form submit
        
            $errors = [];

            // Check for errors
            foreach ($postReviewFields as $fieldKey => $postReviewField) {
                if ((!isset($_POST[$fieldKey]) || !$_POST[$fieldKey]) && isset($postReviewField['required'])) {
                    $errors[$fieldKey]['text'] = 'Заповніть поле';
                } else {
                    $values[$fieldKey] = $_POST[$fieldKey];
                }
            }

            if (!sizeof($errors)) { // If no input errors
                // Add review
                if ($product->addReview($values)) {
                    // Reload the product page
                    header('Location: /product/' . $product->product_id);
                } else {
                    $errors['fail']['text'] = 'Не вдалося додати відгук!';
                }
            }

        }

        // Get all product reviews
        $productReviews = $product->getReviews();
        // Get product average rating
        $productAverageRating = $product->getAverageRating();

        // Include view
        require_once(ROOT . '/views/product/view.php');
        return true;
    }

    /**
    * Create product action
    */
    public function actionCreate()
    {

        $createProductColumns = Helper::getCreateProductColumns();

        if (isset($_POST['submit'])) {// On form submit

            $errors = [];

            // Check for errors
            foreach ($createProductColumns as $columnKey => $createProductColumn) {
                if ((!isset($_POST[$columnKey]) || !$_POST[$columnKey]) && isset($createProductColumn['required'])) {
                    $errors[$columnKey]['text'] = 'Заповніть поле';
                } else {
                    $values[$columnKey] = $_POST[$columnKey];
                }
            }

            if (!sizeof($errors)) { // If no input errors
                
                // Download temporary product image
                $downloadedImage = Product::downloadImage($values['image_url'], DEFAULT_IMAGE_PATH);

                if (isset($downloadedImage['error'])) { // If product image download fail
                    
                    $errors['image_url']['text'] = $downloadedImage['error']['text'];
                
                } else { //If product image download success

                    // Add product
                    $product_id = Product::createProduct($values);

                    if ($product_id) { // If product added

                        // Try to save product image to it's path
                        if (Product::saveImage($product_id, $downloadedImage['temp_image_uri'] , DEFAULT_IMAGE_PATH)) {
                            Db::commitLastTransaction();
                            // Go to main page
                            header('Location: /'); 
                        } else {
                            Db::rollbackLastTransaction();
                            $errors['image_url']['text'] = 'Не вдалось зберегти зображення';
                        }
                    }
                }
            }
        }

        // Include view
        require_once(ROOT . '/views/product/create.php');
        return true;
    }

}
