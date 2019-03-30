# A collection of customizable Nova Fields

## WIP

We are working on a rewrite of every "native" field that comes with Nova.

The idea is make them more configurable and reusable in package development. If you like the idea you can collaborate with us with your awesome PRs!

### Available fields:

- [x] ID
- [x] Text
- [x] Number
- [x] Textarea
- [x] Select
- [x] Password
- [x] Boolean
- [x] Trix
- [x] File
- [x] Image
- [x] BelongsTo
- [x] Currency
- [ ] Status
- [ ] Avatar
- [ ] Gravatar
- [ ] Code
- [ ] Country
- [ ] Date
- [ ] DateTime
- [ ] Markdown
- [ ] Place
- [ ] Timezone

### Custom Fields

- [x] JSON ([Documentation](docs/json.md))
- [x] Row ([Documentation](docs/row.md))

Some examples:

```php
Boolean::make('Activo', 'active')
                ->yesLabel('Yeah')
                ->noLabel('Nope')
                ->hideLabelInDetail()
                ->dotClasses('some classes')
                ->successClass('bg-warning')
```

We are also adding events in vue components that propagates up to the chain so the parent notices when something has happened

Looking forward to see your feedback.

### Install

Run this command in your nova project:
`composer require 64robots/nova-fields`
