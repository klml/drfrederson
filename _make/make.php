<?php
/*
 * @name make.php
 * @class MakeSite
 * @description ganerate a static site
 * @author 2dkun
 */

require_once 'lib/Markdown.php';
require_once 'lib/Spyc.php';
require_once 'lib/Tools.php';

class MakeSite {
	protected $config;
	protected $wwwPath;
	protected $filePath;
	protected $posts;	// all posts data
	protected $pages;	// all pages data
	protected $tmplData;	// used for tmpl

	public function __construct() {
		$this->config = spyc_load_file('config.yml');
		$this->wwwPath = $this->config['path'];
		$this->filePath = $this->wwwPath; // ?

		foreach ($this->filePath as &$fp) {
			$fp = '.' . $fp; // used ?
		}
		foreach ($this->wwwPath as &$wp) {
			$wp = $this->config['baseurl'] . $wp;
		}
		
		$this->clearBlog();
		$this->initPosts();
		$this->initPage();
		$this->initTmplData();

		$this->createIndex();
		$this->createPages();
		$this->createPosts();
		//$this->createArchives();
	}
	
	// remake blog/
	protected function clearBlog() {
		exec('rm -r ' . $this->filePath['blog']);
		mkdir($this->filePath['blog']);
		mkdir($this->filePath['olderPage']);
	}

	// init posts data
	protected function initPosts() {
		$this->posts = array();
		$years = array();
		$months = array();
		$days = array();

		$handle = opendir($this->filePath['post']);
		if ($handle) {
			while (($file = readdir($handle)) !== false) {
				if (preg_match('/\.md$/', $file)) {
					if (is_file($filePath = $this->filePath['post'] . $file)) {
						$item = array();
						
						$tmpInfo = explode('-', $file);
						$item['year'] = $tmpInfo[0];
						$item['month'] = $tmpInfo[1];
						$item['day'] = $tmpInfo[2];
						$item['Month'] = date('M', mktime(0, 0, 0, $item['month'], $item['day'], $item['year']));
						$item['url'] = $this->wwwPath['blog'] . $item['year'] . '/' . $item['month'] . '/' . preg_replace('/^\S{11}|\.md$/i', '', $file) . '/';
						$item['filePath'] = $this->filePath['blog'] . $item['year'] . '/' . $item['month'] . '/' . preg_replace('/^\S{11}|\.md$/i', '', $file) . '/index.html';
						
						$tmpInfo = getYamlObj($filePath);
						$item['title'] = $tmpInfo['title'];
						$item['layout'] = $this->filePath['layout'] . $tmpInfo['layout'] . 'php';
						$item['comment'] = $tmpInfo['comment'];

						$item['content'] = getMdHtml($filePath);
						$more = explode('<!--more-->', $item['content']);
						if (count($more) >= 2) {
							$item['more'] = $more[0];
						} else {
							$item['more'] = false;
						}

						$this->posts[] = $item;
						$years[] = $item['year'];		// used to array_multisort();
						$months[] = $item['month'];
						$days[] = $item['day'];
					}
				}
			}
			closedir($handle);
		}
		// sort $post through date.
		array_multisort($years, SORT_DESC, $months, SORT_DESC, $days, SORT_DESC, $this->posts);
	}
	
	// init pages data
	protected function initPage() {
		$this->pages = array();
		$index = array();

		$handle = opendir($this->filePath['page']);
		if ($handle) {
			while (($file = readdir($handle)) !== false) {
				if (preg_match('/\.md$/', $file)) {
					if (is_file($filePath = $this->filePath['page'] . $file)) {
						$page = array();
						$page['url'] = $this->config['baseurl'] . '/' . preg_replace('/.md$/', '', $file);
						$page['filePath'] = $this->config['htmldir'] . preg_replace('/.md$/', '', $file);

						$tmpInfo = getYamlObj($filePath);
						$page['layout'] = $this->filePath['layout'] . $tmpInfo['layout'] . '.php';
						$page['name'] = $tmpInfo['name'];
						$page['index'] = $tmpInfo['index'];
						$page['comment'] = $tmpInfo['comment'];
						
						$page['content'] = getMdHtml($filePath);

						$this->pages[] = $page;
						$index[] = $page['index'];
					}
				}
			}
			closedir($handle);
		}
		// sort pages by index
		//print_r($index);
		array_multisort($index, SORT_ASC, $this->pages);
	}

	// init data for tmpl
	protected function initTmplData() {
		$this->tmplData = array(
			'config' => $this->config,
			'posts' => $this->posts,
			'post' => null,
			'pages' => $this->pages,
			'page' => null
		);
		$this->tmplData['config']['siteTitle'] = $this->config['siteName'];
	}

