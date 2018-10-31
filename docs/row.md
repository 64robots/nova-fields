# Row

This field allows you to create horizontal rows passing a collection of [Custom Nova Fields](https://github.com/64robots/nova-fields). It also supports validation on field level.

## Demo

![Demo](http://g.recordit.co/88FYF4f7rP.gif)

## Add it to your Nova Resource

Create a new Row field passing an array of Nova Fields as a second param. Attribute name can be passed as third param.

As we are using other [R64 Nova Fields](https://github.com/64robots/nova-fields). We can customize the classes and hide the field labels.

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
    ])->fieldClasses('w-full px-8 py-6')
      ->labelClasses('w-1/2 px-8 py-6'),
```

This converts to an array of objects.

```javascript
[
  { quantity: 1, product: 'This One', price: 20 },
  { quantity: 2, product: 'That One', price: 21 }
];
```

## Localization

Set your translations in the corresponding xx.json file located in `/resources/lang/vendor/nova`

```php
  "Add Row": "Añadir Fila",
  "Delete Row": "Eliminar Fila",
  "Are you sure you want to delete this row?": "¿Estás seguro de querer eliminar esta fila?"
```
