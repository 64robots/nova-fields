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
    require('./components/select/IndexField')
  );
  Vue.component(
    'detail-nova-fields-select',
    require('./components/text/DetailField')
  );
  Vue.component(
    'form-nova-fields-select',
    require('./components/text/FormField')
  );
});
