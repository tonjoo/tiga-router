<?php
/**
 * Parses routes of the following form:
 *
 * "/user/{name}/{id:[0-9]+}"
 *
 * @ref https://github.com/nikic/FastRoute/blob/master/src/RouteParser/Std.php
 */

class RouteParser {
	const VARIABLE_REGEX = <<<'REGEX'
~\{
    \s* ([a-zA-Z][a-zA-Z0-9_]*) \s*
    (?:
        : \s* ([^{}]*(?:\{(?-1)\}[^{}]*)*)
    )?
\}~x
REGEX;
	const DEFAULT_DISPATCH_REGEX = '[^/]+';

	public function parse( $route ) {
		if ( ! preg_match_all(
			self::VARIABLE_REGEX, $route, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER
		) ) {
			return [ $route ];
		}

		$offset = 0;
		$routeData = [];
		foreach ( $matches as $set ) {
			if ( $set[0][1] > $offset ) {
				$routeData[] = substr( $route, $offset, $set[0][1] - $offset );
			}
			$routeData[] = [
				$set[1][0],
				isset( $set[2] ) ? trim( $set[2][0] ) : self::DEFAULT_DISPATCH_REGEX,
			];
			$offset = $set[0][1] + strlen( $set[0][0] );
		}

		if ( $offset != strlen( $route ) ) {
			$routeData[] = substr( $route, $offset );
		}

		return $routeData;
	}

	public function params( $routeData ) {
		$params = array();
		foreach ( $routeData as $rd ) {
			if ( is_array( $rd ) && ! empty( $rd[0] ) && ! empty( $rd[1] ) ) {
				$params[ $rd[0] ] = $rd[1];
			}
		}
		return $params;
	}
}
