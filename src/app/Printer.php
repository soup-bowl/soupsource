<?php namespace soupsource;

use Parsedown;

class Printer {
	protected $parser;
	public function __construct( Parsedown $parser ) {
		$this->parser = $parser;
	}

	/**
	 * Last function to run. Will output the recieved content onto the page.
	 *
	 * @param string $content The content to be displayed.
	 * @return void Prints to the page.
	 */
	public function display( string $content ):void {
		include SITE_THEME . '/header.php';
		echo $this->parser->text( $content );
		include SITE_THEME . '/footer.php';
	}
}
