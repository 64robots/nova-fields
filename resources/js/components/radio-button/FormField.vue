<template>
    <r64-default-field
        :field="field"
        :errors="errors"
        :hide-field="hideField"
        :hide-label="hideLabelInForms"
        :field-classes="fieldClasses"
        :wrapper-classes="wrapperClasses"
        :label-classes="labelClasses"
    >

      <template slot="field">
          <label v-for="(option, val) in field.options" :class="{'mb-2' : field.stack || field.addPadding}" :for="`${field.attribute}_${val}`">
            <input :class="[errorClasses, inputClasses]" v-model="value" :value="val" :id="`${field.attribute}_${val}`" :name="field.attribute" type="radio" :disabled="field.disabled">
            <span class="mlbz-radio-label">{{ getOptionLabel(option) }}</span>
            <span v-if="field.stack && hasOptionHint(option)" class="mlbz-radio-hint mt-1 block text-sm text-80 leading-normal">{{ getOptionHint(option) }}</span>
          </label>
      </template>
    </r64-default-field>
</template>

<script>
import R64Field from '../../mixins/R64Field'
import HasOptions from '../../mixins/HasOptions';
import CanToggle from '../../mixins/CanToggle';
    import {FormField, HandlesValidationErrors} from 'laravel-nova';

    export default {
        mixins: [FormField, HandlesValidationErrors, HasOptions, CanToggle,R64Field],

        props: ['resourceName', 'resourceId', 'field'],

        computed: {
            rawValue() {
                return this.value;
            }
        },

        methods: {
            /*
             * Set the initial, internal value for the field.
             */
            setInitialValue() {
                if (this.value) {
                    return;
                }

                if (this.field.value !== null) {
                    return this.value = this.field.value;
                }

				if (this.field.hasOwnProperty('default')) {
					return this.value = this.field.default;
				}

				return this.value = ''
            },

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                const value = this.value !== null ? this.value : this.field.default;
                formData.append(this.field.attribute, value);
            },

        }
    }
</script>

<style>
    .mlbz-radio-label {
        padding-left: 5px;
    }

    .mlbz-radio-hint {
        padding-left: 24px;
    }

    .mlbz-hidden {
        display: none !important;
    }
</style>
