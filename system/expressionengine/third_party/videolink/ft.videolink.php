<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Videolink_ft extends EE_Fieldtype {

	var $info = array(
		'name'		=> 'Video Link',
		'version'	=> '1.1.0'
	);

	function __construct()
	{
		parent::__construct();

		if (! isset($this->EE->session->cache['videolink']))
		{
			$this->EE->session->cache['videolink'] = array();
		}
		$this->cache =& $this->EE->session->cache['videolink'];

		if (!isset($this->cache['includes'])) {
			$this->cache['includes'] = array();
		}
	}

	function _extract_data($data) {
		// Matrix gives us back $data as an array.
		if (is_array($data)) {
			$video = new stdClass();
			$video->url = isset($data['url']) ? $data['url'] : '';
			$video->title = isset($data['title']) ? $data['title'] : '';
			$video->thumbnail = isset($data['thumbnail']) ? $data['thumbnail'] : '';
			return $video;
		}

		$datas = explode('|', $data);

		$video = new stdClass();
		$video->url = $datas[0];
		$video->title = isset($datas[1]) ? $datas[1] : '';
		$video->thumbnail = isset($datas[2]) ? $datas[2] : '';
		return $video;
	}

	/**
	 * Allow the Field Type to show up in a Grid.
	 */
	public function accepts_content_type($name)
	{
		return ($name == 'channel' || $name == 'grid');
	}

	protected function _include_theme_js($file) {
		if (! in_array($file, $this->cache['includes']))
		{
			$this->cache['includes'][] = $file;
			$this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->_theme_url().$file.'?version='.$this->info['version'].'"></script>');
		}
	}

	protected function _include_theme_css($file) {
		if (! in_array($file, $this->cache['includes']))
		{
			$this->cache['includes'][] = $file;
			$this->EE->cp->add_to_head('<link rel="stylesheet" href="'.$this->_theme_url().$file.'?version='.$this->info['version'].'">');
		}
	}

	/**
	 * Theme URL
	 */
	protected function _theme_url()
	{
		if (! isset($this->cache['theme_url']))
		{
			$theme_folder_url = defined('URL_THIRD_THEMES') ? URL_THIRD_THEMES : $this->EE->config->slash_item('theme_folder_url').'third_party/';
			$this->cache['theme_url'] = $theme_folder_url.'videolink/';
		}

		return $this->cache['theme_url'];
	}


	/**
	 * Display Field on Publish
	 *
	 * @access	public
	 * @param	existing data
	 * @return	field html
	 *
	 */
	function display_field($data)
	{
		return $this->_display($data, $this->field_name);
	}

	function display_cell($data)
	{
		return $this->_display($data, $this->cell_name);
	}

	function _display($data, $name) {
		$obj = $this->_extract_data($data);

		$this->_include_theme_js('js/videolink.js');
		$this->_include_theme_css('css/videolink.css');
		return <<<EOF
<div class="videolink">
	<input type="url" name="{$name}[url]" value="{$obj->url}">
	<input data-title type="hidden" name="{$name}[title]" value="{$obj->title}">
	<input data-thumbnail type="hidden" name="{$name}[thumbnail]" value="{$obj->thumbnail}">
</div>
EOF;
	}

	/**
	 * Prep data for saving
	 *
	 * @access	public
	 * @param	submitted field data
	 * @return	string to save
	 */
	function save($data)
	{
		$url = $data['url'];
		$title = $data['title'];
		$thumbnail = $data['thumbnail'];

		return $url . '|' . $title . '|' . $thumbnail;
	}

	function save_cell($data) {
		$url = $data['url'];
		$title = $data['title'];
		$thumbnail = $data['thumbnail'];

		return $url . '|' . $title . '|' . $thumbnail;
	}

	/**
	 * Replace tag
	 *
	 * @access	public
	 * @param	field data
	 * @param	field parameters
	 * @param	data between tag pairs
	 * @return	replacement text
	 *
	 */
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		$video = $this->_extract_data($data);
		return $video->url;
	}

	function replace_url($data, $params = array(), $tagdata = FALSE)
	{
		$video = $this->_extract_data($data);
		return $video->url;
	}

	function replace_title($data, $params = array(), $tagdata = FALSE)
	{
		$video = $this->_extract_data($data);
		return $video->title;
	}

	function replace_thumbnail($data, $params = array(), $tagdata = FALSE)
	{
		$video = $this->_extract_data($data);
		return $video->thumbnail;
	}

	function replace_embed($data, $params = array(), $tagdata = FALSE)
	{
		$video = $this->_extract_data($data);
		$width = NULL;
		$height = NULL;

		if (isset($params['width'])) {
			$width = intval($params['width']);
		}
		if (isset($params['height'])) {
			$height = intval($params['height']);
		}

		$embed = $this->get_embed_iframe($video->url, $width, $height);
		if (!is_null($embed)) {
			return $embed;
		}
		return '<a href="' . $data . '" target="_blank">' . $data . '</a>';
	}

	function replace_embed_url($data, $params = array(), $tagdata = FALSE)
	{
		$video = $this->_extract_data($data);
		$url = $this->get_embed_url($video->url);
		if (!is_null($url)) {
			return $url;
		}
		return $data;
	}

	function replace_type($data, $params = array(), $tagdata = FALSE)
	{
		$video = $this->_extract_data($data);
		if ($this->is_youtube($video->url)) {
			return "youtube";
		}
		if ($this->is_vimeo($video->url)) {
			return "vimeo";
		}
		return "unknown";
	}

	function replace_valid($data, $params = array(), $tagdata = FALSE)
	{
		$video = $this->_extract_data($data);
		if ($this->is_youtube($video->url)) {
			return "yes";
		}
		if ($this->is_vimeo($video->url)) {
			return "yes";
		}
		return "";
	}

	function get_embed_iframe($data, $width = NULL, $height = NULL) {
		$video = $this->_extract_data($data);
		if ($this->is_youtube($video->url)) {
			$url = $this->get_embed_url($video->url);
			return $this->build_embed_youtube($url, $width, $height);
		}
		if ($this->is_vimeo($video->url)) {
			$url = $this->get_embed_url($video->url);
			return $this->build_embed_vimeo($url, $width, $height);
		}
		return NULL;
	}

	function get_embed_url($data) {
		$video = $this->_extract_data($data);
		if ($this->is_youtube($video->url)) {
			$parsed = $this->parse_youtube($video->url);

			return '//www.youtube.com/embed/' . $parsed['key'];
		}
		if ($this->is_vimeo($video->url)) {
			$parsed = $this->parse_vimeo($video->url);

			return '//player.vimeo.com/video/' . $parsed['key'];
		}
		return NULL;
	}

	function is_youtube($url) {
		return preg_match("/^\s*https?:\/\/(www\.)?youtube\.com\/.*v=.*/", $url);
	}

	function parse_youtube($url) {
		$parsed = parse_url($url);
		$query = $parsed['query'];
		parse_str($query, $queryvars);
		$key = $queryvars['v'];

		return array(
			'service' => 'youtube',
			'key' => $key
			);
	}

	function build_embed_youtube($url, $width = NULL, $height = NULL)
	{
		if (is_null($width)) {
			$width = 560;
		}
		if (is_null($height)) {
			$height = round($width * (315/560));
		}
		return '<iframe width="' . $width . '" height="' . $height . '" src="' . $url . '?rel=0" frameborder="0" allowfullscreen></iframe>';
	}

	function is_vimeo($url) {
		return preg_match("/^\s*https?:\/\/vimeo\.com\/[0-9]+/", $url);
	}

	function parse_vimeo($url) {
		$parsed = parse_url($url);
		$key =	substr($parsed['path'], 1);

		return array(
			'service' => 'vimeo',
			'key' => $key
			);
	}

	function build_embed_vimeo($url, $width = NULL, $height = NULL)
	{
		if (is_null($width)) {
			$width = 500;
		}
		if (is_null($height)) {
			$height = round($width * (281/500));
		}
		return '<iframe src="' . $url . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
	}
}
