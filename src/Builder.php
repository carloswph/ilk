<?php

namespace Ilk;

/**
 * Manages custom post types
 *
 * @since 1.0.0
 * @package Ilk\TermBuilder
 * @author WP Helpers | Carlos Matos
 */
class Builder
{

	/**
     * @var string
     *
     * Set post type params
     */
    public $type;
    private $slug;
    private $name;
    private $singular_name;

    protected $prefix = 'post_';
    protected $rest = false;
    protected $menu = true;

    public $app_slug = 'ilk';

    /**
     * Type constructor.
     *
     * When class is instantiated
     */
    public function __construct(string $type)
    {

        $this->singular_name = $type;
        $this->name = \Ilk\Plural::plural($type);
        $this->slug = $this->prefix . strtolower($type);
        $this->type = $this->prefix . strtolower($type);

        if(strlen($this->slug) > 20) {
            throw new Exception(Failure::ILK_SLUG_TOO_LONG);            
        }
 
        // Register the post type
        add_action('init', array($this, 'register'));

        // Post Type custom messages
        add_filter( 'post_updated_messages', array($this, 'messages'));

 
    }

	public function register()
	{
		$labels = array(
            'name'                  => __($this->name, $this->getI18n()),
            'singular_name'         => __($this->singular_name, $this->getI18n()),
            'add_new'               => __('Add New', $this->getI18n()),
            'add_new_item'          => __('Add New ' . $this->singular_name, $this->getI18n()),
            'edit_item'             => __('Edit ' . $this->singular_name, $this->getI18n()),
            'new_item'              => __('New ' . $this->singular_name, $this->getI18n()),
            'all_items'             => __('All ' . $this->name, $this->getI18n()),
            'view_item'             => __('View ' . $this->name, $this->getI18n()),
            'search_items'          => __('Search ' . $this->name, $this->getI18n()),
            'not_found'             => __('No ' . strtolower($this->name) . ' found', $this->getI18n()),
            'not_found_in_trash'    => __('No ' . strtolower($this->name) . ' found in Trash', $this->getI18n()),
            'parent_item_colon'     => '',
            'menu_name'             => __($this->name, $this->getI18n())
        );
 
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => $this->menu,
            'query_var'             => true,
            'rewrite'               => array( 'slug' => $this->slug ),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => true,
            'menu_position'         => 8,
            'supports'              => array( 'title', 'editor', 'thumbnail'),
            'yarpp_support'         => true
        );

        if($this->rest == true) {
        	$args['show_in_rest'] = true;
        	$args['rest_base'] = $this->slug;
        	$args['rest_controller_class'] = 'WP_REST_Posts_Controller';
        }
 
        register_post_type( $this->type, $args );
	}

	public function messages(array $messages)
	{
		global $post, $post_ID;
  
  		$messages[$this->type] = array(
  			0 => '', 
		    1 => sprintf( __($this->singular_name . ' updated. <a href="%s">View product</a>'), esc_url( get_permalink($post_ID) ) ),
		    2 => __('Custom field updated.'),
		    3 => __('Custom field deleted.'),
		    4 => __($this->singular_name . ' updated.'),
		    5 => isset($_GET['revision']) ? sprintf( __($this->singular_name . ' restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		    6 => sprintf( __($this->singular_name .' published. <a href="%s">View ' . strtolower($this->singular_name) . '</a>'), esc_url( get_permalink($post_ID) ) ),
		    7 => __($this->singular_name . ' saved.'),
		    8 => sprintf( __($this->singular_name . ' submitted. <a target="_blank" href="%s">Preview ' . strtolower($this->singular_name) . '</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		    9 => sprintf( __($this->singular_name . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ' . strtolower($this->singular_name) . '</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		    10 => sprintf( __($this->singular_name . ' draft updated. <a target="_blank" href="%s">Preview ' . strtolower($this->singular_name) . '</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  		);

  		return $messages;
	}

	public function enableRest()
	{
		$this->rest = true;
	}

    /**
    * Sets menu visible and/or position
    *
    * @since 1.0.0
    * @param bool|string $config
    *
    * @author WP Helpers | Carlos Matos
    * @throws new \Exception(Failure::ILK_WRONG_TYPE);
    */
	public function setMenu($config)
	{
		if(is_bool($config) && $config === false) {
			$this->menu = false;
		}

		if(is_string($config)) {
			$this->menu = $config;
		}

		if(!is_bool($config) && !is_string($config)) {
			throw new \Exception(Failure::ILK_WRONG_TYPE);
		}
	}

	public function setI18n(string $slug)
	{
		$this->app_slug = $slug;
	}
	
	public function getI18n()()
	{
		return $this->app_slug;
	}

    /**
    * Adds features support to the post type
    *
    * @since 1.0.1
    * @param array|string $features
    *
    * @author WP Helpers | Carlos Matos
    */
    public function setSupports($features)
    {
        add_post_type_support($this->slug, $features);
    }
    /**
    * Removes features support to the post type
    *
    * @since 1.0.1
    * @param array|string $features
    *
    * @author WP Helpers | Carlos Matos
    */
    public function dropSupports($features)
    {
        remove_post_type_support($this->slug, $features);
    }

}