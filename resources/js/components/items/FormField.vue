<template>
    <r64-default-field
        :field="field"
        :full-width-content="field.fullWidth"
        :hide-field="hideField"
        :hide-label="hideLabelInForms"
        :field-classes="fieldClasses"
        :wrapper-classes="wrapperClasses"
        :label-classes="labelClasses"
    >
        <template slot="field" class="nova-items-field">
            <div class="nova-items-field-input-wrapper flex border border-40 p-4" v-if="field.listFirst == false && ! maxReached">
                <input
                    :class="[errorClasses, inputClasses]"
                    v-model="newItem"
                    :type="field.inputType"
                    :placeholder="field.placeholder"
                    autocomplete="new-password"
                    @keydown.enter.prevent="addItem"
                    class="flex-1 form-control form-input form-input-bordered"
                />
                <a
                    @click="addItem"
                    v-html="field.createButtonValue"
                    v-if="field.hideCreateButton == false"
                    class="btn btn-default btn-primary ml-3 cursor-pointer font-sans"
                />
            </div>
            <ul ref="novaitemslist" :style="maxHeight" v-if="items.length" class="nova-items-field-input-items list-reset border border-40 py-2">
                <draggable v-model="items" :options="{ disabled: field.draggable == false, handle: '.sortable-handle' }">
                <li
                    v-for="(item, index) in items"
                    :key="field.attribute + '.' + index"
                    class="px-4 py-2"
                >

                    <div class="nova-items-field-input-wrapper-item flex py-1">
                        <span v-if="field.draggable" class="sortable-handle py-2 pl-0 pr-4 text-80 cursor-move">
                            |||
                        </span>
                        <input
                            :value="item"
                            :type="field.inputType"
                            v-on:keyup="updateItem(index, $event)"
                            :name="field.name + '['+ index +']'"
                            autocomplete="new-password"
                            :class="[errorClasses, inputClasses,{'border-danger': hasErrors(field.attribute + '.' + index)}]"
                            class="flex-1 form-control form-input form-input-bordered"
                        >
                        <span
                            @click="removeItem(index)"
                            style="font-size: 32px;"
                            class="ml-4 mr-2 font-thin cursor-pointer hover:font-normal"
                            v-html="field.deleteButtonValue"
                        />
                        </div>
                        <div v-if="hasErrors(field.attribute + '.' + index)" class="help-text error-text -mt-2 text-danger py-2">
                            <p v-html="arrayErrors[field.attribute + '.' + index][0]" />
                        </div>
                    </li>
                </draggable>
            </ul>
             <div class="nova-items-field-input-wrapper flex border border-40 p-4"  v-if="field.listFirst && ! maxReached">
                <input
                    :class="[errorClasses, inputClasses]"
                    v-model="newItem"
                    :type="field.inputType"
                    :placeholder="field.placeholder"
                    class="flex-1 form-control form-input form-input-bordered"
                    @keypress.enter.prevent="addItem"
                />
                <a
                    @click="addItem"
                    v-html="field.createButtonValue"
                    v-if="field.hideCreateButton == false"
                    class="btn btn-default btn-primary ml-3 cursor-pointer"
                />
            </div>
        </template>
    </r64-default-field>
</template>

<style scoped>
    .sortable-chosen {
        border: 1px solid var(--50);
        background-color: var(--20);
        box-shadow: 2px 2px 2px var(--40);
        margin-left: -5px;
    }
    .sortable-handle {
        -o-user-select: none;
        -moz-user-select: none;
        -webkit-user-select: none;
        user-select: none;
        -webkit-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        transform: rotate(90deg);
        position: relative;
        left: -5px;
        top: 5px;
    }
</style>

<script>
import draggable from 'vuedraggable'
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import R64Field from '../../mixins/R64Field'

export default {
    mixins: [FormField, HandlesValidationErrors,R64Field],

    props: ['resourceName', 'resourceId', 'field'],

    components: { draggable },

    data: function() {
        return {
            value: '',
            items: [],
            newItem: '',
            arrayErrors: []
        }
    },

    methods: {


        setInitialValue() {
            this.value = this.field.value || [];
            this.items = this.field.value || [];
        },


        fill(formData) {
            formData.append(this.field.attribute, this.value || [])
        },

		addItem() {
            const item = this.newItem.trim()

			if (item && ! this.maxReached) {
				this.items.push(item)
                this.newItem = ''

                this.$nextTick(() => {
                    if(this.field.maxHeight){
                        this.$refs.novaitemslist.scrollTop = this.$refs.novaitemslist.scrollHeight;
                    }
                })
			}
        },

        updateItem(index, event)
        {
            this.$set(this.items, index, event.target.value)
        },

		removeItem (index) {
			this.items.splice(index, 1)
        },

        hasErrors(key)
        {
            return this.arrayErrors.hasOwnProperty(key);
		}
    },
    computed: {
        maxHeight()
        {
            if(this.field.maxHeight == false) {
                return '';
            }

            return `max-height: ${this.field.maxHeight}px; overflow: auto;`;
        },
        maxReached()
        {
            return this.field.max !== false && this.items.length + 1 > this.field.max;
        }
    },
    watch: {
        'items': {
            handler: function (items) {
                this.value = JSON.stringify(items);
            },
            deep: true
        },
        'errors': {
            handler: function (errors) {
                if(errors.errors.hasOwnProperty(this.field.attribute))
                {
                    this.arrayErrors = errors.errors[this.field.attribute][0]
                }
            },
            deep: true
        }
    }
}
</script>
