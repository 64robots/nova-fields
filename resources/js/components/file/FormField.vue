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
      <div
        v-if="hasValue"
        class="mb-6"
      >
        <template v-if="shouldShowLoader">
          <ImageLoader
            :src="field.thumbnailUrl"
            class="max-w-xs"
            @missing="(value) => missing = value"
          />
        </template>

        <template v-if="fileValue && !field.thumbnailUrl">
          <card class="flex item-center relative border border-lg border-50 overflow-hidden p-4">
            {{ fileValue }}

            <DeleteButton
              :dusk="field.attribute + '-internal-delete-link'"
              class="ml-auto"
              v-if="shouldShowRemoveButton"
              @click="confirmRemoval"
            />
          </card>
        </template>

        <p
          v-if="field.thumbnailUrl"
          class="mt-3 flex items-center text-sm"
        >
          <DeleteButton
            :dusk="field.attribute + '-delete-link'"
            v-if="shouldShowRemoveButton"
            @click="confirmRemoval"
          >
            <span class="class ml-2 mt-1">
              {{__('Delete')}}
            </span>
          </DeleteButton>
        </p>

        <portal to="modals">
            <confirm-upload-removal-modal
              v-if="removeModalOpen"
              @confirm="removeFile"
              @close="closeRemoveModal"
            />
        </portal>
      </div>

      <span
        class="form-file mr-4"
        v-if="!isFilledRowSubfield"
      >
        <el-upload
          v-if="field.draggable"
          drag
          action
          :auto-upload="false"
          :on-change="fileChange"
          :show-file-list="false"
        >
          <div class="p-8 text-80">
            <div
              v-if="fileName"
              class="flex items-center"
            >
              <span>{{ fileName }}</span>

              <button
                v-if="field.previewBeforeUpload"
                class="appearance-none cursor-pointer text-70 hover:text-primary ml-3 pt-2"
                title="Preview"
                @click.stop.prevent="onPreviewFile"
              >
                <icon
                  type="view"
                  class="w-8"
                />
              </button>

              <button
                class="appearance-none cursor-pointer text-70 hover:text-primary ml-3"
                title="Delete"
                @click.stop.prevent="removeFile"
              >
                <icon type="delete" />
              </button>
            </div>
            <div v-else>{{__('Click here or drop the file to upload')}}</div>
          </div>
        </el-upload>
        <template v-else>
          <input
            ref="fileField"
            :dusk="field.attribute"
            :class="inputClasses"
            type="file"
            :id="idAttr"
            name="name"
            @change="fileChange"
          />
          <label
            :for="labelFor"
            class="form-file-btn btn btn-default btn-primary"
          >
            {{__('Choose File')}}
          </label>
        </template>
      </span>

      <span
        v-if="!field.draggable && !isFilledRowSubfield"
        class="text-gray-50"
      >
        {{ currentLabel }}
      </span>

      <p
        v-if="hasError"
        class="text-xs mt-2 text-danger"
      >
        {{ firstError }}
      </p>

      <portal to="modals">
        <transition name="fade">
          <modal
            v-if="showPreview"
            @modal-close="showPreview = false"
          >
            <img :src="previewFile">
          </modal>
        </transition>
      </portal>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors, Errors } from 'laravel-nova'
import { Upload } from 'element-ui'
import ImageLoader from '../../nova/ImageLoader'
import DeleteButton from '../../nova/DeleteButton'
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [HandlesValidationErrors, FormField, R64Field],

  components: { DeleteButton, ImageLoader, 'el-upload': Upload },

  data: () => ({
    file: null,
    label: 'no file selected',
    fileName: '',
    removeModalOpen: false,
    missing: false,
    deleted: false,
    uploadErrors: new Errors(),
    showPreview: false,
    previewFile: null
  }),

  mounted() {
    this.field.fill = formData => {
      if (this.file) {
        formData.append(this.field.attribute, this.file, this.fileName)
      }
    }
  },

  methods: {
    onPreviewFile() {
      if (this.isImage) {
        this.showPreview = true
        return
      }
      window.open(this.previewFile, '_blank')
    },

    /**
     * Responsd to the file change
     */
    fileChange(event) {
      // If is a el-upload event
      if (event.raw) {
        this.fileName = event.name
        this.file = event.raw
      } else {
        let path = event.target.value
        let fileName = path.match(/[^\\/]*$/)[0]
        this.fileName = fileName
        this.file = this.$refs.fileField.files[0]
      }

      this.previewFile = URL.createObjectURL(this.file)

      this.emitInputEvent()
    },

    emitInputEvent() {
      this.$emit('input', {
        file: this.file,
        name: this.fileName
      })
    },

    /**
     * Confirm removal of the linked file
     */
    confirmRemoval() {
      this.removeModalOpen = true
    },

    /**
     * Close the upload removal modal
     */
    closeRemoveModal() {
      this.removeModalOpen = false
    },

    /**
     * Remove the linked file from storage
     */
    async removeFile() {
      const {
        resourceName,
        resourceId,
        relatedResourceName,
        relatedResourceId,
        viaRelationship
      } = this
      const attribute = this.field.attribute

      if (this.isRowSubfield || !resourceId) {
        this.fileName = ''
        this.file = null
        this.value = null
        this.closeRemoveModal()
        return this.emitInputEvent()
      }

      this.uploadErrors = new Errors()

      const uri = this.viaRelationship
        ? `/nova-api/${resourceName}/${resourceId}/${relatedResourceName}/${relatedResourceId}/field/${attribute}?viaRelationship=${viaRelationship}`
        : `/nova-api/${resourceName}/${resourceId}/field/${attribute}`

      try {
        await Nova.request().delete(uri)
        this.closeRemoveModal()
        this.deleted = true
        this.$emit('file-deleted')
      } catch (error) {
        this.closeRemoveModal()

        if (error.response.status == 422) {
          this.uploadErrors = new Errors(error.response.data.errors)
        }
      }
    }
  },

  computed: {
    isFilledRowSubfield() {
      return this.fileValue && this.isRowSubfield
    },

    isRowSubfield() {
      return !!this.parentAttribute
    },

    fileValue() {
      return this.field.value || this.value
    },

    isImage() {
      if (!this.file) {
        return false
      }

      if (this.file.type.startsWith('image/')) {
        return true
      }

      return false
    },

    hasError() {
      return this.uploadErrors.has(this.fieldAttribute)
    },

    firstError() {
      if (this.hasError) {
        return this.uploadErrors.first(this.fieldAttribute)
      }
    },

    /**
     * The current label of the file field
     */
    currentLabel() {
      return this.fileName || this.label
    },

    /**
     * The ID attribute to use for the file field
     */
    idAttr() {
      return this.labelFor
    },

    /**
     * The label attribute to use for the file field
     * @return {[type]} [description]
     */
    labelFor() {
      const hash = Math.random()
        .toString(36)
        .substring(7)
      return `file-${this.field.attribute}-${hash}`
    },

    /**
     * Determine whether the field has a value
     */
    hasValue() {
      return (
        Boolean(this.fileValue || this.field.thumbnailUrl) &&
        !Boolean(this.deleted) &&
        !Boolean(this.missing)
      )
    },

    /**
     * Determine whether the field should show the loader component
     */
    shouldShowLoader() {
      return !Boolean(this.deleted) && Boolean(this.field.thumbnailUrl)
    },

    /**
     * Determine whether the field should show the remove button
     */
    shouldShowRemoveButton() {
      return Boolean(this.field.deletable) && !this.isRowSubfield
    }
  }
}
</script>
