<?php 

namespace Pingu\Forms\Support;

abstract class FormElement
{
    abstract public function getName(): string;

    abstract public function render();

    abstract public function setForm(Form $form);
}