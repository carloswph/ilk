<?php

namespace Ilk;

class TermBuilder
{

	/**
     * @var string
     *
     * Set post type params
     */
    private $type;
    public $slug;
    private $name;
    private $singular_name;

    public $post;

    protected $prefix = 'tax_';
    protected $rest = false;
    protected $menu = true;

    public $app_slug;

    /**
     * Type constructor.
     *
     * When class is instantiated
     */
    public function __construct(string $type, Builder $post)
    {
        $this->singular_name = $type;
        $this->name = \Illuminate\Support\Pluralizer::plural($type);
        $this->slug = $this->prefix . strtolower($type);
        $this->type = $this->prefix . strtolower($type);

        $this->post = $post->type;
 
        // Register the post type
        add_action('init', array($this, 'register'));
    }

    public function register()
    {
        $labels = array(
            'name' => _x($this->name, $this->getI18n),
            'singular_name' => _x($this->singular_name, $this->getI18n),
            'search_items' =>  __( 'Search ' . $this->name, $this->getI18n),
            'popular_items' => __( 'Popular ' . $this->name, $this->getI18n),
            'all_items' => __('All ' . $this->name, $this->getI18n),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __( 'Edit ' . $this->singular_name, $this->getI18n ), 
            'update_item' => __( 'Update ' . $this->singular_name, $this->getI18n ),
            'add_new_item' => __( 'Add New ' . $this->singular_name, $this->getI18n ),
            'new_item_name' => __( 'New '. $this->singular_name . ' Name', $this->getI18n ),
            'separate_items_with_commas' => __( 'Separate' . strtolower($this->name) . ' with commas' ),
            'add_or_remove_items' => __( 'Add or remove ' . strtolower($this->name) ),
            'choose_from_most_used' => __( 'Choose from the most used' . strtolower($this->name) ),
            'menu_name' => __($this->name, $this->getI18n),
          );

        $args = array(
            'slug' => $this->slug,
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => $this->slug ),
          );

        if($this->rest == true) {
            $args['show_in_rest'] = true;
            $args['rest_base'] = $this->slug;
            $args['rest_controller_class'] = 'WP_REST_Terms_Controller';
        }

        register_taxonomy($this->slug, $this->post, $args);

    }

    public function enableRest()
    {
        $this->rest = true;
    }

}