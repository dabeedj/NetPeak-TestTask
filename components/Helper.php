<?php
/**
 * Helper class
 */
class Helper
{

    /**
     * Get columns for main table
     * @param string|string $sort_column <p>Sort column</p>
     * @param string|string $sort_order_direction <p>Sort order direction</p>
     * @return array
     */
    public static function getHeaderColumns(string $sort_column = 'name', string $sort_order_direction = 'ASC') : array
    {
         
        $mainHeaderColumns = [
            'name' => [
                'title' => 'Назва товару',
                'sortable' => DEFAULT_SORTABLE,
                'sort_order_direction' => DEFAULT_SORT_DIRECTION
            ],
            'iamge' => [
                'title' => 'Зображення',
                'sortable' => false
            ],
            'date' => [
                'title' => 'Дата',
                'sortable' => DEFAULT_SORTABLE,
                'sort_order_direction' => DEFAULT_SORT_DIRECTION
            ],
            'user_name' => [
                'title' => 'Користувач',
                'sortable' => DEFAULT_SORTABLE,
                'sort_order_direction' => DEFAULT_SORT_DIRECTION
            ],
            'reviews_total' => [
                'title' => 'Відгуків',
                'sortable' => DEFAULT_SORTABLE,
                'sort_order_direction' => DEFAULT_SORT_DIRECTION
            ],
            

        ];

        if (is_array($mainHeaderColumns[$sort_column])) {
    		$mainHeaderColumns[$sort_column]['sorted_by'] = $sort_column;
    		$mainHeaderColumns[$sort_column]['sord_order_icon'] = $sort_order_direction === 'ASC' ? 'oi oi-sort-ascending' : 'oi oi-sort-descending';
    		$mainHeaderColumns[$sort_column]['next_sort_order'] = $sort_order_direction === 'ASC' ? 'desc' : 'asc';
    	}

        return $mainHeaderColumns;

    }

    /**
     * Get columns for main table
     * @param string|string $sort_column <p>Sort column</p>
     * @param string|string $sort_order_direction <p>Sort order direction</p>
     * @return array
     */
    public static function getCreateProductColumns() : array
    {
         
        $createProductColumns = [
            'name' => [
                'title' => 'Назва',
                'type' => 'text',
                'required' => true
            ],
            'image_url' => [
                'title' => 'Зобоаження (URL)',
                'type' => 'text',
                'required' => true
            ],
             'price' => [
                'title' => 'Середня ціна',
                'type' => 'number',
                'required' => true
            ],
             'user_name' => [
                'title' => 'Користувач',
                'type' => 'text',
                'required' => true
            ]
        ];

        return $createProductColumns;

    }
	
}
?>