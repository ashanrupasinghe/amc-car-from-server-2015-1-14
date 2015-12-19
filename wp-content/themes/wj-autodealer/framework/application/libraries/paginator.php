<?php
if (!defined("AT_DIR")) die('!!!');
class AT_paginator {

	private $uri;
	private $maxToDisplay = 10000;

	public function __construct() {
		$this->uri = AT_Router::get_instance();
	}

	public function setMaxToDisplay($number){
		$this->maxToDisplay = (int)$number;
	}

	public function get($uriSegment, $maxObjects, $objectsPerPage = 12, $border = 1, $center = 2, $url = FALSE, $url_first = FALSE) {
		if($maxObjects > $this->maxToDisplay){
			$maxObjects = $this->maxToDisplay;
		}
		$objectsPerPage = str_replace(array('\'','"'),array('',''), $objectsPerPage);

		// count pages
		$pages = ceil($maxObjects / $objectsPerPage);

		if ($pages <= 1) {
			return array('offset' => 0, 'per_page' => $objectsPerPage);
		}

		if (!$url) {

			$current = (int) $this->uri->segments($uriSegment);
		}	else {
			$mas = explode('/', $url);
			$current = (int) $mas[$uriSegment - 1];
		}
		switch($current) {
			case 0:
			case 1:
				$center += 2;
				break;
			case 2:
				$center += 1;
				break;
			case $current == $pages:
				$center += 3;
				break;
			case $current == ($pages-1):
				$center += 2;
				break;
			case $current == ($pages-2):
				$center += 1;
				break;
		}


		// save GET params
		$queryParams = $_GET;
		$queryString = !empty($queryParams) ? "/?" . http_build_query($queryParams) : '';

		if ($current == 0) {
			$current = 1;
		}

		// cut current page
		if (!$url) {
			$urlSegments = $this->uri->segments();
			if (isset($urlSegments[$uriSegment]) && !empty($urlSegments[$uriSegment])) {
				unset($urlSegments[$uriSegment]);
			}
			//unset($urlSegments[0]);
			$url = AT_Common::site_url(implode('/', $urlSegments));
		} else {
			$segments = explode('/', $url);
			if (isset($segments[$uriSegment - 1])) unset($segments[$uriSegment - 1]);
			$url = AT_Common::site_url(implode('/', $segments));
		}

		if ($url_first) {
			$url_first = AT_Common::site_url( rtrim( $url_first, '/' ) );
		}

		$url = rtrim($url, '/');

		$offset = ($current - 1) * $objectsPerPage;

		$result = array('list' => array(), 'current_page' => $current, 'offset' => $offset, 'per_page' => $objectsPerPage);

		// next
		if ($current != $pages) {
			$result['next'] = array('url' => $url . '/' . ($current + 1) . $queryString, 'page' => $current + 1);
		}

		// prev
		if ($current != 1) {
			if($current != 2){
				$result['prev'] = array('url' => $url . '/' . ($current - 1) . $queryString, 'page' => $current - 1);
			} else {
				$result['prev'] = array('url' => ($url_first ? $url_first : $url ) . $queryString , 'page' => $current - 1);
			}
		}
		//print_r($result['prev']);
		// last
		if ($current != $pages)
			$result['last'] = array('url' => $url . '/' . $pages . $queryString , 'page' => $pages);

		for ($i = 1; $i <= $border; $i++) {
			if ($i <= $pages) {
				if ($current != $i) {
					if($i != 1){
						$result['list'][] = array('url' => $url . '/' . $i . $queryString, 'page' => $i, 'current' => FALSE);
					} else {
						$result['first']= array('url' => ($url_first ? $url_first : $url ) . $queryString, 'page' => $i, 'current' => FALSE);
						if($current == 2 || $current == 3)
							$result['list'][] = $result['first'];
					}
				} else {
					if($i != 1){
						$result['list'][] = array('url' => $url . '/' . $i . $queryString, 'page' => $i, 'current' => TRUE);
					} else {
						$result['list'][] = array('url' => ($url_first ? $url_first : $url ) . $queryString, 'page' => $i, 'current' => TRUE);
					}
				}
			} else {
				break;
			}
		}

		if ($current - $center > $border + 1) {
			$left = $current - $center;
		} else {
			$left = $border + 1;
		}

		$right = $current + $center;

		for ($i = $left; $i <= $right; $i++) {
			if ($i <= $pages) {
				if ($current != $i) {
					$result['list'][] = array('url' => $url . '/' . $i . $queryString, 'page' => $i, 'current' => FALSE);
				} else {
					$result['list'][] = array('url' => $url . '/' . $i . $queryString, 'page' => $i, 'current' => TRUE);
				}
			} else {
				break;
			}
		}
		if ($right < $pages) {

			if ($pages - $border + 1 > $border + 1) {
				$left = $pages - $border + 1;
			} else {
				$left = $border + 1;
			}

			$right = $pages;
			for ($i = $left; $i <= $right; $i++) {
				if ($i <= $pages) {
					if ($current != $i) {
						$result['list'][] = array('url' => $url . '/' . $i . $queryString, 'page' => $i, 'current' => FALSE);
					} else {
						$result['list'][] = array('url' => $url . '/' . $i . $queryString, 'page' => $i, 'current' => TRUE);
					}
				} else {
					break;
				}
			}
		}

		return $result;
	}

