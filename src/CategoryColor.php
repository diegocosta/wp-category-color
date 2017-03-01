<?php
    namespace DiegoCosta\WP;

    class CategoryColor
    {
        public static $term_name;

        public static function init($term_name)
        {
            # Set plugin term name
            self::$term_name = $term_name;

            # Add the field to the Add New Category page
    		add_action( 'category_add_form_fields',  array(__CLASS__, 'categoryFormFieldsAction'), 10, 2 );

    		# Add the field to the Edit Category page
    		add_action( 'category_edit_form_fields',  array(__CLASS__, 'categoryFormFieldsAction'), 10, 2 );

    		# Save extra taxonomy fields callback function.
    		add_action( 'edited_category', array(__CLASS__, 'saveCategoryColorAction'), 10, 2 );
    		add_action( 'create_category', array(__CLASS__, 'saveCategoryColorAction'), 10, 2 );

    		# Load assets for WP Color Picker
    		add_action( 'admin_enqueue_scripts', function() {
    			wp_enqueue_style( 'wp-color-picker' );
    	    	wp_enqueue_script( 'wp-color-picker' );
    		});

    		# Initialize Color Picker
    		add_action('admin_print_footer_scripts', function() {
                echo '<script> jQuery(".dc_cat_color").wpColorPicker(); </script>';
            });
        }

        private static function getOptionKey($term_id)
        {
            return self::$term_name . '_' . $term_id;
        }

        private static function getOptionValue($term_id)
        {
            $optionKey = self::getOptionKey($term_id);
            return get_option($optionKey);
        }

        public static function saveCategoryColorAction($term_id)
    	{
    		if ( !isset( $_POST['term_meta'] ) ) return false;

    	    
    	    $term_meta = self::getOptionValue( $term_id );

    	    foreach ( array_keys( $_POST['term_meta'] ) as $key ) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }

    	    // Save the option array.
            $name = self::getOptionKey($term_id);
    	    update_option( $name, $term_meta );
    	}

        public static function categoryFormFieldsAction($term)
    	{
    		$term_value = '';

    		if( is_object($term) && $term->term_id ) {
    			$term_meta = self::getOptionValue($term->term_id);
    			$term_value = (isset( $term_meta['category_color'] )) ? esc_attr( $term_meta['category_color'] ) : '';
    		}

    		echo '
    			<tr class="form-field">
    				<th scope="row" valign="top"><label for="term_meta[category_color]">' . __('Color') . '</label></th>
        			<td>
            			<input type="text" name="term_meta[category_color]" id="term_meta[category_color]" class="dc_cat_color" value="'. $term_value .'">
            			<p class="description">' . __('Select Color') . '</p>
        			</td>
    			</tr>';
    	}

        public static function addColorColumnOnCategoryPage()
        {
            // Add the column color in category list
            add_filter('manage_edit-category_columns', function($columns) {
                $columns[self::$term_name . '_column'] = __('Color');
                return $columns;
            });

            // Show current category color in category list
            add_filter ('manage_category_custom_column', function($content, $column_name, $term_id) {

                if($column_name == self::$term_name . '_column') {

                    $categoryColor = self::getColor($term_id);

                    if(!$categoryColor){
                        return $content;
                    }

                    return '<div class="dc_category_color_preview" style="background:'. $categoryColor .'"></div>' . $categoryColor;
                }

            }, 10,3);


            // Add Style for category preview
            add_action('admin_print_footer_scripts', function() {
                echo '<style> .dc_category_color_preview { width: 20px; height: 20px; float:left; margin-right: 10px; } </style>';
            });
        }

        public static function addColorInWPTermObject()
        {
            // Modify WP_Term Object to shows category color
            add_filter('get_term', function($term){
                $term->color = self::getColor($term->term_id);
                return $term;
            }, 10, 4);
        }

        public static function getColor($categoryId)
        {
            $cat_data = self::getOptionValue($categoryId);

            return (isset($cat_data['category_color'])) ? $cat_data['category_color'] : false;
        }

        public static function uninstall()
        {
            global $wpdb;

            $query = $wpdb->prepare( 
                "SELECT option_id 
                 FROM $wpdb->options 
                 WHERE option_name 
                 LIKE %s", self::$term_name . "%");

            foreach ($wpdb->get_results($query) as $option) {
                $wpdb->delete($wpdb->options, array( 'option_id' => $option->option_id ), array( '%d' ) );
            }
        }
    }
