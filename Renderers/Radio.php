<?php
namespace Modules\Forms\Renderers;

use FormFacade;

class Radio extends FieldRenderer
{
	public function __construct(array $options)
	{
		parent::__construct($options);
		$this->buildItems();
	}

	public function buildItems()
	{
		foreach($this->options['items'] as $key => $item){
			$attributes = $this->options['attributes'];
			$attributes['id'] = $this->options['name'].$key;
			$this->options['radios'][$key] = FormFacade::radio($this->options['name'], $key, $this->options['default'] == $key, $attributes);
		}
	}
}