	// for get params
	public function get_query_string( $param_key, $maxObjects, $objectsPerPage = 12, $border = 1, $center = 2, $url = false ) {

		if($maxObjects > $this->maxToDisplay){
			$maxObjects = $this->maxToDisplay;
		}

		// count pages
		$pages = ceil($maxObjects / $objectsPerPage);

		if ($pages <= 1) {
			return array('offset' => 0, 'per_page' => $objectsPerPage);
		}

		$queryParams = $_GET;
		$current = isset( $queryParams[$param_key] ) ? (int)$queryParams[$param_key] : 0;

		switch($current) {
			case 0:
			case 1:
				$center += 2;
				break;
			case 2:
				$center += 1;
				break;
			case $current == $pages:
				$center += 3;
				break;
			case $current == ($pages-1):
				$center += 2;
				break;
			case $current == ($pages-2):
				$center += 1;
				break;
		}

		// save GET params
		if( isset( $queryParams[$param_key] ) ) {
			unset( $queryParams[$param_key] );
		}

		if ( is_admin() ) {
			$url = get_admin_url() . $url;
		} else {
			if( !$url ) {
				$url = AT_Common::site_url( implode('/', $this->uri->segments() ));
			} else {
				$url = AT_Common::site_url( $url );
			}
		}

		if ($current == 0) {
			$current = 1;
		}

		$url = rtrim($url, '/');

		$offset = ($current - 1) * $objectsPerPage;

		$result = array('list' => array(), 'current_page' => $current, 'offset' => $offset, 'per_page' => $objectsPerPage);

		// next
		if ($current != $pages) {
			$result['next'] = array('url' => $this->_get_query_string_url( $url, $queryParams, $param_key, $current + 1 ), 'page' => $current + 1);
		}

		// prev
		if ($current != 1) {
			if($current != 2){
				$result['prev'] = array('url' => $this->_get_query_string_url( $url, $queryParams, $param_key, $current - 1 ), 'page' => $current - 1);
			} else {
				$result['prev'] = array('url' => $this->_get_query_string_url( $url, $queryParams ) , 'page' => $current - 1);
			}
		}
		//print_r($result['prev']);
		// last
		if ($current != $pages)
			$result['last'] = array('url' => $this->_get_query_string_url( $url, $queryParams, $param_key, $pages ), 'page' => $pages);

		for ($i = 1; $i <= $border; $i++) {
			if ($i <= $pages) {
				if ($current != $i) {
					if($i != 1){
						$result['list'][] = array('url' => $this->_get_query_string_url( $url, $queryParams, $param_key, $i ), 'page' => $i, 'current' => FALSE);
					} else {
						$result['first']= array('url' => $this->_get_query_string_url( $url, $queryParams ), 'page' => $i, 'current' => FALSE);
						if($current == 2 || $current == 3)
							$result['list'][] = $result['first'];
					}
				} else {
					if($i != 1){
						$result['list'][] = array('url' => $this->_get_query_string_url( $url, $queryParams, $param_key, $i ), 'page' => $i, 'current' => TRUE);
					} else {
						$result['list'][] = array('url' => $this->_get_query_string_url( $url, $queryParams ), 'page' => $i, 'current' => TRUE);
					}
				}
			} else {
				break;
			}
		}

		if ($current - $center > $border + 1) {
			$left = $current - $center;
		} else {
			$left = $border + 1;
		}

		$right = $current + $center;

		for ($i = $left; $i <= $right; $i++) {
			if ($i <= $pages) {
				if ($current != $i) {
					$result['list'][] = array('url' => $this->_get_query_string_url( $url, $queryParams, $param_key, $i ), 'page' => $i, 'current' => FALSE);
				} else {
					$result['list'][] = array('url' => $this->_get_query_string_url( $url, $queryParams, $param_key, $i ), 'page' => $i, 'current' => TRUE);
				}
			} else {
				break;
			}
		}
		if ($right < $pages) {

			if ($pages - $border + 1 > $border + 1) {
				$left = $pages - $border + 1;
			} else {
				$left = $border + 1;
			}

			$right = $pages;
			for ($i = $left; $i <= $right; $i++) {
				if ($i <= $pages) {
					if ($current != $i) {
						$result['list'][] = array('url' => $this->_get_query_string_url( $url, $queryParams, $param_key, $i ), 'page' => $i, 'current' => FALSE);
					} else {
						$result['list'][] = array('url' => $this->_get_query_string_url( $url, $queryParams, $param_key, $i ), 'page' => $i, 'current' => TRUE);
					}
				} else {
					break;
				}
			}
		}

		return $result;
	}

	private function _get_query_string_url( $url, $queryParams, $key = false, $value = false ) {
		if( $key ) {
			$queryParams[$key] = $value;
		}

		return $url . ( !is_admin() ? '/' : '' ) . ( !empty($queryParams) ? "?" . http_build_query($queryParams) : '' );
	}
}
