<?php

require_once __DIR__ . '/vendor/autoload.php';

class main {
	protected $parser;
	public function __construct( Parsedown $parser ) {
		$this->parser = $parser;
	}

	/**
	 * The initially-running function that starts the process.
	 *
	 * @return void
	 */
	public function run():void {
		echo '<pre>';var_dump($_SERVER);echo '</pre>';
		$md = $this->urlParser( $_SERVER['REQUEST_URI'] );
		if ( isset( $md ) ) {
			echo $this->parser->text(
				file_get_contents( $md )
			);
		} else {
			echo 'Error 404';
		}
	}

	/**
	 * Returns the filesystem path for the markdown file, or null if not found.
	 *
	 * @param string $request_uri The request string (starting with slash).
	 * @return string Filesystem path, relative to the root.
	 */
	private function urlParser( $request_uri ):?string {
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
}

(new main(
	new Parsedown()
))->run();
