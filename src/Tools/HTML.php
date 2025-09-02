<?php
// Version 0.1.10

namespace Q\Tools;
class HTML {
	public static function keyproperty($property, $tag, $array, $indent=0, $key=null, $prefix='', $postfix='') {
		if (is_null($key))
			$key = $property;
		if (!isset($array[$key]))
		    return '';
		$content = $array[$key];
		$attr = [];
		if ($property == 'email')
		    $attr['href'] = 'mailto:'.esc_attr($content);
		if ($tag == 'time')
		    $attr['content'] = self::timeparse($content);
		return self::property($property, $tag, $prefix.$content.$postfix, $indent, $attr);
	}

	public static function property($property, $tag, $content, $indent=0, $attr=null) {
		$spc = str_repeat('  ', $indent);
		return $spc.'<'.$tag.' property="'.esc_attr($property).' '.self::attrlist($attr).'">'.$content.'</'.$tag.">\n";
	}

	public static function timeparse($time) {
		$parts = explode(':', $time);
		if (count($parts) == 2)
			return '00:'.$parts[0].':'.$parts[1];
		if (count($parts) == 3)
			return $parts[0].':'.$parts[1].':'.$parts[2];
		return '00:00:00';
	}	

	public static function attrlist($attr) {
		if (is_null($attr))
			return '';
		$list = '';
		foreach ($attr as $k => $v) {
			$list .= ' '.$k.'="'.$v.'"';
		}
		return $list;
	}

	public static function inputfield($id, $name, $label, $value, $type='text', $size=100) {
		return '<label for="'.$name.'">'.$label.':</label>'
			.'<input class="widefat" id="'.$id.'" name="'.$name.'" type="'
			.$type.'" value="'.$value.'" />';
	}
}
?>