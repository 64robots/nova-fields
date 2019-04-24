<template>
  <r64-default-field
    :hide-field="hideField"
    :field="field"
    :hide-label="hideLabelInForms"
    :field-classes="fieldClasses"
    :wrapper-classes="wrapperClasses"
    :label-classes="labelClasses"
  >
    <template slot="field">
      <loading-view :loading="loading">
        <div class="flex items-center">
          <search-input
            v-if="isSearchable && !isLocked"
            :data-testid="`${field.resourceName}-search-input`"
            @input="performSearch"
            @clear="clearSelection"
            @selected="selectResource"
            :error="hasError"
            :value="selectedResource"
            :data="availableResources"
            trackBy="value"
            searchBy="display"
            class="flex-grow mb-2"
          >
            <div
              slot="default"
              v-if="selectedResource"
              class="flex items-center"
            >
              <div
                v-if="selectedResource.avatar"
                class="mr-3"
              >
                <img
                  :src="selectedResource.avatar"
                  class="w-8 h-8 rounded-full block"
                >
              </div>
              {{ selectedResource.display }}
            </div>

            <div
              slot="option"
              slot-scope="{option, selected}"
              class="flex items-center"
            >
              <div
                v-if="option.avatar"
                class="mr-3"
              >
                <img
                  :src="option.avatar"
                  class="w-8 h-8 rounded-full block"
                >
              </div>
              {{ option.display }}
            </div>
          </search-input>

          <select
            v-if="!isSearchable || isLocked"
            :class="[inputClasses, { 'border-danger': hasError }]"
            :data-testid="`${field.resourceName}-select`"
            :dusk="field.attribute"
            @change="selectResourceFromSelectControl"
            :disabled="isLocked"
          >
            <option
              value
              disabled
              selected
            >{{ placeholder }}</option>

            <option
              v-for="resource in options"
              :key="resource.value"
              :value="resource.value"
              :selected="selectedResourceId == resource.value"
              :disabled="resource.disabled"
            >{{ resource.display}}</option>
          </select>
          <a
            v-if="field.quickCreate && !isModal"
            class="btn btn-primary p-2 rounded ml-3 mb-2 cursor-pointer"
            @click="openModal = true"
          >+</a>
        </div>
      </loading-view>

      <portal
        v-if="!isModal"
        to="modals"
      >
        <transition name="fade">
          <ModalCreate
            v-if="openModal"
            :resourceName="field.resourceName"
            @confirm="reloadResources"
            @close="openModal = false"
          />
        </transition>
      </portal>
      <!-- Trashed State -->
      <div v-if="softDeletes && !isLocked && !field.disableTrashed">
        <label
          class="flex items-center"
          @input="toggleWithTrashed"
          @keydown.prevent.space.enter="toggleWithTrashed"
        >
          <checkbox
            :dusk="field.resourceName + '-with-trashed-checkbox'"
            :checked="withTrashed"
          />

          <span class="ml-2">{{__('With Trashed')}}</span>
        </label>
      </div>

      <p
        v-if="hasError"
        class="my-2 text-danger"
      >{{ firstError }}</p>
    </template>
  </r64-default-field>
</template>

<script>
import storage from '../../storage/BelongsToFieldStorage'
import {
  TogglesTrashed,
  PerformsSearches,
  HandlesValidationErrors
} from 'laravel-nova'
import ModalCreate from '../../modals/ModalCreate'
import R64Field from '../../mixins/R64Field'

