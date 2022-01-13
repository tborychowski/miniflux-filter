<?php
require_once 'cache.php';
require_once 'utils.php';

class Miniflux {
	private $url;
	private $token;
	private $headers;


	function __construct ($settings) {
		$this->url = trim($settings['url'], '/') . '/v1';
		$this->token = $settings['token'];
		$this->headers = [ 'Content-type: application/json', "X-Auth-Token: $this->token" ];
	}


	private function get ($path, $data = null) {
		return fetch('GET', $this->url . $path, $data, $this->headers);
	}

	private function put ($path, $data = null) {
		return fetch('PUT', $this->url . $path, $data, $this->headers);
	}


	public function getFeeds () {
		$feeds = getFromCache('feeds');
		if (empty($feeds)) {
			$res = $this->get('/feeds');
			$feeds = $res['json'];
			saveToCache('feeds', $feeds);
		}
		return $feeds;
	}


	public function getFeedTitles () {
		$feeds = $this->getFeeds();
		$feedTitles = array_map(fn ($feed) => [ 'id' => $feed['id'], 'title' => $feed['title'] ], $feeds);
		return $feedTitles;
	}


	public function getFeedIcon ($id) {
		$icon = getIconFromCache($id);
		if (empty($icon)) {
			$res = $this->get("/feeds/$id/icon");
			if ($res['status'] == 200) {
				$data = $res['json'];
				$icon = saveIconToCache($id, $data);
			}
		}
		if (empty($icon)) $icon = [ 'mime_type' => 'text/plain', 'data' => '#' ];
		return $icon;
	}


	public function getUnread () {
		$res = $this->get('/entries?status=unread&limit=10000');
		$data = $res['json'];
		if (empty($data) || $data['total'] == 0) return [];

		return array_map(function ($item) {
			$feed = $item['feed'];
			return [
				'id' => $item['id'],
				'title' => $item['title'],
				'feed_id' => $feed['id'],
				'feed_url' => $feed['feed_url'],
				'site_url' => $feed['site_url'],
				'content' => $item['content'],
			];
		}, $data['entries']);
	}


	public function markAsRead ($article_ids = []) {
		if (empty($article_ids)) return;
		$data = [ 'entry_ids' => $article_ids, 'status' => 'read' ];
		$res = $this->put('/entries', $data);
		if ($res['status'] == 204) return count($article_ids);
		return 0;
	}

}