	// create somepage/index.html
	protected function createPages() {
		foreach ($this->pages as $item) {
			$filePath = $item['filePath'];
			if (!is_dir($filePath)) {
				mkdir($filePath);
			}
			$target = $filePath . '/index.html';
			$layout = $item['layout'];
			$this->tmplData['page'] = $item;
			$this->tmplData['config']['siteTitle'] = $item['name'] . ' | ' .$this->tmplData['config']['siteName'];
			makeHtmlFile($target, $layout, $this->tmplData);
			$this->initTmplData();
			suc('page: ' . $item['name']);
		}
	}

	// create index.html and blog/page/2,3,4,...
	protected function createIndex() {
		$indexPostsNum = $this->config['indexPostsNum'];
		$totalPostsNum = count($this->posts);
		$currentPage = 1;
		$tmpPosts = array();
		foreach ($this->posts as $key => $item) {
			if ($key === 0 || $key % $indexPostsNum !== 0) {	// 0,1,2,3,4 | 5,6,7,8,9 | ...
				$tmpPosts[] = $item;
			} else {											// when $key == 5,10...
				$tmpPosts = array();
				$tmpPosts[] = $item;
				$currentPage += 1;
			}
			// index.html
			if ($currentPage === 1 && $key === $totalPostsNum - 1 || $key === $indexPostsNum - 1) {
				$this->tmplData['olderUrl'] = ($totalPostsNum > $indexPostsNum) ? $this->wwwPath['olderPage'] . '2' : '';
				$this->tmplData['newerUrl'] = '';
				$target = $this->config['htmldir'] . 'index.html';
				$layout = $this->filePath['layout'] . 'index.php';
				$this->tmplData['posts'] = $tmpPosts;
				
				makeHtmlFile($target, $layout, $this->tmplData);
				$this->initTmplData();

				suc("index.html: $currentPage");
				continue;
			}
			// blog/page/2,3,....
			if (count($tmpPosts) === $indexPostsNum || $key === $totalPostsNum - 1) {
				$newFolder = $this->filePath['olderPage'] . $currentPage;
				mkdir($newFolder);

				$newerPage = $currentPage - 1;
				$this->tmplData['newerUrl'] = $newerPage === 1 ? $this->config['baseurl'] : $this->wwwPath['olderPage'] . $newerPage;
				$this->tmplData['olderUrl'] = ($key === $totalPostsNum - 1) ? '' : $this->wwwPath['olderPage'] . ($currentPage + 1);
				$target = $newFolder . '/index.html';
				$layout = $this->filePath['layout'] . 'index.php';
				$this->tmplData['posts'] = $tmpPosts;

				makeHtmlFile($target, $layout, $this->tmplData);
				$this->initTmplData();

				suc("blog/page: $currentPage");
				continue;
			}
		}
	}
	
	// create blog/year/month/article.html
	protected function createPosts() {
		foreach ($this->posts as $key => $item) {
			$folderPathYear = $this->filePath['blog'] . $item['year'];
			$folderPathMonth = $folderPathYear . '/' . $item['month'];
			$folderPathPost = preg_replace('/\/index\.html$/', '', $item['filePath']);
			if (!is_dir($folderPathYear)) {
				mkdir($folderPathYear);
			}
			if (!is_dir($folderPathMonth)) {
				mkdir($folderPathMonth);
			}
			if (!is_dir($folderPathPost)) {
				mkdir($folderPathPost);
			}
			$target = $item['filePath'];
			$layout = $this->filePath['layout'] . 'post.php';
			$this->tmplData['post'] = $item;
			$this->tmplData['newerPostUrl'] = ($key - 1 >= 0) ? $this->posts[$key - 1]['url'] : '';
			$this->tmplData['newerPostTitle'] = ($key - 1 >= 0) ? $this->posts[$key - 1]['title'] : '';
			$this->tmplData['olderPostUrl'] = ($key + 1 < count($this->posts)) ? $this->posts[$key + 1]['url'] : '';
			$this->tmplData['olderPostTitle'] = ($key + 1 < count($this->posts)) ? $this->posts[$key + 1]['title'] : '';
			$this->tmplData['config']['siteTitle'] = $item['title'] . ' | ' . $this->tmplData['config']['siteName'];
			
			makeHtmlFile($target, $layout, $this->tmplData);
			$this->initTmplData();
			suc('post: ' . $item['title']);
		}
	}
}

new MakeSite();

?>
