<?php
namespace App\Services;

class ScormParser
{
    protected $xmlElement;
    protected $resources;
    protected $items;

    public function __construct($uri,$link) {
	$this->xmlElement = simplexml_load_file($uri);
    $this->link = $link;
	if (!$this->xmlElement) {
	    throw new InvalidArgumentException('Could not parse file ' . $uri);
	}

	$xresources = $this->xmlElement->resources->resource;
	$xitems = $this->xmlElement->organizations->organization;

	$this->scorm_url = $uri;
	$this->resources = $this->getResources($xresources);
	$this->items = $this->getItems($xitems);

	$this->useResourceFolder = is_dir($uri . '/resources');
    }

    protected function getResources($xresources) {
	$resources = array();
	foreach ($xresources as $xres) {
	    $attrs = $xres->attributes();
	    $type = (string) $attrs['type'];
	    if ($type == 'webcontent') {
		$resources[(string) $attrs['identifier']] = (string) $attrs['href'];
	    }
	}
	return $this->resources = $resources;
    }

    protected function getItems($xitems) {
	$items = array();
	foreach ($xitems as $xitem) {
	    $item = array('title' => (string) $xitem->title);
	    $attributes = $xitem->attributes();
	    $resID = (string) $attributes['identifierref'];
	    $item['href'] = '';
	    if (isset($this->resources[$resID])) {
		$item['href'] = $this->resources[$resID];
	    }
	    $item['subitems'] = array();
	    if (!empty($xitem->item)) {
		$item['subitems'] = $this->getItems($xitem->item);
	    }
	    $items[] = $item;
	}
	return $this->items = $items;
    }

    public function output($items = null) {
	if (!$items) {
	    $items = $this->items;
	}

	$output = '<ul class="scorm-tree">';
	foreach ($items as $item) {
	    $output .= '<li>';
	    if ($item['href']) {
		$output .= '<a href="' . $this->link . '" class="scorm-link">' . $item['title'] . '</a>';
	    } else {
		$output .= $item['title'];
	    }

	    if ($item['subitems']) {
		$output .= $this->output($item['subitems']);
	    }
	    $output .= '</li>';
	}
	$output .= '</ul>';
	return $output;
    }
}
