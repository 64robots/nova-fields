Nova.booting((Vue, router) => {
  /** Shared */
  Vue.component('r64-default-field', require('./components/DefaultField'));
  Vue.component('r64-panel-item', require('./components/PanelItem'));

  /** Text & Number */
  Vue.component(
    'index-nova-fields-text',
    require('./components/text/IndexField')
  );
  Vue.component(
    'detail-nova-fields-text',
    require('./components/text/DetailField')
  );
  Vue.component(
    'form-nova-fields-text',
    require('./components/text/FormField')
  );

  /** Textarea */
  Vue.component(
    'index-nova-fields-textarea',
    require('./components/text/IndexField')
  );
  Vue.component(
    'detail-nova-fields-textarea',
    require('./components/textarea/DetailField')
  );
  Vue.component(
    'form-nova-fields-textarea',
    require('./components/textarea/FormField')
  );

  /** Password */
  Vue.component(
    'index-nova-fields-password',
    require('./components/password/IndexField')
  );
  Vue.component(
    'detail-nova-fields-password',
    require('./components/password/DetailField')
  );
  Vue.component(
    'form-nova-fields-password',
    require('./components/password/FormField')
  );

  /** Boolean */
  Vue.component(
    'index-nova-fields-boolean',
    require('./components/boolean/IndexField')
  );
  Vue.component(
    'detail-nova-fields-boolean',
    require('./components/boolean/DetailField')
  );
  Vue.component(
    'form-nova-fields-boolean',
    require('./components/boolean/FormField')
  );

  /** Select */
  Vue.component(
    'index-nova-fields-select',
    require('./components/text/IndexField')
  );
  Vue.component(
    'detail-nova-fields-select',
    require('./components/text/DetailField')
  );
  Vue.component(
    'form-nova-fields-select',
    require('./components/select/FormField')
  );
});
