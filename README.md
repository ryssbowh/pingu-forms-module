# Forms Module

## TODO
- [ ] test all sorts of relations
- [ ] make fields define extra validations rules
- [ ] make file field

## v1.1.4
- added js for dashify fields
- added new validation rule `valid_url` 
- added functions to move a field up in his layout or group

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
- Form : base form with fields and options.
- FormModel : extends Form, used to build a form from a model.

If you build a form manually you will need to call the `end` method or an Exception will be thrown.

### Formable Models
To make a model compatible with Form it'll need to implement the `FormableContract` and use the trait `Formable` that implements the basic methods. It can then override some methods of it.

Formable provides with methods to define validations for each fields and create a validator with those rules. It can also fill a model with values coming from a form (`formFill`). For models that define `BelongsToMany` relations, `saveWithRelations` can be used to save the model and then save those relations (those relations force us to save the model before syncing the relations).

see [Laravel validation](https://laravel.com/docs/5.7/validation)

### Fields
Fields define how a field is saved in database and how it can be filtered/accessed by values coming from a form.

You can define new Fields by extending `Field`, `Serie`, `Model` or `ManyModel`.

Each field must define `filterQueryModifier(Builder $query, string $name, $value)` which is used when a call filters by this type of field (api/ajax call for example). the Builder is of type `Illuminate\Database\Eloquent\Builder` and is basically the query used to get the models that you can change at will in this method.

For field that define relations, `saveRelationships(BaseModel $model, string $name, $value)` saves the relations (it is called by `FormableModel`).

Each field must implement `setModelValue(BaseModel $model, string $name, $value)` which populates the model with a value (called by `FormableModel`)

When your models and their fields defines all those, they can safely be used with the Core ModelController.

Model fields can define a queryCallback ['object', 'method'], which will be called when retrieving all models from db. The method should be static and will receive the Field as argument.

### Define fields that are not in the model table

Sometimes for validation purposes you need to create fields and validate them but not populate the model with them (\_repeat_password for example), in that case, prefix them with a \_.

### Renderers
Renderers define how a field is rendered in html. They take for argument an array of options defined by the field.

The Renderers written here use [laravel Collective](https://github.com/LaravelCollective/docs/blob/5.6/html.md) to render elements.

You can define and use new renderers as long as they extend FieldRenderer.

### Config
Defines the default css classes for fields and renderers.

### Views
Provides default views for form, fields and renderers

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