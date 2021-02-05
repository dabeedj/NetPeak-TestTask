<?php
/**
 * Product class
 */
class Product
{

    /**
    * Class properties
    */
    public string $name, $user_name, $date;
    public float $price;
    public int $product_id, $reviews_total;
    private object $db;

    /**
     * Class constructor
     * @param int $product_id 
     */
    public function __construct(int $product_id)
    {
        // Get PDO resource and setting db property
        $this->db = Db::getInstance();
        //Set product_id property
        $this->product_id = $product_id;
        //Get product info
        $this->getProduct();
    }

    /**
     * Returns all products' IDs
     * @param string|string $sort_column <p>Sort column</p>
     * @param string|string $sort_order_direction <p>Sort order direction</p>
     * @return array <p>Products' IDs array
     */
    public static function getProductsList(string $sort_column = 'name', string $sort_order_direction = 'ASC') : array
    {

        // Get PDO resource and setting db property for static method
        $db = Db::getInstance();
        
        // SQL request text
        $sql = '
            SELECT 
                product_id
            FROM product
            WHERE 1
            ORDER BY `' . $sort_column . '` ' . $sort_order_direction; // Applying sort

        // Prepare SQL request
        $q = $db->pdo->prepare($sql);

        // Execute SQL request
        $q->execute();

        // Parse SQL errors
        Db::sql_error_parse($q->errorInfo());

        // Fetch and return the results
        return $q->fetchAll(PDO::FETCH_COLUMN);
    }


    /**
     * Fetch product info class instance
     * @return void
     */
    public function getProduct() : void
    {
        // SQL request text
        $sql = '
            SELECT 
                p.product_id,
                p.name,
                p.price,
                p.user_name,
                p.date,
                IFNULL(COUNT(pr.product_review_id), 0) AS reviews_total
            FROM product p
            LEFT JOIN product_review pr ON pr.product_id = p.product_id
            WHERE p.product_id = :product_id
            GROUP BY p.product_id
            ORDER BY p.product_id DESC
        ';
        
        // Prepare SQL request
        $q = $this->db->pdo->prepare($sql);

        // Bind product_id as SQL request parameter
        $q->bindParam(':product_id', $this->product_id, PDO::PARAM_INT);

        // Fetch data into class instance
        $q->setFetchMode(PDO::FETCH_INTO, $this);

        // Execute SQL request
        $q->execute();

        // Parse SQL errors
        Db::sql_error_parse($q->errorInfo());

        // Fetch the results
        $q->fetch();
    }

    /**
     * Get roduct reviews
     * @return void
     */
    public function getAverageRating() : int
    {
        // SQL request text
        $sql = '
            SELECT AVG(rating)
            FROM product_review
            WHERE product_id = :product_id
        ';
        
        // Prepare SQL request
        $q = $this->db->pdo->prepare($sql);

        // Bind product_id as SQL request parameter
        $q->bindParam(':product_id', $this->product_id, PDO::PARAM_INT);

        // Fetch data into class instance
        $q->setFetchMode(PDO::FETCH_COLUMN, 0);

        // Execute SQL request
        $q->execute();

        // Parse SQL errors
        Db::sql_error_parse($q->errorInfo());

        // Fetch and return the results
        return $q->fetch() ?: 0;
    }

    /**
     * Get product reviews
     * @return void
     */
    public function getReviews() : array
    {
        // SQL request text
        $sql = '
            SELECT *
            FROM product_review
            WHERE product_id = :product_id
            ORDER BY `date` DESC
        ';
        
        // Prepare SQL request
        $q = $this->db->pdo->prepare($sql);

        // Bind product_id as SQL request parameter
        $q->bindParam(':product_id', $this->product_id, PDO::PARAM_INT);

        // Fetch data into class instance
        $q->setFetchMode(PDO::FETCH_CLASS, 'Review');

        // Execute SQL request
        $q->execute();

        // Parse SQL errors
        Db::sql_error_parse($q->errorInfo());

        // Fetch and return the results
        return $q->fetchAll();
    }

    /**
     * Creates new product
     * @param array $values <p>Input values array</p>
     * @return integer <p>New product ID</p>
     */
    public static function createProduct($values) : int
    {
        // Get PDO resource and setting db property for static method
        $db = Db::getInstance();
        
        $db->pdo->beginTransaction();

        // SQL request text
        $sql = '
                INSERT INTO product '
                . '(name, price, user_name)'
                . 'VALUES '
                . '(:name, :price, :user_name)';

        // Prepare SQL request
        $q = $db->pdo->prepare($sql);

        $q->bindParam(':name', $values['name'], PDO::PARAM_STR);
        $q->bindParam(':price', $values['price'], PDO::PARAM_STR);
        $q->bindParam(':user_name', $values['user_name'], PDO::PARAM_STR);
        
        // Execute SQL request
        $q->execute();

        // Parse SQL errors
        Db::sql_error_parse($q->errorInfo());

        return $db->pdo->lastInsertId();
    }

