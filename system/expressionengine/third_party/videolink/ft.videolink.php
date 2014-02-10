<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Videolink_ft extends EE_Fieldtype {

	var $info = array(
		'name'		=> 'Video Link',
		'version'	=> '1.0.0'
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
		$this->_include_theme_js('js/videolink.js');
		$this->_include_theme_css('css/videolink.css');
		return '<div class="videolink"><input type="url" name="field_id_' . $this->field_id . '" value="' . $data . '"></div>';
	}

	function display_cell($data)
	{
		$this->_include_theme_js('js/videolink.js');
		$this->_include_theme_css('css/videolink.css');
		return '<div class="videolink"><input type="url" name="' . $this->cell_name . '" value="' . $data . '"></div>';
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
		return $this->EE->input->post('field_id_' . $this->field_id);
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
		return $data;
	}

	function replace_embed($data, $params = array(), $tagdata = FALSE)
	{
		$width = NULL;
		$height = NULL;

		if (isset($params['width'])) {
			$width = intval($params['width']);
		}
		if (isset($params['height'])) {
			$height = intval($params['height']);
		}

		$embed = $this->get_embed_iframe($data, $width, $height);
		if (!is_null($embed)) {
			return $embed;
		}
		return '<a href="' . $data . '" target="_blank">' . $data . '</a>';
	}

	function replace_embed_url($data, $params = array(), $tagdata = FALSE)
	{
		$url = $this->get_embed_url($data);
		if (!is_null($url)) {
			return $url;
		}
		return $data;
	}

	function replace_type($data, $params = array(), $tagdata = FALSE)
	{
		if ($this->is_youtube($data)) {
			return "youtube";
		}
		if ($this->is_vimeo($data)) {
			return "vimeo";
		}
		return "unknown";
	}

	function replace_valid($data, $params = array(), $tagdata = FALSE)
	{
		if ($this->is_youtube($data)) {
			return "yes";
		}
		if ($this->is_vimeo($data)) {
			return "yes";
		}
		return "";
	}

	function get_embed_iframe($data, $width = NULL, $height = NULL) {
		if ($this->is_youtube($data)) {
			$url = $this->get_embed_url($data);
			return $this->build_embed_youtube($url, $width, $height);
		}
		if ($this->is_vimeo($data)) {
			$url = $this->get_embed_url($data);
			return $this->build_embed_vimeo($url, $width, $height);
		}
		return NULL;
	}

	function get_embed_url($data) {
		if ($this->is_youtube($data)) {
			$parsed = $this->parse_youtube($data);

			return '//www.youtube.com/embed/' . $parsed['key'];
		}
		if ($this->is_vimeo($data)) {
			$parsed = $this->parse_vimeo($data);

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
