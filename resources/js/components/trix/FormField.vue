<template>
  <r64-default-field
    :hide-field="hideField"
    :field="field"
    :hide-label="hideLabelInForms"
    :field-classes="fieldClasses"
    :wrapper-classes="wrapperClasses"
    :label-classes="labelClasses"
    :show-help-text="false"
  >
    <template slot="field">
      <trix
        name="trixman"
        :value="field.value"
        placeholder=""
        @change="handleChange"
        @file-add="handleFileAdd"
        @file-remove="handleFileRemove"
        :class="[{'border-danger': hasError}, inputClasses]"
        :with-files="field.withFiles"
      />

      <p v-if="hasError" class="my-2 text-danger">
        {{ firstError }}
      </p>
    </template>
  </r64-default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import Trix from '../Trix'
import R64Field from '../../mixins/R64Field'

export default {
  mixins: [HandlesValidationErrors, FormField, R64Field],

  components: { Trix },

  data: () => ({ draftId: uuidv4() }),

  beforeDestroy() {
    this.cleanUp()
  },

  methods: {
    /**
     * Update the field's internal value
     */
    handleChange(value) {
      this.value = value
      this.$emit('input', value)
    },

    fill(formData) {
      formData.append(this.field.attribute, this.value || '')
      formData.append(this.field.attribute + 'DraftId', this.draftId)
    },

    /**
     * Initiate an attachement upload
     */
    handleFileAdd({ attachment }) {
      if (attachment.file) {
        this.uploadAttachment(attachment)
      }
    },

    /**
     * Upload an attachment
     */
    uploadAttachment(attachment) {
      const data = new FormData()
      data.append('Content-Type', attachment.file.type)
      data.append('attachment', attachment.file)
      data.append('draftId', this.draftId)

      Nova.request()
        .post(
          `/nova-api/${this.resourceName}/trix-attachment/${
            this.field.attribute
          }`,
          data,
          {
            onUploadProgress: function(progressEvent) {
              attachment.setUploadProgress(
                Math.round((progressEvent.loaded * 100) / progressEvent.total)
              )
            }
          }
        )
        .then(({ data: { url } }) => {
          return attachment.setAttributes({
            url: url,
            href: url
          })
        })
    },

    /**
     * Remove an attachment from the server
     */
    handleFileRemove({ attachment: { attachment } }) {
      Nova.request()
        .delete(
          `/nova-api/${this.resourceName}/trix-attachment/${
            this.field.attribute
          }`,
          {
            params: { attachmentUrl: attachment.attributes.values.url }
          }
        )
        .then(response => {})
        .catch(error => {})
    },

    /**
     * Purge pending attachments for the draft
     */
    cleanUp() {
      if (this.field.withFiles) {
        Nova.request()
          .delete(
            `/nova-api/${this.resourceName}/trix-attachment/${
              this.field.attribute
            }/${this.draftId}`
          )
          .then(response => {})
          .catch(error => {})
      }
    }
  }
}

function uuidv4() {
  return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
    (
      c ^
      (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))
    ).toString(16)
  )
}
</script>
