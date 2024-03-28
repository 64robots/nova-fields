<template>
  <transition name="fade">
    <template v-if="view == 'grid'">
      <div @click="clickStrategy" ref="card" :loading="loading" :class="{ 'opacity-50': dragOver }"
        class="card relative flex flex-wrap justify-center border border-lg border-50 dark:border-gray-700 overflow-hidden px-0 py-0 cursor-pointer">

        <template v-if="loading">
          <div class="rounded-lg flex items-center justify-center absolute pin z-50 mx-auto w-full">
            <loader class="text-60" />
          </div>
        </template>

        <template v-if="file.id == 'folder_back'">
          <svg class="w-2/3 h-5/6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path
              d="M20 6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h7.41l2 2H20zM4 6v12h16V8h-7.41l-2-2H4z"
              fill="#B3C1D1" />
            <path fill="#b3c1d1"
              d="M12.307 10.628v5a.32.32 0 0 1-.64 0v-5l-1.68 1.71a.323.323 0 0 1-.49-.42l2.25-2.25a.32.32 0 0 1 .45 0l2.25 2.25a.323.323 0 1 1-.42.49l-1.71-1.71-.01-.07z" />
          </svg>

          <div
            class="h-1/6 w-full text-center text-xs border-t border-30 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
            {{ __('Go up') }}
          </div>
        </template>

        <template v-else>

          <svg class="w-2/3 h-5/6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path fill="#B3C1D1"
              d="M20 6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h7.41l2 2H20zM4 6v12h16V8h-7.41l-2-2H4z" />
          </svg>

          <div class="actions-grid absolute pr-2 pt-2 dark:bg-gray-700" :class="{ 'hidden': !multiSelecting }">
            <div v-if="multiSelecting">
              <input :checked="selected" type="checkbox">
            </div>
            <div v-else class="flex flex-wrap text-70">
              <div class="cursor-pointer  mr-2" v-if="deletePermission" @click.prevent="deleteFolder($event)">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                  aria-labelledby="delete" class="fill-current">
                  <path fill-rule="nonzero"
                    d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z">
                  </path>
                </svg>
              </div>
              <div class="cursor-pointer" v-if="renamePermission" @click.prevent="editFolder($event)">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit"
                  class="fill-current">
                  <path
                    d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z">
                  </path>
                </svg>
              </div>
            </div>
          </div>

          <div
            class="h-1/6 w-full text-center text-xs  border-t border-30 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 flex items-center justify-center ">
            {{ file.name }}
          </div>

        </template>


      </div>
    </template>

    <template v-else-if="view == 'list'">

      <template v-if="file.id == 'folder_back'">
        <tr @click="goToFolder" :loading="loading" v-bind:key="file.id" class="cursor-pointer">
          <td class="w-8" v-if="multiSelecting">
          </td>
          <td>
            <div class="w-full flex justify-center items-center">
              <template v-if="loading">
                <div class="rounded-lg flex items-center justify-center absolute pin z-50 mx-auto w-full">
                  <loader class="text-60" />
                </div>
              </template>

              <svg class="w-24 h-24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  d="M20 6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h7.41l2 2H20zM4 6v12h16V8h-7.41l-2-2H4z"
                  fill="#B3C1D1" />
                <path fill="#b3c1d1"
                  d="M12.307 10.628v5a.32.32 0 0 1-.64 0v-5l-1.68 1.71a.323.323 0 0 1-.49-.42l2.25-2.25a.32.32 0 0 1 .45 0l2.25 2.25a.323.323 0 1 1-.42.49l-1.71-1.71-.01-.07z" />
              </svg>
            </div>
          </td>
          <td class="px-2">{{ __('Go up') }}</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </template>
      <template v-else>
        <tr @click="clickStrategy" :loading="loading" v-bind:key="file.id" class="cursor-pointer">
          <td class="w-8" v-if="multiSelecting">
          </td>
          <td>
            <div class="w-full flex justify-center items-center">
              <template v-if="loading">
                <div class="rounded-lg flex items-center justify-center absolute pin z-50 mx-auto w-full">
                  <loader class="text-60" />
                </div>
              </template>

              <svg class="w-24 h-24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="#B3C1D1"
                  d="M20 6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h7.41l2 2H20zM4 6v12h16V8h-7.41l-2-2H4z" />
              </svg>
            </div>
          </td>

          <td class="p-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            {{ file.name }}
          </td>

          <td class="p-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            <template v-if="file.size">{{ file }}</template>
          </td>

          <td class="text-center p-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            {{ file.date }}
          </td>
          <td
            class="text-center py-1 pl-2 border-t border-gray-100 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
            <div class="flex items-center justify-end">
              <div aria-label="Rename"  @click.prevent="editFolder($event)"  v-if="renamePermission" class="toolbar-button hover:text-primary-500 v-popper--has-tooltip" title="Rename" >
                <svg data-v-4c9b71d8="" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20" class="inline-block tooltip inline-block cursor-pointer hover:opacity-50 mr-2" role="presentation"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
              </div>
              <div aria-label="Delete"  @click.prevent="deleteFolder($event)"  v-if="deletePermission" class="toolbar-button hover:text-primary-500 v-popper--has-tooltip" title="Delete" >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20" class="inline-block text-red-500" role="presentation" data-v-5a3b7268=""><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              </div>

              <div aria-label="Move"  @click.prevent="moveFolder($event)"  v-if="movePermission" class="toolbar-button hover:text-primary-500 v-popper--has-tooltip" title="Move" >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20" class="inline-block inline-block inline-block cursor-pointer hover:opacity-50 mr-2 ml-2" role="presentation" data-v-5a3b7268=""><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
              </div>
              <!-- <Icon type="pencil-alt" width="20" height="20" v-if="renamePermission" class="inline-block cursor-pointer hover:opacity-50 mr-2"
                @click.prevent="editFolder($event)" />
              <Icon type="trash" class="text-red-500" width="20" height="20" v-if="deletePermission" 
                @click.prevent="deleteFolder($event)" />
              <Icon type="document-duplicate" class="inline-block inline-block cursor-pointer hover:opacity-50 mr-2 ml-2" width="20" height="20"   v-if="movePermission" @click.prevent="moveFolder($event)" /> -->
            </div>
          </td>
        </tr> 
      </template>


    </template>
  </transition>
