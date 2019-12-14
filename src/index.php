<?php

require_once __DIR__ . '/vendor/autoload.php';

class main {
	protected $parser;
	public function __construct( Parsedown $parser ) {
		$this->parser = $parser;

		$this->loadSettings();
	}

	/**
	 * The initially-running function that starts the process.
	 *
	 * @return void
	 */
	public function run():void {
		//echo '<pre>';var_dump($_SERVER);echo '</pre>';
		$md = $this->urlParser( $_SERVER['REQUEST_URI'] );
		if ( isset( $md ) ) {
			$this->printer(
				$this->parser->text(
					file_get_contents( $md )
				)
			);
		} else {
			http_response_code( 404 );
			$error404page = SITE_PAGES_DIR . '/404.md';
			if ( file_exists( $error404page ) ) {
				$this->printer(
					$this->parser->text(
						file_get_contents( $error404page )
					)
				);
			} else {
				$this->printer(
					'Cannot find the requested page.'
				);
			}
		}
	}

	/**
	 * Last function to run. Will output the recieved content onto the page.
	 *
	 * @param string $content The content to be displayed.
	 * @return void Prints to the page.
	 */
	private function printer( string $content ):void {
		include SITE_THEME . '/header.php';
		echo $content;
		include SITE_THEME . '/footer.php';
	}

	/**
	 * Returns the filesystem path for the markdown file, or null if not found.
	 *
	 * @param string $request_uri The request string (starting with slash).
	 * @return string Filesystem path, relative to the root.
	 */
	private function urlParser( string $request_uri ):?string {
		if ( $request_uri === '/' ) {
			if ( file_exists( SITE_PAGES_DIR . '/index.md' ) ) {
				return SITE_PAGES_DIR . '/index.md';
			} else {
				return null;
			}
		}

		$file = SITE_PAGES_DIR . $request_uri . '.md';
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			return null;
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
			$nodes .= "{$li}<a href='{$node->url}'{$aClass}>{$node->label}</li>";
		}

		return $begin . $nodes . $end;
	}
	
	/**
	 * Loads the configuration variables into constants.
	 *
	 * @return void Variables in site.json are loaded into SITE_XXX in constants.
	 */
	private function loadSettings():void {		
		// System confiurations.
		define( 'SITE_ROOT_DIR', realpath( __DIR__ ) );
		define( 'SITE_THEMES_DIR', realpath( SITE_ROOT_DIR . '/themes' ) );
		define( 'SITE_PAGES_DIR', realpath( SITE_ROOT_DIR . '/pages' ) );
		
		$conf = __DIR__ . '/site.json';
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
}

(new main(
	new Parsedown()
))->run();
