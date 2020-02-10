# Forms Module

## TODO
- [ ] test macros

### Forms

A basic form must extend the `Form` class and define a name, a method, an url and some fields. It can also define options and groups.

### Fields

A field can be one of the fields defined by this package (Support/Fields).
New fields can be registered through the facade `FormField`.

Fields defined by the Field API have helper methods to be turned into Form fields.

### Widgets

Fields as defined in the Field API can be displayed through different widgets. The `FormField` facade provides with methods to register extra widgets (Form fields) for fields.

### Groups
Fields can be organized in groups, and you have many methods available to move them around (`HasFields` and `HasGroups` traits). If you don't define groups for your form, they will all go into a 'default' group.

### Config
Defines the default css classes for fields, wrappers and forms. (unused now)

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

### Validation
`valid_url` checks if a field's value is valid url (if the value doesn't start with http), the value can be a route uri or route name (GET routes only).