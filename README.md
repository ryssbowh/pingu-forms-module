# Forms Module

## TODO
- [ ] test all sorts of relations

## v1.0.3 wrote readme

### Forms
- Form : base form with fields and options.
- FormModel : extends Form, used to build a form from a model.

If you build a form manually you will need to call the `end` method or an Exception will be thrown.

Those forms will send events when :
- They are built
- The validator for it is built

By catching those events you can add/remove fields, change the layout, or add validation rules.

### Formable Models
To make a model compatible with Form it'll need to implement the `FormableContract` and use the trait `Formable` that implements the basic methods. It can then override some methods of it.

Formable provides with methods to define validations for each fields and create a validator with those rules. It can also fill a model with values coming from a form (`formFill`). For models that define `BelongsToMany` relations, `saveWithRelations` can be used to save the model and then save those relations (those relations force us to save the model before syncng the relations).

see [Laravel validation](https://laravel.com/docs/5.7/validation)

### Fields
Fields define how a field is saved in database and how it can be filtered/accessed by values coming from a form.

You can define new Fields by extending `Field`, `Serie`, `Model` or `ManyModel`.

Each field must define `fieldQueryModifier(Builder $query, string $name, $value)` which is used when a call filters by this type of field (api call for example). the Builder is of type `Illuminate\Database\Eloquent\Builder` and is basically the query used to get the models that you can change at will in this method.

For field that define relations, `saveRelationships(BaseModel $model, string $name, $value)` saves the relations (it is called by `Formable`).

Each field must implement `setModelValue(BaseModel $model, string $name, $value)` which populates the model with a value (called by `Formable`)

When your models and their fields defines all those, they can safely be used with the Core ModelController

### Renderers
Renderers define how a field is rendered in html. They take for argument an array of options defined by the field.

The Renderers written here use [laravel Collective](https://github.com/LaravelCollective/docs/blob/5.6/html.md) to render elements.

You can define and use new renderers as long as they extend FieldRenderer or InputFieldRenderer. The only method you need to override is `render` which will be called by Form.

### Config
Defines the default css classes for renderers

### Views
Provides default views for renderers