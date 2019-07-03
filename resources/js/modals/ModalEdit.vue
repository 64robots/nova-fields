<template>
  <modal
    class="modal"
    tabindex="-1"
    role="dialog"
    @modal-close="handleClose"
  >
    <loading-view :loading="loading">
      <heading class="m-3">{{__('Edit')}} {{ singularName }} #{{ resourceId }}</heading>

      <card class="overflow-hidden">
        <form v-if="fields" @submit.prevent="updateResource">
          <!-- Validation Errors -->
          <validation-errors :errors="validationErrors"/>

          <!-- Fields -->
          <div
            v-for="field in fields"
            :key="field.component"
          >
            <component
              :is="'form-' + field.component"
              :errors="validationErrors"
              :resource-name="resourceName"
              :field="field"
              is-modal
            />
          </div>

          <div class="bg-30 flex px-8 py-4">
            <button
              type="button"
              @click="handleClose"
              class="ml-auto btn btn-default btn-danger mr-3"
            >
              {{__('Cancel')}}
            </button>

            <button
              dusk="update-button"
              class="btn btn-default btn-primary"
            >
              {{__('Update')}} {{ singularName }}
            </button>
          </div>
        </form>
      </card>
    </loading-view>
  </modal>
</template>

<script>
import {
  Errors,
  Minimum,
  InteractsWithResourceInformation
} from 'laravel-nova';

export default {
  mixins: [InteractsWithResourceInformation],

  props: {
    resourceName: {
      type: String,
      required: true
    },
    resourceId: {
      type: Number,
      required: true
      
    }
  },

  data: () => ({
    addedItem: false,
    loading: true,
    fields: [],
    validationErrors: new Errors()
  }),

  created() {
    this.getFields();
  },

  methods: {
    handleClose() {
      const event = this.addedItem ? 'confirm' : 'close';
      this.$emit(event);
    },

    /**
     * Get the available fields for the resource.
     */
    async getFields() {
      this.fields = [];

      const { data: fields } = await Nova.request().get(
        `/nova-api/${this.resourceName}/${this.resourceId}/update-fields`, {
            params: {
                editing: true,
                editMode: 'update',
            }
        }
      )
      let fieldsArr = (fields.hasOwnProperty('fields')) ? fields.fields : fields;
        
      this.fields = fieldsArr;
      this.loading = false;
    },

    /**
     * Update a new resource instance using the provided data.
     */
    async updateResource() {
      try {
        const response = await this.updateRequest();

        this.$toasted.show(
          this.__('The :resource was updated!', {
            resource: this.resourceInformation.singularLabel.toLowerCase()
          }),
          { type: 'success' }
        );

        const { id } = response.data;
        this.$emit('confirm', id);
      } catch (error) {
        if (error.response.status == 422) {
          this.validationErrors = new Errors(error.response.data.errors);
        }
      }
    },


    /**
     * Send a update request for this resource
     */
    updateRequest() {
      return Nova.request().post(
        `/nova-api/${this.resourceName}/${this.resourceId}`,
        this.updateResourceFormData()
      );
    },

    /**
     * Update the form data for creating the resource.
     */
    updateResourceFormData() {
      return _.tap(new FormData(), formData => {
        _.each(this.fields, field => {
          field.fill(formData);
        });
        formData.append('_method', 'PUT');
      });
    }
  },

  computed: {
    singularName() {
      return this.resourceInformation.singularLabel;
    }
  }
};
</script>