    /**
     * Creates new product review
     * @param array $values <p>Input values array</p>
     * @return bool <p> Is review added</p>
     */
    public function addReview($values) : bool
    {
        // Get PDO resource and setting db property for static method
        $db = Db::getInstance();
        
        $db->pdo->beginTransaction();

        // SQL request text
        $sql = '
                INSERT INTO product_review '
                . '(product_id, user_name, rating, comment)'
                . 'VALUES '
                . '(:product_id, :user_name, :rating, :comment)';

        // Prepare SQL request
        $q = $db->pdo->prepare($sql);

        $q->bindParam(':product_id', $this->product_id, PDO::PARAM_INT);
        $q->bindParam(':user_name', $values['user_name'], PDO::PARAM_STR);
        $q->bindParam(':rating', $values['rating'], PDO::PARAM_INT);
        $q->bindParam(':comment', $values['comment'], PDO::PARAM_STR);
        
        // Execute SQL request
        $q->execute();

        // Parse SQL errors
        Db::sql_error_parse($q->errorInfo());

        // If review added successfully
        if ($db->pdo->lastInsertId()) {
            Db::commitLastTransaction(); // Commit
            return true;
        } else {
            Db::rollbackLastTransaction(); // Rollback
            return false;
        }


    }

    /**
     * Returns base64 encoded thumbnail image
     * @param int|int $max_width <p>Maximum thumbnail width, while height is calculated automaticallu 
     * @return string
     */
    public function getThumb(int $max_width = 50) : string
    {
        // Название изображения-пустышки
        $noImage = 'na-thumb.jpg';

        // Путь к папке с товарами
        $path = DEFAULT_IMAGE_PATH;

        if ($this->product_id) {
            // Путь к изображению товара
            $imagePath = getcwd() . $path . $this->product_id . '.jpg';

            if (file_exists($imagePath)) {
                // Если изображение для товара существует
                // Возвращаем путь изображения товара
                $generatedImg = $this->imageResize($imagePath, $max_width);
                if ($generatedImg) return $generatedImg;
            }
        }

        // Возвращаем путь изображения-пустышки
        return $path . $noImage;
    }

    /**
     * Returns base64 encoded thumbnail image
     * @param int|int $max_width <p>Maximum thumbnail width, while height is calculated automaticallu 
     * @return string
     */
    public function getImage() : string
    {
        // Название изображения-пустышки
        $noImage = 'na-thumb.jpg';

        // Путь к папке с товарами
        $path = DEFAULT_IMAGE_PATH;

        if ($this->product_id) {
            // Путь к изображению товара
            $imagePath = getcwd() . $path . $this->product_id . '.jpg';

            if (file_exists($imagePath)) {
                 return $path . $this->product_id . '.jpg';
            }
        }

        // Возвращаем путь изображения-пустышки
        return $path . $noImage;
    }

    /**
     * Возвращает путь к изображению
     * @param integer $id
     * @return string <p>Путь к изображению</p>
     */

    private function imageResize($imagePath, $max_width) : string
    {

        list($width, $height) = @getimagesize($imagePath);
        
        if ($width && $height) {
            $ratio = $width / $height;
        } else {
            return '';
        }

        try {
            $new_height = $max_width / $ratio;
            $new_width = $max_width;

            $src = imagecreatefromstring(file_get_contents($imagePath));
            $dst = imagecreatetruecolor($new_width, $new_height);
            
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            ob_start();
            imagejpeg($dst);
            imagedestroy($src);
            return 'data:image/jpeg;base64,' . base64_encode(ob_get_clean());
        } catch (Throwable $t) {
            return '';
        }
    }

    public static function downloadImage(string $image_url, string $path) : array
    {
        $ch = curl_init($image_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);

        $imgData = curl_exec($ch);
        curl_close ($ch);

        $fullpath = getcwd() . $path . uniqid();

        $imageHandler = fopen($fullpath, 'w');
        fwrite($imageHandler, $imgData);
        fclose($imageHandler);

        $fileInfo = @getimagesize($fullpath);

        $result = [];

        if ($fileInfo === FALSE) {
            $result = [
                'error' => [
                    'text' => 'Посилання на зображення невірне'
                ]
            ];

            if (file_exists($fullpath)) unlink($fullpath);

        } elseif (($fileInfo[2] !== IMAGETYPE_JPEG)) {
           $result = [
                'error' => [
                    'text' => 'Зображення має бути JPG'
                ]
            ];

            if (file_exists($fullpath)) unlink($fullpath);
           
        } else {
            
            $result['temp_image_uri'] = $fullpath;
        }

        return $result;
    }

    public static function saveImage(int $product_id, string $temp_image_uri, string $path) : bool
    {

        if (file_exists($temp_image_uri)) {

            $fullpath = getcwd() . $path . $product_id . '.jpg';

            if (file_exists($fullpath)) unlink($fullpath);
            
            return rename($temp_image_uri, $fullpath);
        } else {
            return false;
        }
    }

}
