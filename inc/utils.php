<?php
/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
if(!function_exists("dsi_get_option")) {
	function dsi_get_option( $key = '', $type = "dsi_options", $default = false ) {
		if ( function_exists( 'cmb2_get_option' ) ) {
			// Use cmb2_get_option as it passes through some key filters.
			return cmb2_get_option( $type, $key, $default );
		}

		// Fallback to get_option if CMB2 is not loaded yet.
		$opts = get_option( $type, $default );

		$val = $default;

		if ( 'all' == $key ) {
			$val = $opts;
		} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
			$val = $opts[ $key ];
		}

		return $val;
	}
}

/**
 * Wrapper function for get_post_meta
 * @param string $key
 * @return mixed meta_value
 */
if(!function_exists("dsi_get_meta")){
	function dsi_get_meta( $key = '', $prefix = "", $post_id = "") {

		if($post_id == "")
			$post_id = get_the_ID();

		$post_type = get_post_type($post_id);

		if($prefix != "")
			return get_post_meta( $post_id, $prefix.$key, true );

		if(is_singular("servizio") || (isset($post_type) && $post_type == "servizio")){
			$prefix = '_dsi_servizio_';
			return get_post_meta( $post_id, $prefix.$key, true );
		}else if (is_singular("luogo")  || (isset($post_type) && $post_type == "luogo")) {
			$prefix = '_dsi_luogo_';
			return get_post_meta( $post_id, $prefix . $key, true );
		}else if (is_singular("struttura")  || (isset($post_type) && $post_type == "struttura")) {
			$prefix = '_dsi_struttura_';
			return get_post_meta( $post_id, $prefix . $key, true );
		}else if (is_singular("evento")  || (isset($post_type) && $post_type == "evento")) {
			$prefix = '_dsi_evento_';
			return get_post_meta( $post_id, $prefix . $key, true );
		}else if (is_singular("documento")  || (isset($post_type) && $post_type == "documento")) {
			$prefix = '_dsi_documento_';
			return get_post_meta( $post_id, $prefix . $key, true );
		}else if (is_singular("post")  || (isset($post_type) && $post_type == "post")) {
			$prefix = '_dsi_articolo_';
			return get_post_meta( $post_id, $prefix . $key, true );
		}else if (is_singular("programma_materia")  || (isset($post_type) && $post_type == "programma_materia")) {
			$prefix = '_dsi_materia_';
			return get_post_meta( $post_id, $prefix . $key, true );
		}else if (is_singular("scheda_progetto")  || (isset($post_type) && $post_type == "scheda_progetto")) {
			$prefix = '_dsi_scheda_progetto_';
			return get_post_meta( $post_id, $prefix . $key, true );
		}else if (is_singular("scheda_didattica")  || (isset($post_type) && $post_type == "scheda_didattica")) {
			$prefix = '_dsi_scheda_didattica_';
			return get_post_meta( $post_id, $prefix . $key, true );
		}else if (is_singular("circolare")  || (isset($post_type) && $post_type == "circolare")) {
            $prefix = '_dsi_circolare_';
            return get_post_meta( $post_id, $prefix . $key, true );
        }

		return get_post_meta( $post_id, $key, true );
	}
}


/**
 * Wrapper function for user avatar
 * @param object user
 * @return string url
 */
if(!function_exists("dsi_get_user_avatar")){
	function dsi_get_user_avatar( $user = false, $size=250 ) {
		if(!$user && is_user_logged_in()){
			$user = wp_get_current_user();
		}

		$avatar = get_avatar_url( $user->ID, array("size" => $size) );

		$avatar = apply_filters("dsi_avatar_url", $avatar, $user);
		return $avatar;
	}
}



/**
 * Wrapper function for user role
 * @param object user
 * @return string role
 */
