<?php namespace soupsource;

use stdClass;

class Settings {
	/**
	 * Loads the configuration variables into constants.
	 *
	 * @return void Variables in site.json are loaded into SITE_XXX in constants.
	 */
	public function load():void {	
		// System confiurations.
		define( 'SITE_ROOT_DIR', realpath( __DIR__ . '/..' ) );
		define( 'SITE_THEMES_DIR', realpath( SITE_ROOT_DIR . '/themes' ) );
		define( 'SITE_PAGES_DIR', realpath( SITE_ROOT_DIR . '/pages' ) );
		
		$conf = __DIR__ . '/../site.json';
		if ( file_exists( $conf ) ) {
			// site.json configurations.
			$decoded = json_decode( file_get_contents( $conf ) );
			define( "SITE_TITLE", ( ! empty( $decoded->title ) ? $decoded->title : 'Undefined' ) );
			define( "SITE_THEME", ( ! empty( $decoded->theme ) ? SITE_THEMES_DIR . "/{$decoded->theme}" : null ) );
			define( "SITE_MENU", $this->menuGenerator( $decoded->menu ) );
		} else {
			# TODO
		}
	}

	/**
	 * Generates menu HTML for display.
	 *
	 * @param stdClass $menu_array
	 * @return string HTML output.
	 */
	private function menuGenerator( stdClass $menu_array ):string {
		$begin = ( ! empty( $menu_array->class ) ) ? "<ul class='{$menu_array->class}'>" : '<ul>';
		$end   = '</ul>';
		$nodes = '';

		foreach ( $menu_array->nodes as $node ) {
			$li     = ( ! empty( $node->liclass ) ) ? "<li class='{$node->liclass}'>" : '<li>';
			$aClass = ( ! empty( $node->class ) ) ? " class='{$node->class}'" : null;
			$nodes .= "{$li}<a href='{$node->url}'{$aClass}>{$node->label}</a></li>";
		}

		return $begin . $nodes . $end;
	}
}
