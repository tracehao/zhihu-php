<?php

class Collection
{
	private $collection_url;
	private $collection_title;

	function __construct($collection_url, $collection_title=null)
	{
		if (substr($collection_url, 0, 33) !== COLLECTION_PREFIX_URL) {
			throw new Exception($collection_url.": it isn't a collection url !");
		} else {
			$this->collection_url = $collection_url;
			if ( ! empty($collection_title)) {
				$this->collection_title = $collection_title;
			}
		}	
	}
	
	/**
	 * 解析收藏主页
	 * @return object simple html dom 对象
	 */
	public function parser()
	{
		if (empty($this->dom) || isset($this->dom)) {
			$r = Request::get($this->collection_url);
			$this->dom = str_get_html($r);
		}
	}

	/**
	 * 获取收藏夹名称
	 * @return string 收藏夹名称
	 */
	public function get_title()
	{
		if( ! empty($this->title)) {
			$title = $this->title;
		} else {
			$this->parser();
			$title = trim($this->dom->find('div#zh-list-title', 0)->plaintext);
		}
		return $title;
	}

	/**
	 * 获取收藏夹描述
	 * @return string 收藏夹描述
	 */
	public function get_description()
	{
		$this->parser();
		$description = $this->dom->find('div#zh-fav-head-description', 0)->plaintext;
		return $description;
	}

	/**
	 * 获取收藏夹创建者
	 * @return object 创建者
	 */
	public function get_author()
	{
		$this->parser();
		$author_link = $this->dom->find('h2.zm-list-content-title a', 0);
		$author_url = ZHIHU_URL.$author_link->href;
		$author_id = $author_link->plaintext;
		return new User($author_url, $author_id);
	}


	/**
	 * 获取收藏夹中的全部回答
	 * @return Generator 回答迭代器
	 */
	public function get_answers()
	{
		$this->parser();
		$max_page = (int)$this->dom->find('div.zm-invite-pager span', -2)->plaintext;
		for ($i = 1; $i <= $max_page; $i++) { 
			$page_url = $this->collection_url.GET_PAGE_SUFFIX_URL.$i;
			$r = Request::get($page_url);
			$dom = str_get_html($r);

			for ($j = 0; ! empty($dom->find('div.zm-item', $j)); $j++) { 
				$collection_link = $dom->find('div.zm-item', $j);
				if ( ! empty($collection_link->find('h2.zm-item-title a', 0))) {
					$question_link = $collection_link->find('h2.zm-item-title a', 0);
					$question_url = ZHIHU_URL.$question_link->href;
					$question_title = $question_link->plaintext;

					$question = new Question($question_url, $question_title);
				}
				$answer_id = $collection_link->find('div.zm-item-fav div.zm-item-answer', 0)->attr['data-atoken'];
				$answer_url = $question_url.ANSWERS_SUFFIX_URL.'/'.$answer_id;
				yield new Answer($answer_url, $question);
			}
		}
	}
}