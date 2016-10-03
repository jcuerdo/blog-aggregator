<?php

namespace Blog\Rss;

class RssReader
{
	public function getItems($url)
	{
		$xml =simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
		if($xml)
		{
			if($xml->xpath('/rss/channel/item')){
				return $xml->xpath('/rss/channel/item') ? : [];
			}

			$result = [];
			foreach($xml->item as $item){

				$result[] = $item;
			}
			return $result;
		}
		return [];
	}
}