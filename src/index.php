<?php

require_once __DIR__ . '/vendor/autoload.php';

use soupsource\Settings;
use soupsource\Printer;
use soupsource\Router;

class main {
	protected $printer;
	protected $router;
	public function __construct( Printer $printer, Router $router ) {
		$this->printer = $printer;
		$this->router  = $router;
	}

	/**
	 * The initially-running function that starts the process.
	 *
	 * @return void
	 */
	public function run():void {
		$md = $this->router->urlParser( $_SERVER['REQUEST_URI'] );
		if ( isset( $md ) ) {
			$this->printer->display( file_get_contents( $md ) );
		} else {
			http_response_code( 404 );
			$error404page = SITE_PAGES_DIR . '/404.md';
			if ( file_exists( $error404page ) ) {
				$this->printer->display( file_get_contents( $error404page ) );
			} else {
				$this->printer->display( 'Cannot find the requested page.' );
			}
		}
	}
}

( new Settings() )->load();

(new main(
	new Printer( new Parsedown ),
	new Router()
))->run();
