<?php namespace soupsource;

class Router {
	/**
	 * Returns the filesystem path for the markdown file, or null if not found.
	 *
	 * @param string $request_uri The request string (starting with slash).
	 * @return string Filesystem path, relative to the root.
	 */
	public function urlParser( string $request_uri ):?string {
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
}
