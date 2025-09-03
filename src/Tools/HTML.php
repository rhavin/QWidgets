<?php
// Version 0.1.35

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

	public static function to_tag($tag, $content, $indent=0, $attr=null) {
		$spc = str_repeat('  ', $indent);
		return $spc.'<'.$tag.self::attrlist($attr).'>'.$content.'</'.$tag.">\n";
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

	public static function inputfield($id, $name, $label, $value, $type='text', $attr = []) {
		$label_first = true;
		$content = null;
		$labelsuffix = ':';
		$attr['id'] = $id;
		$attr['type'] = $type;
		$attr['name'] = $name;
		$attr['size'] = $attr['size'] ?? 100;
		$tagname = 'input';

		switch ($type) {
			case 'checkbox':
				$label_first = false;
				$labelsuffix = '';
				$attr['class'] = 'checkbox';
				if ($value) {
					$attr['value'] = '1';
					$attr['checked'] = 'checked';
				} else
					$attr['value'] = '1';
				break;
			case 'textarea':
				$attr['cols'] = $attr['size'] ?? 40;
				$attr['rows'] = $attr['rows'] ?? 5;
				unset($attr['size']);
				$content = $value;
				$tagname = 'textarea';
				break;
			case 'text':
				$attr['class'] = 'widefat';
			case 'email':
			case 'number':
			case 'url':
			case 'password':
				$attr['value'] = $value;
				break;
			default:
				// NYI: say something here...
		}

		$label = '<label for="'.$id.'">'.$label.$labelsuffix.'</label>';

		$field = '<'.$tagname.self::attrlist($attr);
		if (is_null($content))
			$field .= ' />';
		else
			$field .= ' >'.$content.'</'.$tagname.'>';

		if ($label_first)
			return $label.$field;
		else
			return $field.$label;
	}
}
?>