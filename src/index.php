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
			$this->printer(
				'Error 404'
			);
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
			if ( file_exists( __DIR__ . '/pages/index.md' ) ) {
				return __DIR__ . '/pages/index.md';
			} else {
				return null;
			}
		}

		$file = __DIR__ . '/pages' . $request_uri . '.md';
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			return null;
		}
	}
	
	/**
	 * Loads the configuration variables into constants.
	 *
	 * @return void Variables in site.json are loaded into SITE_XXX in constants.
	 */
	private function loadSettings():void {
		$prefix   = 'SITE_';
		$confFile = __DIR__ . '/site.json';
		if ( file_exists( $confFile ) ) {
			$decoded = json_decode( file_get_contents( $confFile ) );
			foreach ( $decoded as $key => $value ) {
				if ( $key === 'theme' && ! empty( $value ) ) {
					$value = realpath( __DIR__ . '/themes/' . $value );
				}

				define( $prefix . strtoupper( $key ), ( ! empty( $value ) ? $value : null ) );
			}
		}

		// Defaults
		( defined( "{$prefix}TITLE" ) ? null : define( "{$prefix}TITLE", 'Undefined' ) );
	}
}

(new main(
	new Parsedown()
))->run();
