<?php

class ChargeBee_Util
{

	static function toCamelCaseFromUnderscore($str) {
		return preg_replace_callback('/_([a-z])/', function($match) {
			return strtoupper($match[1]);
		}, $str);
	}

	static function toUnderscoreFromCamelCase($str) {
		return preg_replace_callback('/([A-Z])/', function($match) {
			return '_'.strtolower($match[1]);
		}, $str);
	}

	static function serialize($value, $prefix=NULL, $idx=NULL)
	{
		if($value && !is_array($value))
		{
			throw new Exception("only arrays are allowed as value");
		}
		$serialized = array();
		foreach ($value as $k => $v)
		{
			if (is_array($v) && !is_int($k))
			{
				$serialized = array_merge($serialized, self::serialize($v, self::toUnderscoreFromCamelCase($k)));
			}
			else if (is_array($v) && is_int($k))
			{
				$serialized = array_merge($serialized, self::serialize($v, $prefix, $k));
			}
			else
			{
				$usK = self::toUnderscoreFromCamelCase($k);
				$key = (!is_null($prefix)?$prefix:'').(!is_null($prefix)?'['.$usK.']':$usK).(!is_null($idx)?'['.$idx.']':'');
				$serialized[$key] = self::asString($v);
			}
		}
		return $serialized;
	}

    static function asString($value)
    {
        if(is_null($value))
        {
            return '';
        }
        else if(is_bool($value))
        {
            return ($value) ? 'true' : 'false';;
        }
        else
        {
            return (string)$value;
        }
    }
    
    static function encodeURIPath()
    {
      $uriPaths = "";
      foreach(func_get_args() as $arg) {
            $arg=trim($arg);
            if( $arg == null || strlen($arg) < 1 ) {
                 throw new Exception("Id cannot be null or empty");
            }
            $uriPaths .= "/" . rawurlencode($arg);
      }
      return $uriPaths;
    }
	

}

?>
