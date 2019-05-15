# Custom Fields

## Autocomplete
This field extends from R64 Select. The main difference is that you will recieve suggestions as you type inside the field.

[Shared Methods](shared-methods.md)

## JSON
This field allows you to group together Nova Fields and merge their key-value-pairs into unique JSON.

### Demo

![Demo](http://g.recordit.co/b7alxIvlsh.gif)

### Usage

Create a new Json field passing an array of Nova Fields as second param. Attribute names can be passed as third param.

```php
use R64\NovaFields\JSON;

JSON::make('Content', [
    Text::make('Name'),
    Boolean::make('Active'),
    Textarea::make('Description'),
], 'content_json'),
```

This converts to

```json
{ "name": "Jane", "active": true, "description": "My Description" }
```

and is stored in the `content_json` field.

In this example ensure that the `content_json` field is being set to cast either to `object` or `collection` on the underlying model instance.

### childConfig

You can apply as many as [Shared Methods](shared-methods.md) you want to all the JSON subfields. The following example would change the label and field classes for `First Name` and `Last Name` subfields

```php
JSON::make('Content', [
    Text::make('First Name'),
    Text::make('Last Name'),
])->childConfig([
    'labelClasses' => 'w-3/5 py-4 pr-4',
    'fieldClasses' => 'w-2/5 py-4',
]),
```

### panelTitleClasses

If you are using panel for grouping your subfields you can use this method to customize the classes of the panel title.

```php
JSON::make('Content', [
    new Panel('Client Info', [
        Text::make('First Name'),
        Boolean::make('Last Name'),
    ]),
    new Panel('Address', [
        Text::make('Address'),
        Text::make('City'),
    ])
])->panelTitleClasses('my-8'),
```

### showOnIndex

By default Json field is hidden in index view. If you want to show it you can use this method.

```php
JSON::make('Content', [
    Text::make('Name'),
])->showOnIndex(),
```

### flatten

Using `flatten()` subfields will be displayed as if they where fields of the main Resource

```php
JSON::make('Content', [
    Text::make('Name'),
])->flatten(),
```

[Shared Methods](shared-methods.md)

## Row
This field allows you to create horizontal rows passing a collection of Nova Fields. It also supports validation on field level.

### Demo

![Demo](http://g.recordit.co/88FYF4f7rP.gif)

### Usage

Create a new Row field passing an array of Nova Fields as a second param. Attribute name can be passed as third param.

As we are using other Nova Fields]. We can customize the classes and hide the field labels.

```php
use R64\NovaFields\Row;

Row::make('Lines', [
      Number::make('Quantity')
        ->fieldClasses('w-full px-8 py-6')
        ->hideLabelInForms(),
      Text::make('Product')
        ->fieldClasses('w-full px-8 py-6')
        ->hideLabelInForms(),
      Number::make('Price')
        ->fieldClasses('w-full px-8 py-6')
        ->hideLabelInForms(),
    ]),
```

This converts to an array of objects.

```javascript
[
  { quantity: 1, product: 'This One', price: 20 },
  { quantity: 2, product: 'That One', price: 21 }
];
```
### childConfig

You can apply as many as [Shared Methods](shared-methods.md) you want to all the Row subfields. The following example has the same effect that code in the snippet above

```php
Row::make('Lines', [
      Number::make('Quantity'),
      Text::make('Product'),
      Number::make('Price'),
    ])->childConfig([
        'fieldClasses' => 'w-full px-8 py-6',
        'hideLabelInForms' => true,
    ])
```

[Shared Methods](shared-methods.md)