if(!function_exists("dsi_get_user_role")) {
	function dsi_get_user_role( $user = false ) {
		global $wp_roles;

		if ( ! $user && is_user_logged_in() ) {
			$user = wp_get_current_user();
		}

		$roles = array();
		if ( ! empty( $user->roles ) && is_array( $user->roles ) ) {
			foreach ( $user->roles as $role ) {
				$roles[] .= translate_user_role( $role);
			}
		}
		$role = implode( ', ', $roles );
		$role = apply_filters( "dsi_user_role", $role, $user );

		return $role;
	}
}



/**
 * Wrapper function for agomenti taxonomy list
 * @return array arguomenti
 */
if(!function_exists("dsi_get_argomenti_of_post")) {
	function dsi_get_argomenti_of_post( $singular = false ) {
		global $post;

		if ( ! $singular) {
			$singular = $post;
		}

		$argomenti_terms = wp_get_object_terms( $singular->ID, 'category' );
		return $argomenti_terms;
	}
}



/**
 * Wrapper function for agomenti taxonomy list
 * @return array arguomenti
 */
if(!function_exists("dsi_get_materie_of_post")) {
	function dsi_get_materie_of_post( $singular = false ) {
		global $post;

		if ( ! $singular) {
			$singular = $post;
		}

		$argomenti_terms = wp_get_object_terms( $singular->ID, 'materia' );
		return $argomenti_terms;
	}
}


/**
 * Wrapper function for agomenti taxonomy list
 * @return array arguomenti
 */
if(!function_exists("dsi_get_classi_of_post")) {
	function dsi_get_classi_of_post( $singular = false ) {
		global $post;

		if ( ! $singular) {
			$singular = $post;
		}

		$argomenti_terms = wp_get_object_terms( $singular->ID, 'classe' );
		return $argomenti_terms;
	}
}


/**
 * Function to get mapbox access token
 * @return string accesstoken
 */
if(!function_exists("dsi_get_mapbox_access_token")) {
	function dsi_get_mapbox_access_token() {
		global $post;

		$accesstoken = dsi_get_option( "mapbox_key", "setup" );
		if ( trim( $accesstoken ) == "" ) {
			$accesstoken = DSI_ACCESSTOKEN_MAPBOX;
		}

		return $accesstoken;
	}
}

/**
 * Event date start/stop
 * @param $post
 *
 */
function dsi_get_date_evento($post){
	if($post->post_type == "evento")
		$prefix = '_dsi_evento_';
	else if($post->post_type == "scheda_progetto")
		$prefix = '_dsi_scheda_progetto_';

	$ret = "";
	$timestamp_inizio = dsi_get_meta("timestamp_inizio", $prefix, $post->ID);
	$timestamp_fine= dsi_get_meta("timestamp_fine", $prefix, $post->ID);
	if($timestamp_inizio >= $timestamp_fine){
		$ret .=  date_i18n("j M Y", $timestamp_inizio);
		$ret .= __(" alle ", "design_scuole_italia");
		$ret .=  date_i18n("H:i", $timestamp_inizio);
		return $ret;
	}

	$data_inizio = date_i18n("j M Y", $timestamp_inizio);
	$data_fine = date_i18n("j M Y", $timestamp_fine);
	$ora_inizio = date_i18n("H:i", $timestamp_inizio);
	$ora_fine = date_i18n("H:i", $timestamp_fine);
	if($data_inizio == $data_fine){
		$ret .= __("Il ", "design_scuole_italia");
		$ret .= $data_inizio;
		if($post->post_type == "evento"){
			$ret .= __(" dalle ", "design_scuole_italia");
			$ret .= $ora_inizio;
			$ret .= __(" alle ", "design_scuole_italia");
			$ret .= $ora_fine;

		}

	}else{
		$ret .= __("dal ", "design_scuole_italia");
		$ret .= $data_inizio;
		if($post->post_type == "evento") {
			$ret .= __( " alle ", "design_scuole_italia" );
			$ret .= $ora_inizio;
		}
		$ret .= __(" al ", "design_scuole_italia");
		$ret .= $data_fine;
		if($post->post_type == "evento") {
			$ret .= __( " alle ", "design_scuole_italia" );
			$ret .= $ora_fine;
		}
	}

	return $ret;

}


