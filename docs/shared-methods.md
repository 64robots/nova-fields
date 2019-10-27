# Shared Methods

There are some methods available for every field that allow customizing the look and feel and its behavior. For field specific method visit the documentantion for that field

## Overriding default classes

### fieldClasses

Set the field wrapper classes that should be applied in the create / edit views.

```php
use R64\NovaFields\Text;

Text::make('Name')->fieldClasses('w-1/2 px-8 py-8'),
```

### indexClasses

Set the field classes that should be applied in the index view.

```php
use R64\NovaFields\Text;

Text::make('Name')->indexClasses('whitespace-no-wrap font-bold'),
```

### inputClasses

Set the input classes that should be applied in the create / edit views.

```php
use R64\NovaFields\Text;

Text::make('Name')->inputClasses('w-1/2 form-control form-input form-input-bordered'),
```

### labelClasses

Set the label classes that should be applied.

```php
use R64\NovaFields\Text;

Text::make('Name')->labelClasses('w-1/5 px-8 py-8'),
```

### wrapperClasses

Set the container classes that should be applied.

```php
use R64\NovaFields\Text;

Text::make('Name')->wrapperClasses('flex border-b border-40 border-t'),
```

### panelLabelClasses

Set the label wrapper classes that should be applied in the panel of detail view.

```php
use R64\NovaFields\Text;

Text::make('Name')->panelLabelClasses('w-1/4 py-4'),
```

### panelFieldClasses

Set the field wrapper classes that should be applied in the panel of detail view.

```php
use R64\NovaFields\Text;

Text::make('Name')->panelFieldClasses('w-3/4 py-4'),
```

### panelWrapperClasses

Set the wrapper classes that should be applied in the panel of detail view.

```php
use R64\NovaFields\Text;

Text::make('Name')->panelWrapperClasses('flex border-b border-40'),
```

### excerptClasses

Set the excerpt classes that should be applied.

```php
use R64\NovaFields\Textarea;

Textare::make('Name')->excerptClasses('cursor-pointer dim inline-block text-primary font-bold'),
```

## Adding or Removing classes from the defaults

If you prefer, instead of setting all the classes, you might add or remove one or more classes to the defaults.

Just add the prefix `add` or `remove` to the name of the method

```php
use R64\NovaFields\Text;

Text::make('Name')->addWrapperClasses('border-t'),
// flex border-b border-40 border-t

Text::make('Name')->removeWrapperClasses('border-40'),
// flex border-b
```

## Showing / Hidding / Disabling fields

As our fields extend from the core Nova fields you can use existing methods such as

- hideWhenCreating
- hideWhenUpdating
- onlyOnIndex
- onlyOnDetail
- onlyOnForms
- exceptOnForms

Futhermore you can use also

### hideLabelInForms

Indicate that the label should be hidden in forms.

```php
use R64\NovaFields\Text;

Text::make('Name')->hideLabelInForms(),

```

### hideLabelInDetail

Indicate that the label should be hidden in detail view.

```php
use R64\NovaFields\Text;

Text::make('Name')->hideLabelInDetail(),

```

### onlyWhen

Show or hide the field based on other field value. Only available as subfield of [Json](custom.md#json) field.

```php
use R64\NovaFields\JSON;
use R64\NovaFields\Text;

JSON::make('Group', [
  Text::make('First Name'),
  Text::make('Last Name')->onlyWhen('first_name'),
])

```

### readOnly

Sets the input as disabled. A callback can be passed as param

```php
use R64\NovaFields\Text;

  Text::make('Name')->readOnly(),

  // or

  Text::make('Name')->readOnly(function() {
    return $this->whatever;
  }),
])
```

### readOnlyOnCreate

Set the input as disabled on create view.

```php
use R64\NovaFields\Text;

  Text::make('Name')->readOnlyOnCreate(),
])
```

### readOnlyOnUpdate

Set the input as disabled on update view.

```php
use R64\NovaFields\Text;

  Text::make('Name')->readOnlyOnUpdate(),
])
```

### showLinkInIndex

Specify that the index view should show as a link to the resource.

```php
use R64\NovaFields\Text;

  Text::make('Name')->showLinkInIndex(),
])
```

## Other methods

### placeholder

Sets the input placeholder

```php
use R64\NovaFields\Text;

  Text::make('Name')->placeholder(),
])
```
