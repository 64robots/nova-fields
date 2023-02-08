import DefaultField from './components/DefaultField.vue'
import PanelItem from './components/PanelItem.vue'
import DateIndexField from './components/date/IndexField.vue'
import Excerpt from './components/Excerpt.vue'
import DateDetailField from './components/date/DetailField.vue'
import DateTimeIndexField from './components/date-time/IndexField.vue'
import DateTimeDetailField from './components/date-time/DetailField.vue'
import DateTimeFormField from './components/date-time/FormField.vue'
import DateFormField from './components/date/FormField.vue'
import TextIndexField from './components/text/IndexField.vue'
import TextDetailField from './components/text/DetailField.vue'
import TextFormField from './components/text/FormField.vue'
import MultiSelectDualBoxIndexField from './components/multi-select-dual-box/IndexField.vue'
import MultiSelectDualBoxDetailField from './components/multi-select-dual-box/DetailField.vue'
import MultiSelectDualBoxFormField from './components/multi-select-dual-box/FormField.vue'
import HeadingIndexField from './components/heading/IndexField.vue'
import HeadingDetailField from './components/heading/DetailField.vue'
import HeadingFormField from './components/heading/FormField.vue'
import ComputedFormField from './components/computed/FormField.vue'
import TextAreaDetailField from './components/textarea/DetailField.vue'
import TextAreaFormField from './components/textarea/FormField.vue'
import PasswordIndexField from './components/password/IndexField.vue'
import PasswordDetailField from './components/password/DetailField.vue'
import PasswordFormField from './components/password/FormField.vue'
import BooleanIndexField from './components/boolean/IndexField.vue'
import BooleanDetailField from './components/boolean/DetailField.vue'
import BooleanFormField from './components/boolean/FormField.vue'
import BooleanGroupIndexField from './components/boolean-group/IndexField.vue'
import BooleanGroupDetailField from './components/boolean-group/DetailField.vue'
import BooleanGroupFormField from './components/boolean-group/FormField.vue'
import RadioButtonIndexField from './components/radio-button/IndexField.vue'
import RadioButtonDetailField from './components/radio-button/DetailField.vue'
import RadioButtonFormField from './components/radio-button/FormField.vue'
import SelectFormField from './components/select/FormField.vue'
import AutoCompleteFormField from './components/autocomplete/FormField.vue'
import FileIndexField from './components/file/IndexField.vue'
import FileDetailField from './components/file/DetailField.vue'
import FileFormField from './components/file/FormField.vue'
import TrixFormField from './components/trix/FormField.vue'
import RowDetailField from './components/row/DetailField.vue'
import JsonIndexField from './components/json/IndexField.vue'
import RowIndexField from './components/row/IndexField.vue'
import JsonDetailField from './components/json/DetailField.vue'
import BelongsToIndexField from './components/belongs-to/IndexField.vue'
import RowFormField from './components/row/FormField.vue'
import JsonFormField from './components/json/FormField.vue'
import SlugFormField from './components/slug/FormField.vue'
import MultiselectIndexField from './components/multiselect/IndexField.vue'
import MultiselectDetailField from './components/multiselect/DetailField.vue'
import MultiselectFormField from './components/multiselect/FormField.vue'
import FileManagerIndexField from './components/filemanager/IndexField.vue'
import FileManagerDetailField from './components/filemanager/DetailField.vue'
import FileManagerFormField from './components/filemanager/FormField.vue'
import Tool from './components/Tool.vue'
import SimpleRepeatableDetailField from './components/simple-repetable/DetailField.vue'
import SimpleRepeatableFormField from './components/simple-repetable/FormField.vue'
import BlankDivFormField from './components/blank-div/FormField.vue'
import DynamicSelectFormField from './components/dynamic-select/FormField.vue';
// import BelongsToDetailField from './components/belongs-to/DetailField.vue'
// import BelongsToFormField from './components/belongs-to/FormField.vue'
// import IdIndexField from './components/id/IndexField.vue'
// import NovaMultiselectDetailFieldValue from './components/multiselect/NovaMultiselectDetailFieldValue.vue'
// import ItemsIndexField from './components/items/IndexField.vue'
// import ItemsDetailField from './components/items/DetailField.vue'
// import ItemDetailField from './components/items/DetailFieldItem.vue'
// import ItemsFormField from './components/items/FormField.vue'