</template>

<script>
import findIndex from 'lodash/findIndex';

export default {
  props: {
    file: {
      type: Object,
      default: function () {
        return { name: '' };
      },
      required: true,
    },
    view: {
      type: String,
      default: 'grid',
      required: false,
    },
    multiSelecting: {
      type: Boolean,
      default: false,
      required: false,
    },
    selectedFiles: {
      type: Array,
      default: () => [],
      required: false,
    },
    deletePermission: {
      type: Boolean,
      required: false,
      default: true,
    },
    renamePermission: {
      type: Boolean,
      required: false,
      default: true,
    },
    movePermission: {
      type: Boolean,
      required: false,
      default: true,
    },
  },

  data: () => ({
    loading: true,
    missing: false,
    dragOver: false,
  }),

  mounted() {
    this.loading = false;
  },

  computed: {
    selected() {
      return (
        findIndex(this.selectedFiles, { type: this.file.type, path: this.file.path }) >= 0
      );
    },
  },

  methods: {
    clickStrategy() {
      return this.goToFolder();
    },

    goToFolder() {
      this.$emit('goToFolderEvent', this.file.path);
    },

    deleteFolder(e) {
      this.stopDefaultActions(e);
      this.$emit('delete', 'folder', this.file.path);
    },

    moveFolder(e) {
      this.stopDefaultActions(e);
      this.$emit('move', 'folder', this.file.path);
    },

    editFolder(e) {
      this.stopDefaultActions(e);
      this.$emit('rename', 'folder', this.file.path);
    },

    stopDefaultActions(e) {
      e.preventDefault();
      e.stopPropagation();
    },

    select() {
      this.$emit('select', {
        type: this.file.type,
        path: this.file.path,
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.card {
  padding: 0 !important;

  &:hover {
    >svg {
      opacity: 0.5;
    }

    >.actions-grid {
      display: flex;
    }
  }
}

.actions-grid {
  display: flex;
  right: 0;
  position: absolute;
  top: 0;
  padding-left: 0.5rem;
  padding-right: 0.5rem;
  padding-top: 0.5rem;
  padding-bottom: 0.25rem;
  border-bottom-left-radius: 0.5rem;
}</style>
