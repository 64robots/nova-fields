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

### flatten

Using `flatten()` subfields will be displayed as if they where fields of the main Resource

```php
JSON::make('Content', [
    Text::make('Name'),
])->flatten(),
```

### More methods

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
;[
  { quantity: 1, product: 'This One', price: 20 },
  { quantity: 2, product: 'That One', price: 21 }
]
```

If you need to transform the request before storing it in the database you can use `fillUsing` callback

```php
Row::make('Lines', [
    // ...
])->fillUsing(function ($request, $model) {
    $model->lines = json_encode($request->lines);
})
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

### hideHeading

Indicate that the heading should be hidden.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])->hideHeading()
```

### hideHeadingWhenEmpty

Determine if the row heading should be hidden when there is no rows.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])->hideHeadingWhenEmpty()
```

### sum

Shows a sum of the value of the field.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->sum('price')
```

### hideSumWhenEmpty

When used with `sum`, determine if the sum field should be hidden when empty rows.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->sum('price')
    ->hideSumWhenEmpty()
```

### prepopulateRowWhenEmpty

Prepopulate one empty row when the collection is empty.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->prepopulateRowWhenEmpty()
```

### maxRows

Sets the maximum of rows that can be added.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->maxRows(3)
```

### addRowText

Sets the text for Add Row button.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->addRowText('Add Product')
```

### headingClasses

Sets the classes to be displayed in row heading (applied both in the detail view and in the form view).

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->headingClasses('flex text-80 py-2')
```

### itemWrapperClasses

Sets the classes to be displayed wrapping the item (applied to each item in the detail view).

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->itemWrapperClasses('flex border-40 border')
```

### deleteButtonClasses

Sets the classes to be displayed in delete row button.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->deleteButtonClasses('flex items-center justify-center bg-danger text-white p-2 m-2 w-6 h-6 rounded-full cursor-pointer font-bold')
```

### sumWrapperClasses

Sets the classes to be displayed in sum wrapper.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->sumWrapperClasses('flex items-center justify-end w-full p-2')
```

### sumFieldClasses

Sets the classes to be displayed in sum field.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->sumFieldClasses('text-80')
```

### addRowButtonClasses

Sets the classes to be displayed in Add Row button.

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->addRowButtonClasses('btn btn-primary p-3 rounded cursor-pointer mt-3')
```

### rowWrapperClasses

Sets the classes to be displayed in Row wrapper (applied to each row in the form view).

```php
Row::make('Lines', [
      Text::make('Product'),
      Number::make('Price'),
    ])
    ->rowWrapperClasses('flex items-center border-40 border relative')
```

### More methods

[Shared Methods](shared-methods.md)
