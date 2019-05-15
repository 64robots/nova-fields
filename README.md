# Getting Started

Nova Fields is a collection of rewriten "native" fields that comes with Nova. We are also adding new fields and utilities as we find the need.

The idea here is making them more configurable and reusable in package development. These components have a lot of new methods to customize the look and feel, behavior, etc...

Vue components also emit events that propagates up to the chain so are ideal to reuse them in other Nova packages.

Looking forward to see your feedback.

## Install

Run this command in your nova project:
`composer require 64robots/nova-fields`


Usage:
```php
// use R64\NovaFields\<NAME OF THE FIELD>;
use R64\NovaFields\Boolean;

Boolean::make('Activo', 'active')
                ->yesLabel('Yeah')
                ->noLabel('Nope')
                ->hideLabelInDetail()
                ->dotClasses('some classes')
                ->successClass('bg-warning')
```
