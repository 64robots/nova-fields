# JSON

This field allows you to group together Nova Fields and merge their key-value-pairs into unique JSON.

## Demo

![Demo](http://g.recordit.co/b7alxIvlsh.gif)

## Add it to your Nova Resource

Create a new Json field passing an array of Nova Fields as second param

```php
use R64\NovaFields\JSON;

JSON::make('Content', [
    Text::make('Name'),
    Boolean::make('Active'),
    Textarea::make('Description'),
]),
```

Attribute names can be passed as third param

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
