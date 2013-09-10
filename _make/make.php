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
		
		$this->initPage();
		$this->initTmplData();

        $this->createPages();
		//$this->createArchives();
	}

	// init pages data
	protected function initPage() {
		$this->pages = array();
		$index = array();

		$handle = opendir($this->config['sourcedir']);
		if ($handle) {
			while (($file = readdir($handle) ) !== false) { // TODO recursive // TODO also as arg and GET

                
				if (preg_match('/\.md$/', $file)) {
					if (is_file($filePath = $this->config['sourcedir'] . $file)) {
						$page = array();
						$page['url'] = $this->config['baseurl'] . '/' . preg_replace('/.md$/', '', $file);
						$page['filePath'] = $this->config['htmldir'] . preg_replace('/.md$/', '', $file);

						$tmpInfo = getYamlObj($filePath);
						$page['layout'] = $this->filePath['layout'] . $tmpInfo['layout'] . '.php';
						$page['name'] = $tmpInfo['name'];
						$page['index'] = $tmpInfo['index'];
						$page['comment'] = $tmpInfo['comment'];
						
						$page['content'] = getMdHtml($filePath);

//~ more functionality 
//~ TODO move to outermarkdown
						//~ $more = explode('<!--more-->', $item['content']);
						//~ if (count($more) >= 2) {
							//~ $item['more'] = $more[0];
						//~ } else {
							//~ $item['more'] = false;
						//~ }

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
			$target = $filePath . '/index.html'; // TODO dir lorem/index.html or lorem.html or lorem
			$layout = $item['layout'];
			$this->tmplData['page'] = $item;
			$this->tmplData['config']['siteTitle'] = $item['name'] . ' | ' .$this->tmplData['config']['siteName'];
			makeHtmlFile($target, $layout, $this->tmplData);
			$this->initTmplData();
			suc('page: ' . $item['name']);
		}
	}
}

new MakeSite();

?>
