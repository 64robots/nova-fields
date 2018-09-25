<template>
  <modal
    class="modal"
    tabindex="-1"
    role="dialog"
    @modal-close="handleClose"
  >
    <loading-view :loading="loading">
      <heading class="m-3">{{__('New')}} {{ singularName }}</heading>

      <card class="overflow-hidden">
        <form v-if="fields" @submit.prevent="createResource">
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
              type="button"
              @click="createAndAddAnother"
              class="ml-auto btn btn-default btn-primary mr-3"
            >
              {{__('Create &amp; Add Another')}}
            </button>

            <button
              dusk="create-button"
              class="btn btn-default btn-primary"
            >
              {{__('Create')}} {{ singularName }}
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
        `/nova-api/${this.resourceName}/creation-fields`
      );

      this.fields = fields;
      this.loading = false;
    },

    /**
     * Create a new resource instance using the provided data.
     */
    async createResource() {
      try {
        const response = await this.createRequest();

        this.$toasted.show(
          this.__('The :resource was created!', {
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
     * Create a new resource and reset the form
     */
    async createAndAddAnother() {
      try {
        const response = await this.createRequest();

        this.$toasted.show(
          this.__('The :resource was created!', {
            resource: this.resourceInformation.singularLabel.toLowerCase()
          }),
          { type: 'success' }
        );

        this.addedItem = true;

        // Reset the form by refetching the fields
        this.getFields();

        this.validationErrors = new Errors();
      } catch (error) {
        if (error.response.status == 422) {
          this.validationErrors = new Errors(error.response.data.errors);
        }
      }
    },

    /**
     * Send a create request for this resource
     */
    createRequest() {
      return Nova.request().post(
        `/nova-api/${this.resourceName}`,
        this.createResourceFormData()
      );
    },

    /**
     * Create the form data for creating the resource.
     */
    createResourceFormData() {
      return _.tap(new FormData(), formData => {
        _.each(this.fields, field => {
          field.fill(formData);
        });
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