export default {
  components: { ModalCreate },

  mixins: [TogglesTrashed, PerformsSearches, HandlesValidationErrors, R64Field],

  props: {
    isModal: {
      type: Boolean,
      default: false
    },
    resourceName: String,
    field: Object,
    viaResource: {},
    viaResourceId: {},
    viaRelationship: {},

    dataSet: Array,
    withDataSet: false
  },

  data: () => ({
    loading: false,
    openModal: false,
    availableResources: [],
    initializingWithExistingResource: false,
    selectedResource: null,
    selectedResourceId: null,
    softDeletes: false,
    withTrashed: false,
    search: ''
  }),

  computed: {
    options() {
      if (!this.field.grouped) {
        return this.availableResources
      }

      const options = []
      let lastGroup = ''
      _.sortBy(this.availableResources, 'groupedBy').forEach(resource => {
        if (resource.groupedBy !== lastGroup) {
          options.push({
            disabled: true,
            value: '',
            display: resource.groupedBy
          })
          lastGroup = resource.groupedBy
        }

        options.push(resource)
      })
      return options
    },

    /**
     * Get the placeholder.
     */
    placeholder() {
      return this.field.placeholder === undefined
        ? `${this.__('Choose')} ${this.field.name}`
        : this.field.placeholder
    },

    /**
     * Determine if we are editing and existing resource
     */
    editingExistingResource() {
      return Boolean(this.field.belongsToId)
    },

    /**
     * Determine if we are creating a new resource via a parent relation
     */
    creatingViaRelatedResource() {
      return this.viaResource == this.field.resourceName && this.viaResourceId
    },

    /**
     * Determine if we should select an initial resource when mounting this field
     */
    shouldSelectInitialResource() {
      return Boolean(
        this.editingExistingResource || this.creatingViaRelatedResource
      )
    },

    /**
     * Determine if the related resources is searchable
     */
    isSearchable() {
      return this.field.searchable
    },

    /**
     * Get the query params for getting available resources
     */
    queryParams() {
      return {
        params: {
          current: this.selectedResourceId,
          first: this.initializingWithExistingResource,
          search: this.search,
          withTrashed: this.withTrashed
        }
      }
    },

    isLocked() {
      return this.viaResource == this.field.resourceName || this.readOnly
    }
  },

  watch: {
    /**
     * Setting the dataSet within row component
     */
    dataSet(data) {
      this.availableResources = data
    }
  },

  /**
   * Mount the component.
   */
  mounted() {
    this.initializeComponent()
  },

  methods: {
    async reloadResources(id) {
      this.loading = true
      await this.getAvailableResources()
      if (id) {
        this.selectedResourceId = id
        this.selectInitialResource()
      }
      this.openModal = false
      this.loading = false
    },

    initializeComponent() {
      this.withTrashed = false

      // If a user is editing an existing resource with this relation
      // we'll have a belongsToId on the field, and we should prefill
      // that resource in this field
      if (this.editingExistingResource) {
        this.initializingWithExistingResource = true
        this.selectedResourceId = this.field.belongsToId
      }

      // If the user is creating this resource via a related resource's index
      // page we'll have a viaResource and viaResourceId in the params and
      // should prefill the resource in this field with that information
      if (this.creatingViaRelatedResource) {
        this.initializingWithExistingResource = true
        this.selectedResourceId = this.viaResourceId
      }

      if (this.shouldSelectInitialResource && !this.isSearchable) {
        // If we should select the initial resource but the field is not
        // searchable we should load all of the available resources into the
        // field first and select the initial option
        this.initializingWithExistingResource = false
        this.getAvailableResources().then(() => this.selectInitialResource())
      } else if (this.shouldSelectInitialResource && this.isSearchable) {
        // If we should select the initial resource and the field is
        // searchable, we won't load all the resources but we will select
        // the initial option
        this.getAvailableResources().then(() => this.selectInitialResource())
      } else if (
        !this.shouldSelectInitialResource &&
        !this.isSearchable &&
        !this.withDataSet
      ) {
        // If we don't need to select an initial resource because the user
        // came to create a resource directly and there's no parent resource,
        // and the field is searchable we'll just load all of the resources
        this.getAvailableResources()
      } else if (this.withDataSet && this.dataSet.length) {
        // If it is within a row component and exists previous dataSet
        // it will populate the availableResources with dataSet
        this.availableResources = this.dataSet
      }

      if (!this.withDataSet) {
        // If it is within a row component and it is the 0 index this will be called
        this.determineIfSoftDeletes()
      }

      this.field.fill = this.fill
    },

    /**
     * Select a resource using the <select> control
     */
    selectResourceFromSelectControl(e) {
      this.selectedResourceId = e.target.value
      this.$emit('input', this.selectedResourceId)
      this.selectInitialResource()
    },

    /**
     * Fill the forms formData with details from this field
     */
    fill(formData) {
      if (this.selectedResource) {
        formData.append(this.field.attribute, this.selectedResource.value)
        formData.append(this.field.attribute + '_trashed', this.withTrashed)
      }
    },

    /**
     * Get the resources that may be related to this resource.
     */
    getAvailableResources() {
      return storage
        .fetchAvailableResources(
          this.resourceName,
          this.field.attribute,
          this.queryParams
        )
        .then(({ data: { resources, softDeletes, withTrashed } }) => {
          if (this.initializingWithExistingResource || !this.isSearchable) {
            this.withTrashed = withTrashed
          }

          // Turn off initializing the existing resource after the first time
          this.initializingWithExistingResource = false
          this.availableResources = resources
          this.softDeletes = softDeletes

          // Set dataSet for others nova-field's BelongsTo into nova-field's Row
          if (!this.withDataSet) {
            this.$emit('data-set-available', resources)
          }
        })
    },

    /**
     * Determine if the relatd resource is soft deleting.
     */
    determineIfSoftDeletes() {
      return storage
        .determineIfSoftDeletes(this.field.resourceName)
        .then(response => {
          this.softDeletes = response.data.softDeletes
        })
    },

    /**
     * Determine if the given value is numeric.
     */
    isNumeric(value) {
      return !isNaN(parseFloat(value)) && isFinite(value)
    },

    /**
     * Select the initial selected resource
     */
    selectInitialResource() {
      this.selectedResource = _.find(
        this.availableResources,
        r => r.value == this.selectedResourceId
      )
      this.$emit('input', this.selectedResourceId)
    },

    /**
     * Toggle the trashed state of the search
     */
    toggleWithTrashed() {
      this.withTrashed = !this.withTrashed

      // Reload the data if the component doesn't support searching
      if (!this.isSearchable) {
        this.getAvailableResources()
      }
    },

    /**
     * Update the field's internal value.
     */
    handleChange(item) {
      this.selectedResourceId = item.id

      this.$nextTick(this.selectInitialResource)
    }
  }
}
</script>
