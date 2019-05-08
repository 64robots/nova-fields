Nova.booting((Vue, router) => {
  /** Shared */
  Vue.component('r64-default-field', require('./components/DefaultField'))
  Vue.component('r64-panel-item', require('./components/PanelItem'))
  Vue.component('r64-excerpt', require('./components/Excerpt'))

  /** Text & Number */
  Vue.component(
    'index-nova-fields-text',
    require('./components/text/IndexField')
  )
  Vue.component(
    'detail-nova-fields-text',
    require('./components/text/DetailField')
  )
  Vue.component('form-nova-fields-text', require('./components/text/FormField'))

  /** Computed */
  Vue.component(
    'index-nova-fields-computed',
    require('./components/text/IndexField')
  )
  Vue.component(
    'detail-nova-fields-computed',
    require('./components/text/DetailField')
  )
  Vue.component(
    'form-nova-fields-computed',
    require('./components/computed/FormField')
  )

  /** Textarea */
  Vue.component(
    'index-nova-fields-textarea',
    require('./components/text/IndexField')
  )
  Vue.component(
    'detail-nova-fields-textarea',
    require('./components/textarea/DetailField')
  )
  Vue.component(
    'form-nova-fields-textarea',
    require('./components/textarea/FormField')
  )

  /** Password */
  Vue.component(
    'index-nova-fields-password',
    require('./components/password/IndexField')
  )
  Vue.component(
    'detail-nova-fields-password',
    require('./components/password/DetailField')
  )
  Vue.component(
    'form-nova-fields-password',
    require('./components/password/FormField')
  )

  /** Boolean */
  Vue.component(
    'index-nova-fields-boolean',
    require('./components/boolean/IndexField')
  )
  Vue.component(
    'detail-nova-fields-boolean',
    require('./components/boolean/DetailField')
  )
  Vue.component(
    'form-nova-fields-boolean',
    require('./components/boolean/FormField')
  )

  /** Select */
  Vue.component(
    'index-nova-fields-select',
    require('./components/text/IndexField')
  )
  Vue.component(
    'detail-nova-fields-select',
    require('./components/text/DetailField')
  )
  Vue.component(
    'form-nova-fields-select',
    require('./components/select/FormField')
  )

  /** Autocomplete */
  Vue.component(
    'index-nova-fields-autocomplete',
    require('./components/text/IndexField')
  )
  Vue.component(
    'detail-nova-fields-autocomplete',
    require('./components/text/DetailField')
  )
  Vue.component(
    'form-nova-fields-autocomplete',
    require('./components/autocomplete/FormField')
  )

  /** File & Image */
  Vue.component(
    'index-nova-fields-file',
    require('./components/file/IndexField')
  )
  Vue.component(
    'detail-nova-fields-file',
    require('./components/file/DetailField')
  )
  Vue.component('form-nova-fields-file', require('./components/file/FormField'))

  /** Trix */
  Vue.component(
    'detail-nova-fields-trix',
    require('./components/textarea/DetailField')
  )
  Vue.component('form-nova-fields-trix', require('./components/trix/FormField'))

  /** R64 Fields */

  /** Row */
  Vue.component('index-nova-fields-row', require('./components/row/IndexField'))
  Vue.component(
    'detail-nova-fields-row',
    require('./components/row/DetailField')
  )
  Vue.component('form-nova-fields-row', require('./components/row/FormField'))

  /** JSON */
  Vue.component(
    'index-nova-fields-json',
    require('./components/json/IndexField')
  )
  Vue.component(
    'detail-nova-fields-json',
    require('./components/json/DetailField')
  )
  Vue.component('form-nova-fields-json', require('./components/json/FormField'))

  /** RELATIONS */

  /** BelongsTo */
  Vue.component(
    'index-nova-fields-belongs-to',
    require('./components/belongs-to/IndexField')
  )
  Vue.component(
    'detail-nova-fields-belongs-to',
    require('./components/belongs-to/DetailField')
  )
  Vue.component(
    'form-nova-fields-belongs-to',
    require('./components/belongs-to/FormField')
  )
})
