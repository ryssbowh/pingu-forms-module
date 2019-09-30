<?php

namespace Pingu\Forms\Support\Types;

use Illuminate\Http\UploadedFile;
use Pingu\Core\Entities\BaseModel;
use Pingu\Media\Contracts\MediaFieldContract;
use Pingu\Media\Entities\Media;

class ManyMedia extends ManyModel
{
	public function __construct(MediaFieldContract $field)
	{
		parent::__construct($field);
	}

	/**
	 * @inheritDoc
	 */
	public function saveRelationships(BaseModel $model, $value)
	{
		$value = array_map(function($item){
			if($item instanceof UploadedFile){
				return $this->field->uploadFile($value)->id;
			}
			return (int)$item;
		}, $value);
		return parent::saveRelationships($model, $value);
	}
}