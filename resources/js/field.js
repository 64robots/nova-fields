Nova.booting((Vue, router) => {
  Vue.component('r64-default-field', require('./components/DefaultField'));
  Vue.component('r64-panel-item', require('./components/PanelItem'));

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
});
