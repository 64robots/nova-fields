# Nova Core Fields

## ID

[Shared Methods](shared-methods.md)

## Text

[Shared Methods](shared-methods.md)

## Number

### colors

Indicate that value should be displayed as danger when < 0.

```php
Number::make('Price')->colors()
```

[Shared Methods](shared-methods.md)

## Textarea

### showContentLabel

Sets the Show Content label.

```php
Textarea::make('Comments')->showContentLabel('Show Comments')
```

### hideContentLabel

Sets the Hide Content label.

```php
Textarea::make('Comments')->hideContentLabel('Hide Comments')
```

[Shared Methods](shared-methods.md)

## Select

[Shared Methods](shared-methods.md)

## Password

### maskLabel

Sets the mask label.

```php
Password::make('Password')->maskLabel('******')
```

[Shared Methods](shared-methods.md)

## Boolean

### yesLabel

Set the label near to the dot when boolean is true.

```php
Boolean::make('Active')->yesLabel('Yeah')
```

### noLabel

Set the label near to the dot when boolean is false.

```php
Boolean::make('Active')->noLabel('Disabled')
```

### hideBooleanLabel

Indicate that the label near to the dot should be hidden.

```php
Boolean::make('Active')->hideBooleanLabel()
```

### dotClasses

Set the classes that should be applied to the status dot.

```php
Boolean::make('Active')->dotClasses('inline-block rounded-full w-2 h-2 mr-1')
```

### successClass

Set the class that should be applied to the dot when boolean is true.

```php
Boolean::make('Active')->successClass('bg-success')
```

### dangerClass

Set the class that should be applied to the dot when boolean is false.

```php
Boolean::make('Active')->dangerClass('bg-danger')
```

[Shared Methods](shared-methods.md)

## Trix

[Shared Methods](shared-methods.md)

## File

### draggable

Whether the file can be dropped on the input.

```php
File::make('Document')->draggable()
```

### previewBeforeUpload

Whether the file can be previewed before upload.

```php
File::make('Document')->previewBeforeUpload()
```

[Shared Methods](shared-methods.md)

## Image

[Shared Methods](shared-methods.md)

## BelongsTo

### quickCreate

Determine if a new related model can be created without leaving the main form.

```php
BelongsTo::make('Category')->quickCreate()
```

You can pass fill values for this new modal form

```php
BelongsTo::make('Category')->quickCreate(['campaign' => 1])
```

### groupedBy

Set the field that should be used to group the resources.

```php
BelongsTo::make('Category')->groupedBy('campaignName')
```

It also works with nested relations

```php
BelongsTo::make('Category')->groupedBy('campaign.name')
```

### displayName

Set the field that should be used to be displayed in a Row Field.

```php
Row::make('Lines', [
      BelongsTo::make('Category')->displayName('slug')
    ])
```

### disableTrashed

Determine if a without trashed option should be hidden.

```php
BelongsTo::make('Category')->disableTrashed()
```

[Shared Methods](shared-methods.md)

## Currency

### colors

Indicate that value should be displayed as danger when < 0.

```php
Currency::make('Price')->colors()
```

[Shared Methods](shared-methods.md)

## Date

[Shared Methods](shared-methods.md)

## DateTime

### hideTimezone

Indicate that you don't want to show the user timezone in this field.

```php
DateTime::make('Created At', 'created_at')->hideTimezone()
```

[Shared Methods](shared-methods.md)
