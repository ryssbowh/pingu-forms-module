# Forms Module

## TODO
- [ ] test all sorts of relations
- [ ] test macros

## v2.0.0 Remade everything

## v1.1.4
- added js for dashify fields
- added new validation rule `valid_url` 
- added functions to move a field up in his layout or group
- added docs

## v1.1.3
- renamed fieldQueryModifier in filterQueryModifier in Field

## v1.1.2
- Model fields can define a queryCallback

## v1.1.1
- Refactored FormableModel and FormableController to throw events at each step of the process
- fixed field templates
- refactored Renderers
- added Route folder and Route service provider
- added Url field
- added Boolean field
- added singleCheckbox Renderer
- added default classes for all parts of a form
- ignore fields starting with _ in formFill

## v1.0.3 wrote readme

### Forms
A form's life cycle from definition to saving in db :
- definition :
	Either through a form class (`module:make-form` command) for forms that don't define a model form. Fields are defined as arrays
	Or through a ModelForm instance. fields are defined in the model (which must implement `FormableContract`) as arrays. When the system retrieves those definitions, the event `AddFormFields` or `EditFormFields` are thrown.
- class building
	Those arrays will be turned into a Field class and added to the form
- rendering
	Before the form is being rendered, the event `FormBuilt` will be thrown, so other modules can update it.
- form is rendered through a view
- validation. The form is submitted. see [Laravel validation](https://laravel.com/docs/5.7/validation)
	For class defined forms, the validation rules and messages sit in the class.
	For model forms, the validation rules and messages sit in the model.
- form is validated, model is being saved (model forms only)

A basic form must define a name, a method, an url and some fields. It can also define options.

### Fields
A field define how the field is rendered on screen, it has nothing to do with how the value of that field is saved in db, this is the role of the Type.

A field can have options and attributes. Each field must define a Type, if you don't define a Type, each field has a default one (Text).

Each field can define a number of required options, see each field class (Support/Fields folder)

a valid field definition :

`'name' => [
	'field' => TextInput::class,
	'options' => [
		'myoption' => 'myvalue',
		'type' => Boolean::class
	],
	'attributes' => [
		'required' => true
	]
];`

Fields can also be elements that you can add to your form but do not have a value, for example submit is considered as a field.
So you could define any type of 'field' that you want and add it to your layout (Image, link, download etc)

If you want to define new fields, they must extend Field

### Types 
Types define how the value of a field must be handled (Boolean, Model, Text etc), those types define methods, for example how to modify the db query when filtering on this type of field. Or how to set the value of the field when given a value.

It also define whether or not the associated field define relationships (for a model), which is used when saving a model.

### groups
Fields can be organized in groups, and you have many methods available to move them around (`HasFields` and `HasGroups` traits). If you don't define groups for your form, they will all go into a 'default' group.

### Formable Models
To make a model compatible with Form it'll need to implement the `FormableContract` and use the trait `Formable` that implements the basic methods. It can then override some methods of it.

Formable provides with methods to define validations for each fields and create a validator with those rules. It can also fill a model with values coming from a form (`formFill`). formFill will look at each field type and set the value through it. It can also automatically save a models relation if the type of that field define relations for it.

### Config
Defines the default css classes for fields, wrappers and forms.

### Views
Provides default views for form and fields.
All default views are based on FormFacade and Html facades provided by [Laravel Collective](https://github.com/LaravelCollective/docs/blob/5.6/html.md)
The html facade has been overwritten with the class `Macro` where some additionnal macros are available (usStates and titles), calling `{{ Html::titles(options) }}` in a template will print a select with titles.

Before being rendered, forms will be given some view suggestions (which can be changed through the event), that allows us to define more granular form views. the last one of the list (the last to be called) is the one the Forms module provides.

### Commands
`./artisan module:make-form Form Module` creates a basic template for a form, for you to fill in.

### Events
- `AddFormFields` thrown every time a model add form fields are retrieved
- `EditFormFields` thrown every time a model edit form fields are retrieved
- `FormBuilt` thrown every time a form is finished building (`end` method is called)
- `ModelFieldDefinitions` thrown every time a model field's definition are retrieved
- `ModelFieldValidationMessages` thrown every time a model field's validation messages are retrieved
- `ModelFieldValidationRules` thrown every time a model field's validation rules are retrieved
- `ModelValidator` thrown every time a model's validator is created

### Validation
`valid_url` checks if a field's value is valid url (if the values doesn't start with http), the value can be a route uri or route name (GET routes only).