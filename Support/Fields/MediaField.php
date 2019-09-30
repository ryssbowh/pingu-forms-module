<?php

namespace Pingu\Forms\Support\Fields;

use Illuminate\Http\UploadedFile;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Types\Media;
use Pingu\Media\Contracts\MediaFieldContract;
use Pingu\Media\Entities\Media as MediaModel;

abstract class MediaField extends Field implements MediaFieldContract
{
	public function __construct(string $name, array $options = [], array $attributes = [])
	{
		$options['disk'] = $options['disk'] ?? \Media::getDefaultDisk();
		parent::__construct($name, $options, $attributes);
	}

	public function uploadFile(UploadedFile $file)
	{
		$disk = $this->option('disk');
		$media = \Media::uploadFile($file, $disk);
		return $media;
	}

	public function getMedia()
	{
		if($this->value){
			return MediaModel::find($this->value)->first();
		}
		return null;
	}

	public static function getDefaultType()
	{
		return Media::class;
	}
}