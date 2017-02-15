<?php
    namespace DiegoCosta\WP;

    class CategoryColor
    {
        public static $instance = null;
        public static $categoryOptionKey;

        public static function init($category_option)
        {
            if(self::$instance == null){
                self::$categoryOptionKey = $category_option;
                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct()
        {
            // Add the field to the Add New Category page
    		add_action( 'category_add_form_fields', array($this, 'input'), 10, 2 );

    		// Add the field to the Edit Category page
    		add_action( 'category_edit_form_fields',  array($this, 'input'), 10, 2 );

    		// Save extra taxonomy fields callback function.
    		add_action( 'edited_category', array($this, 'save'), 10, 2 );
    		add_action( 'create_category', array($this, 'save'), 10, 2 );

    		// Load assets for WP Color Picker
    		add_action( 'admin_enqueue_scripts', function() {
    			wp_enqueue_style( 'wp-color-picker' );
    	    	wp_enqueue_script( 'wp-color-picker' );
    		});

    		// Initialize Color Picker
    		add_action('admin_print_footer_scripts', function() {
                echo '<script> jQuery(".dc_cat_color").wpColorPicker(); </script>';
                echo '<style> .dc_category_color_preview { width: 20px; height: 20px; float:left; margin-right: 10px; } </style>';
            });

            // Add the column color in category list
            add_filter('manage_edit-category_columns', function($columns) {
                $columns[self::$categoryOptionKey . '_column'] = 'Color';
                return $columns;
            });

            // Show current category color in category list
            add_filter ('manage_category_custom_column', function($content, $column_name, $term_id) {

                if($column_name == self::$categoryOptionKey . '_column') {

                    $categoryColor = self::getColor($term_id);

                    if(!$categoryColor){
                        return $content;
                    }

                    return '<div class="dc_category_color_preview" style="background:'. $categoryColor .'"></div>' . $categoryColor;
                }

            }, 10,3);

            // Modify WP_Term Object to shows category color
            add_filter('get_term', function($term){
                $term->color = self::getColor($term->term_id);
                return $term;
            }, 10, 4);

        }

        private function id($term_id)
        {
            return self::categoryOptionKey . '_' . $term_id;
        }

        public function save($term_id)
    	{
    		if ( !isset( $_POST['term_meta'] ) )
    			return false;

    	    $name = $this->id($term_id);
    	    $term_meta = get_option( $name );

    	    foreach ( array_keys( $_POST['term_meta'] ) as $key ) {
                if ( isset ( $_POST['term_meta'][$key] ) ){
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }

    	    // Save the option array.
    	    update_option( $name, $term_meta );
    	}

        public function input($term)
    	{
    		$term_value = '';

    		if( is_object($term) && $term->term_id ) {
    			$term_meta = $this->id($term->term_id);
    			$term_value = esc_attr( $term_meta['cat_color'] ) ? esc_attr( $term_meta['cat_color'] ) : '';
    		}

    		echo '
    			<tr class="form-field">
    				<th scope="row" valign="top"><label for="term_meta[cat_color]">Color</label></th>
        			<td>
            			<input type="text" name="term_meta[cat_color]" id="term_meta[cat_color]" class="dc_cat_color" value="'. $term_value .'">
            			<p class="description">Choose one color</p>
        			</td>
    			</tr>';
    	}


        public static function getColor($categoryId)
        {
            $cat_data = get_option(sprintf("%s_%s", self::$categoryOptionKey, $categoryId));

            return (isset($cat_data['cat_color'])) ? $cat_data['cat_color'] : false;
        }
    }