/**
 * @param WP_Query|null $wp_query
 * @param bool $echo
 *
 * @return string
 * Accepts a WP_Query instance to build pagination (for custom wp_query()),
 * or nothing to use the current global $wp_query (eg: taxonomy term page)
 * - Tested on WP 4.9.5
 * - Tested with Bootstrap 4.1
 * - Tested on Sage 9
 *
 * USAGE:
 *     <?php echo dsi_bootstrap_pagination(); ?> //uses global $wp_query
 * or with custom WP_Query():
 *     <?php
 *      $query = new \WP_Query($args);
 *       ... while(have_posts()), $query->posts stuff ...
 *       echo bootstrap_pagination($query);
 *     ?>
 */
function dsi_bootstrap_pagination( \WP_Query $wp_query = null, $echo = true ) {
	if ( null === $wp_query ) {
		global $wp_query;
	}
	$pages = paginate_links( [
			'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'       => '?paged=%#%',
			'current'      => max( 1, get_query_var( 'paged' ) ),
			'total'        => $wp_query->max_num_pages,
			'type'         => 'array',
			'show_all'     => false,
			'end_size'     => 3,
			'mid_size'     => 1,
			'prev_next'    => true,
			'prev_text'    => __( '« ' ),
			'next_text'    => __( ' »' ),
			'add_args'     => false,
			'add_fragment' => ''
		]
	);
	if ( is_array( $pages ) ) {
		//$paged = ( get_query_var( 'paged' ) == 0 ) ? 1 : get_query_var( 'paged' );
		$pagination = '<div class="pagination"><ul class="pagination">';
		foreach ($pages as $page) {
			$pagination .= '<li class="page-item' . (strpos($page, 'current') !== false ? ' active' : '') . '"> ' . str_replace('page-numbers', 'page-link', $page) . '</li>';
		}
		$pagination .= '</ul></div>';
		if ( $echo ) {
			echo $pagination;
		} else {
			return $pagination;
		}
	}
	return null;
}


/**
 * Ritorna l'associazione tra i type ricercabili e i post_type wordpress
 * @param string $type
 *
 * @return array
 */
function dsi_get_post_types_grouped($type = "", $category = false){
	if($type == "")
		$type = "any";
	if($type === "school")
		$post_types = array("documento", "luogo", "struttura", "page");
	else if($type === "news")
		$post_types = array("evento", "post", "circolare");
	else if($type === "education")
		$post_types = array("programma_materia", "scheda_didattica", "scheda_progetto");
	else if($type === "service")
		$post_types = array("servizio");
	else
		$post_types = array("evento", "post","circolare", "documento", "luogo", "materia", "programma_materia", "scheda_didattica", "scheda_progetto", "servizio", "struttura", "page");

	// rimuovo post types che non hanno la categoria
	if($category){
		if (($key = array_search("page", $post_types)) !== false) {
			unset($post_types[$key]);
		}

	}
	return $post_types;

}


/**
 * @param $post_type
 *
 * ritorna il gruppo di appartenenza del post type
 * @return string
 *
 */
function dsi_get_post_types_group($post_type){
	$group = "news";
	if(in_array($post_type, array("documento", "luogo", "programma_materia", "struttura", "page")))
		$group = "school";
	else if(in_array($post_type, array("programma", "scheda_didattica", "scheda_progetto")))
		$group = "education";
	else if(in_array($post_type, array("servizio")))
		$group = "service";


	return $group;
}

/**
 * @param $post_type
 *
 * ritorna il suffisso della classe relativa al colore
 * @return string
 */
