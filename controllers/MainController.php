<?php
/**
 * Main Page Controller
 */
class MainController
{

    /**
     * Main page action
     * @param string|string $sort_column <p>Sort column</p>
     * @param string|string $sort_order_direction <p>Sort order direction</p>
     * @return bool
     */
    public function actionIndex(string $sort_column = 'name', string $sort_order_direction = 'ASC') : bool
    {
        
        // Filter sort_column
        $sort_column_filter_options = [
            'options' => [
                'default' => 'name'
            ]
        ];

        $sort_column = filter_var($sort_column, FILTER_SANITIZE_STRING, $sort_column_filter_options);

        // Filter sort_order_direction
        $sort_order_filter_options = [
            'options' => [
                'default' => 'ASC'
            ]
        ];
        

        $sort_order_direction = filter_var($sort_order_direction, FILTER_SANITIZE_STRING, $sort_order_filter_options);

        // Just for the sake of beauty:)
        $sort_order_direction = strtoupper($sort_order_direction);

        // All products List
        $productsList = Product::getProductsList($sort_column, $sort_order_direction);
        $headerColumns = Helper::getHeaderColumns($sort_column, $sort_order_direction);

        // Include view
        require_once(ROOT . '/views/main/main.php');
        return true;
    }

}
