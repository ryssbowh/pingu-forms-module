<?php

namespace Pingu\Forms\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use TextSnippet;

class FormsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // if(!TextSnippet::getSnippet('forms.errors.notValid')){
        //     TextSnippet::create('forms.errors.notValid','${label} isn\'t valid.');
        //     TextSnippet::create('forms.errors.required','${label} is required.');
        //     TextSnippet::create('forms.errors.tooBig','${label} must be smaller than ${maxValue}.');
        //     TextSnippet::create('forms.errors.tooSmall','${label} must be higher than ${minValue}.');
        //     TextSnippet::create('forms.errors.incFileType','${label} : Incorrect file type');
        //     TextSnippet::create('forms.errors.incFileSize','${label} : Incorrect file size.');
        //     TextSnippet::create('forms.errors.noUpload','${label} : Couldn\'t upload file.');
        //     TextSnippet::create('forms.errors.fileError','${label} : Error processing the file.');
        //     TextSnippet::create('forms.errors.maxValues','${label} can have ${maxValues} values maximum.');
        //     TextSnippet::create('forms.errors.notUrl','${label} This url is not valid.');
        //     TextSnippet::create('forms.errors.notEmail','${label} This email is not valid.');
        //     TextSnippet::create('forms.errors.dateFormat.','${label} incorrect date format.');
        //     TextSnippet::create('forms.errors.dateTooBig.','${label} is after the maximum allowed date ${maxDate}.');
        //     TextSnippet::create('forms.errors.dateTooSmall.','${label} is before the minimum allowed date ${minDate}.');
        //     TextSnippet::create('forms.errors.textTooLong.','${label} is too long (max ${maxLength} characters).');
        //     TextSnippet::create('forms.errors.textTooShort.','${label} is too short (min ${minLength} characters).');
        //     TextSnippet::create('forms.errors.notAlnum.','${label} can contain only alphanumeric characters.');
        // }
    }
}