function dsi_get_post_types_color_class($post_type) {
	$class = "greendark";
	$group = dsi_get_post_types_group($post_type);
	if($group == "school")
		$class = "redbrown";
	else if($group == "education")
		$class = "bluelectric";
	else if($group == "service")
		$class = "purplelight";
	return $class;
}

/**
 * @param $post_type
 *
 * ritorna il nome dell'svg utilizzato per la preview del post type
 * @return string
 */
function dsi_get_post_types_icon_class($post_type) {
	$icon = "newspaper";
	$group = dsi_get_post_types_group($post_type);
	if($group == "school")
		$icon = "school-building";
	else if($group == "education")
		$icon = "school";
	else if($group == "service")
		$icon = "hand-point-up";

	if($post_type == "documento")
		$icon = "generic-document";
		return $icon;
}


/**
 *
 * Contatore dei post totali raggruppati in base al gruppo di ricerca di appartenenza
 *
 * @param $post_types
 *
 * @return bool|int
 */
function dsi_count_grouped_posts($post_types){
	if(!is_array($post_types))
		return false;
	$count = 0;
	foreach ($post_types as $post_type){
		$count_posts = wp_count_posts($post_type);
		if(isset($count_posts->publish))
			$count += $count_posts->publish;
	}
	return $count;

}

/**
 * recupera il template in base al nome
 * @param $TEMPLATE_NAME
 *
 * @return string|null
 */
function dsi_get_template_page_url($TEMPLATE_NAME){
	$url = null;
	$pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => $TEMPLATE_NAME
	));
	if(isset($pages[0])) {
		$url = get_page_link($pages[0]->ID);
	}
	return $url;
}

/**
 * ritorna l'array dei feedback delle circolari
 * @return array
 */
function dsi_get_circolari_feedback_options(){
    return array(
        "false" => __('Nessun Feedback ', 'design_scuole_italia'),
        'presa_visione' => __('Presa Visione', 'design_scuole_italia'),
        'si_no' => __('Si / No', 'design_scuole_italia'),
        'si_no_visione' => __('Si / No / Presa Visione', 'design_scuole_italia'),
    );
}

/**
 * controlla se l'utente è abilitato a firmare la circolare
 * @param $user
 * @param $post
 * @return bool
 */
function dsi_user_can_sign_circolare($user, $post){

    $destinatari_circolari = dsi_get_meta("destinatari_circolari", "", $post->ID);
    if($destinatari_circolari == "all"){
        return true;
    }elseif ($destinatari_circolari == "ruolo"){
        $ruoli_circolari = dsi_get_meta("ruoli_circolari", "", $post->ID);
        if( array_intersect($ruoli_circolari, $user->roles ) ) {
            return true;
        }
    }elseif ($destinatari_circolari == "gruppo"){
        $gruppi_circolari = dsi_get_meta("gruppi_circolari", "", $post->ID);
        if(is_object_in_term($user->ID, "gruppo-utente", $gruppi_circolari)){
            return true;
        }
    }

    return false;
}


/**
 * Controllo se l'utente ha già firmato la circolare
 * @param $user
 * @param $post
 * @return bool
 */
function dsi_user_has_signed_circolare($user, $post){
    $signed = get_post_meta($post->ID, "_dsi_has_signed", true);
    if(!$signed)
        $signed = array();
    if(in_array($user->ID, $signed)){
        $sign = get_user_meta($user->ID, "_dsi_signed_".$post->ID, true);
        if($sign)
            return $sign;

        return true;
    }
    return false;
}

/**
 * check if is circolare
 * @param $post
 * @return bool
 */
function dsi_is_circolare($post){

    if($post->post_type == "circolare")
        return true;

    return false;
}


/**
 * check if is circolare
 * @param $post
 * @return bool
 */
function dsi_is_albo($post){

    if(has_term("albo-pretorio", "tipologia-documento", $post))
        return true;

    return false;
}