Nova.booting((app, store) => {
    /** Shared */
    app.component('r64-default-field', DefaultField)
    app.component('r64-panel-item', PanelItem)
    app.component('r64-excerpt', Excerpt)

    /** Text & Number */
    app.component('index-nova-fields-text', TextIndexField)
    app.component('detail-nova-fields-text', TextDetailField)
    app.component('form-nova-fields-text', TextFormField)

    /** ID */
    // app.component('index-nova-fields-id',IdIndexField)
    app.component('detail-nova-fields-id',TextDetailField)
    app.component('form-nova-fields-id',TextFormField)

    /** Slug */
    app.component('index-nova-fields-slug',TextIndexField)
    app.component('detail-nova-fields-slug', TextDetailField)
    app.component('form-nova-fields-slug', SlugFormField)

    /** BooleanGroup */
    app.component('index-nova-fields-boolean-group', BooleanGroupIndexField)
    app.component('detail-nova-fields-boolean-group', BooleanGroupDetailField)
    app.component('form-nova-fields-boolean-group', BooleanGroupFormField)

    /** RadioButton */
    app.component('index-nova-fields-radio', RadioButtonIndexField);
    app.component('detail-nova-fields-radio', RadioButtonDetailField);
    app.component('form-nova-fields-radio', RadioButtonFormField);

    /** MultiSelect */
    app.component('index-nova-fields-multiselect', MultiselectIndexField);
    app.component('detail-nova-fields-multiselect', MultiselectDetailField);
    app.component('form-nova-fields-multiselect', MultiselectFormField);
    // Allow user to overwrite nova-multiselect-detail-field-value
    // if (!app.options.components['nova-multiselect-detail-field-value']) {
    //   app.component('nova-multiselect-detail-field-value',NovaMultiselectDetailFieldValue);
    // }

    /** Items */
    // app.component('index-nova-fields-items', ItemsIndexField)
    // app.component('detail-nova-fields-items', ItemsDetailField)
    // app.component('form-nova-fields-items', ItemsFormField)
    // app.component('detail-nova-fields-item', ItemDetailField)
    //
    // /** FileManager */
    app.component('index-nova-fields-filemanager', FileManagerIndexField);
    app.component('detail-nova-fields-filemanager', FileManagerDetailField);
    app.component('form-nova-fields-filemanager', FileManagerFormField);

    /** Heading */
    app.component('index-nova-fields-heading', HeadingIndexField);
    app.component('detail-nova-fields-heading', HeadingDetailField);
    app.component('form-nova-fields-heading', HeadingFormField);

    /** Date */
    app.component('index-nova-fields-date', DateIndexField)
    app.component('detail-nova-fields-date', DateDetailField)
    app.component('form-nova-fields-date', DateFormField)
    //
    // /** DateTime */
    app.component('index-nova-fields-date-time', DateTimeIndexField)
    app.component('detail-nova-fields-date-time', DateTimeDetailField)
    app.component('form-nova-fields-date-time', DateTimeFormField)

    /** Computed */
    app.component('index-nova-fields-computed',TextIndexField)
    app.component('detail-nova-fields-computed', TextDetailField)
    app.component('form-nova-fields-computed', ComputedFormField)

    /** Textarea */
    app.component('index-nova-fields-textarea',TextIndexField)
    app.component('detail-nova-fields-textarea', TextAreaDetailField)
    app.component('form-nova-fields-textarea', TextAreaFormField)

    /** Password */
    app.component('index-nova-fields-password', PasswordIndexField)
    app.component('detail-nova-fields-password', PasswordDetailField)
    app.component('form-nova-fields-password', PasswordFormField)

    /** Boolean */
    app.component('index-nova-fields-boolean', BooleanIndexField)
    app.component('detail-nova-fields-boolean', BooleanDetailField)
    app.component('form-nova-fields-boolean', BooleanFormField)

    /** Select */
    app.component('index-nova-fields-select',TextIndexField)
    app.component('detail-nova-fields-select', TextDetailField)
    app.component('form-nova-fields-select', SelectFormField)

    /** Autocomplete */
    app.component('index-nova-fields-autocomplete',TextIndexField)
    app.component('detail-nova-fields-autocomplete', TextDetailField)
    app.component('form-nova-fields-autocomplete', AutoCompleteFormField)

    /** File & Image */
    app.component('index-nova-fields-file', FileIndexField)
    app.component('detail-nova-fields-file', FileDetailField)
    app.component('form-nova-fields-file', FileFormField)

    /** Trix */
    app.component('detail-nova-fields-trix', TextAreaDetailField)
    app.component('form-nova-fields-trix', TrixFormField)

    /** R64 Fields */

    /** Row */
    app.component('index-nova-fields-row', RowIndexField)
    app.component('detail-nova-fields-row', RowDetailField)
    app.component('form-nova-fields-row', RowFormField)

    /** JSON */
    app.component('index-nova-fields-json', JsonIndexField)
    app.component('detail-nova-fields-json', JsonDetailField)
    app.component('form-nova-fields-json', JsonFormField)

    /** RELATIONS */

    /** BelongsTo */
    app.component('index-nova-fields-belongs-to', BelongsToIndexField)
    // app.component('detail-nova-fields-belongs-to', BelongsToDetailField)
    // app.component('form-nova-fields-belongs-to', BelongsToFormField)

    app.component('nova-filemanager',Tool);

    /* simple repeatable */
    app.component('detail-nova-fields-simple-repeatable', SimpleRepeatableDetailField)
    app.component('form-nova-fields-simple-repeatable', SimpleRepeatableFormField);

    /* Blank Div */
    app.component('form-nova-fields-blank-div', BlankDivFormField)

    /* MultiSelectDualBox */
    app.component('index-nova-fields-multi-select-dual-box', MultiSelectDualBoxIndexField)
    app.component('detail-nova-fields-multi-select-dual-box', MultiSelectDualBoxDetailField)
    app.component('form-nova-fields-multi-select-dual-box', MultiSelectDualBoxFormField);

    /* dynamic select */
    app.component('index-nova-fields-dynamic-select', TextIndexField);
    app.component('detail-nova-fields-dynamic-select', TextDetailField);
    app.component('form-nova-fields-dynamic-select', DynamicSelectFormField);
});
