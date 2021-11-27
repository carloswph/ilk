<?php

namespace Ilk;

/**
 * Manages custom taxonomies
 *
 * @since 1.0.0
 * @package Ilk\TermBuilder
 * @author WP Helpers | Carlos Matos
 */
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
    protected $is_cat = false;
    protected $menu = true;

    public $i18n;

    /**
     * Term constructor.
     *
     * When class is instantiated
     */
    public function __construct(string $type, Builder $post)
    {
        $this->singular_name = $type;
        $this->name = \Ilk\Plural::plural($type);
        $this->slug = $this->prefix . strtolower($type);
        $this->type = $this->prefix . strtolower($type);

        $this->post = $post->type;
        $this->i18n = $post->getI18n();
 
        // Register the custom taxonomy
        add_action('init', array($this, 'register'));
    }

    /**
     * Registers the new taxonomy
     *
     * @since 1.0.0
     * @return void
     */
    public function register()
    {
        $labels = array(
            'name' => _x($this->name, $this->i18n),
            'singular_name' => _x($this->singular_name, $this->i18n),
            'search_items' =>  __( 'Search ' . $this->name, $this->i18n),
            'popular_items' => __( 'Popular ' . $this->name, $this->i18n),
            'all_items' => __('All ' . $this->name, $this->i18n),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __( 'Edit ' . $this->singular_name, $this->i18n ), 
            'update_item' => __( 'Update ' . $this->singular_name, $this->i18n ),
            'add_new_item' => __( 'Add New ' . $this->singular_name, $this->i18n ),
            'new_item_name' => __( 'New '. $this->singular_name . ' Name', $this->i18n ),
            'separate_items_with_commas' => __( 'Separate' . strtolower($this->name) . ' with commas' ),
            'add_or_remove_items' => __( 'Add or remove ' . strtolower($this->name) ),
            'choose_from_most_used' => __( 'Choose from the most used' . strtolower($this->name) ),
            'menu_name' => __($this->name, $this->i18n),
          );

        $args = array(
            'slug' => $this->slug,
            'hierarchical' => $this->is_cat,
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

    /**
     * Enables a rest endpoint to this taxonomy
     *
     * @since 1.0.0
     * @return void
     */
    public function enableRest()
    {
        $this->rest = true;
    }

    /**
     * Sets taxonomy as hierarchical (like a category)
     *
     * @since 1.0.0
     * @return void
     */
    public function likeCategory()
    {
        $this->is_cat = true;
    }

}