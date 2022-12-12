<template>
  <div>
    <portal to="portal-filemanager" name="File Details" transition="fade-transition">
      <modal v-if="active" @modal-close="handleClose">
        <div class="bg-white rounded-lg shadow-lg" style="width: 70vw;">

          <div class="bg-30 flex flex-wrap border-b border-70">
            <div class="w-3/4 px-4 py-3 ">
              {{ __('Preview of') }} <span class="text-primary-70%">{{ info.name }}</span>


            </div>

            <div class="w-1/4 flex flex-wrap justify-end">
              <button class="btn buttons-actions" v-on:click="closePreview">X</button>
            </div>
          </div>

          <div class="flex flex-wrap">
            <div class="w-3/5 box-preview flex justify-center" :class="cssType">

              <template v-if="info.type == 'image'">
                <ImageInfo :file="info" :preview="true" :css="'card relative w-full flex flex-wrap justify-center items-center overflow-hidden px-0 py-0'"/>
              </template>

              <template v-else-if="info.type == 'audio'">
                <audio ref="audio" controls>
                  <source :src="info.src" :type="info.mime"/>
                </audio>
              </template>

              <template v-else-if="info.type == 'video'">
                <video ref="video" controls crossorigin playsinline>
                  <source :src="info.url" :type="info.mime"/>
                </video>
              </template>


              <template v-else-if="info.type == 'text'">
                <codemirror v-if="codeLoaded" ref="code" :value="info.source" :options="cmOptions" >
                </codemirror>
              </template>

              <template v-else-if="info.type == 'zip'">
                <TreeView v-if="zipLoaded" :json="info.source" :name="info.name">
                </TreeView>
              </template>

              <!-- <template v-else-if="info.type == 'word'">
                  <iframe :src="'https://view.officeapps.live.com/op/embed.aspx?src='+info.url" width="100%" height="100%" style="border: none;">
                      <object class="no-preview" v-html="info.image"></object>
                  </iframe>
              </template> -->

              <template v-else-if="info.type == 'pdf'">
                <object :data="info.url" type="application/pdf" width="100%" height="100%">
                  <iframe :src="info.url" width="100%" height="100%" style="border: none;">
                    <object class="no-preview" v-html="info.image">

                    </object>
                  </iframe>
                </object>
              </template>

              <template v-else>
                <object class="no-preview" v-html="info.image">

                </object>
              </template>


            </div>
            <div class="w-2/5 bg-30 box-info flex flex-wrap">

              <div class="info-data w-full">
                <div class="info mx-4 my-3 flex flex-wrap items-center">
                  <span class="title bg-50 px-1 py-1 rounded-l">{{ __('Name') }}:</span>
                  <span class="value bg-white px-1 py-1 rounded-r" v-if="!editingName">{{ info.name }}
                                </span>

                  <template v-if="buttons.rename_file">

                    <svg v-if="!editingName" @click="editName" class="ml-1 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="12" height="12"><path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/></svg>

                    <template v-if="editingName">

                      <input type="text"  v-bind:ref="'inputName'" :style="{ 'width': nameWidth + 'px' }" v-model="nameNoExtension" class=" value px-1 py-1 rounded-r">

                      <svg @click="rename" xmlns="http://www.w3.org/2000/svg" class="ml-1 cursor-pointer text-success fill-current" viewBox="0 0 20 20" width="12" height="12"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>

                      <svg @click="editingName = !editingName" xmlns="http://www.w3.org/2000/svg" class="ml-1 cursor-pointer" viewBox="0 0 20 20" width="12" height="12"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/></svg>

                    </template>
                  </template>



                </div>

                <div class="info mx-4 my-3 flex flex-wrap" v-if="info.mime">
                  <span class="title bg-50 px-1 py-1 rounded-l">{{ __('Mime Type') }}:</span>
                  <span class="value bg-white px-1 py-1 rounded-r">{{ info.mime }}</span>
                </div>

                <div class="info mx-4 my-3 flex flex-wrap" v-if="info.date">
                  <span class="title bg-50 px-1 py-1 rounded-l">{{ __('Last Modification') }}:</span>
                  <span class="value bg-white px-1 py-1 rounded-r">{{ info.date }}</span>
                </div>

                <div class="info mx-4 my-3 flex flex-wrap" v-if="info.size">
                  <span class="title bg-50 px-1 py-1 rounded-l">{{ __('Size') }}:</span>
                  <span class="value bg-white px-1 py-1 rounded-r">{{ info.size }}</span>
                </div>

                <div class="info mx-4 my-3 flex flex-wrap" v-if="info.dimensions">
                  <span class="title bg-50 px-1 py-1 rounded-l">{{ __('Dimensions') }}:</span>
                  <span class="value bg-white px-1 py-1 rounded-r">{{ info.dimensions }}</span>
                </div>

                <div class="info mx-4 mt-6" v-if="info.url">
                  <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-value">
                    {{ __('Url') }}
                  </label>

                  <div class="flex flex-wrap items-stretch w-full mb-4 relative">
                    <input type="text" class="flex-shrink flex-grow flex-auto text-xs leading-normal w-px flex-1 border border-70 rounded rounded-r-none px-1 relative" :value="info.url" disabled>
                    <div class="flex -mr-px">
                      <button class="copy flex items-center leading-normal bg-50 rounded rounded-l-none border border-l-0 border-70 px-3 whitespace-no-wrap text-grey-dark text-xs" v-copy="info.url" v-copy:callback="onCopy">{{ __('Copy') }}</button>
                    </div>
                  </div>
                </div>

                <div class="info-actions mx-4 w-full justify-end">
                  <div class="">
                    <a
                        v-if="buttons.download_file"
                        class="bg-50 py-1 rounded-l text-xs text-grey-500 h-9 px-2 text-90 no-underline"
                        :href="`/nova-r64-api/actions/download-file?file=${this.info.path}`"
                        target="_blank"
                    >
                      Download
                    </a>
                  </div>

                </div>
              </div>

              <div class="info-actions w-full flex flex-wrap self-end justify-end">
                <!-- <button type="button" data-testid="cancel-button" @click.prevent="removeFilePopup" class="btn text-danger text-sm font-normal h-9 px-3 mr-3 btn-link">{{ __('Remove file') }}</button> -->
                <div :class="{ 'm-3': popup }">
                  <confirmation-button
                      v-if="buttons.delete_file"
                      :messages="messagesRemove"
                      :css="'btn text-danger text-sm font-normal h-9 px-3 mr-3 btn-link'"
                      v-on:confirmation-success="removeFilePopup()"></confirmation-button>


                  <template v-if="popup">
                    <button @click="selectFile" class="btn btn-default btn-primary">
                      {{ __('Select file') }}
                    </button>
                  </template>
                </div>

              </div>

            </div>

          </div>


        </div>
      </modal>
    </portal>
    <confirm-modal-delete ref="confirmDelete" />
  </div>
</template>

<script>
import api from '../api';
import ImageInfo from '../modules/Image';
import ConfirmationButton from './ConfirmationButton';
import TreeView from './TreeView';
import { copy } from 'v-copy';
import VuePlyr from 'vue-plyr'
import 'vue-plyr/dist/vue-plyr.css'
import { Codemirror } from 'vue-codemirror';
import ConfirmModalDelete from './ConfirmModalDelete';
//themes
import 'codemirror/lib/codemirror.css'
import 'codemirror/theme/dracula.css';

//modes
import 'codemirror/mode/markdown/markdown';
import 'codemirror/mode/javascript/javascript';
import 'codemirror/mode/php/php';
import 'codemirror/mode/sql/sql';
import 'codemirror/mode/ruby/ruby';
import 'codemirror/mode/shell/shell';
import 'codemirror/mode/sass/sass';
import 'codemirror/mode/yaml/yaml';
import 'codemirror/mode/yaml-frontmatter/yaml-frontmatter';
import 'codemirror/mode/nginx/nginx';
import 'codemirror/mode/xml/xml';
import 'codemirror/mode/vue/vue';
import 'codemirror/mode/dockerfile/dockerfile';
import 'codemirror/keymap/vim';

export default {
  props: {
    active: {
      type: Boolean,
      default: false,
      required: true,
    },
    info: {
      type: Object,
      default: function() {
        return { name: '' };
      },
      required: true,
    },
    popup: {
      type: Boolean,
      default: false,
      required: false,
    },
    buttons: {
      default: () => [],
      required: true,
    },
  },

  components: {
    ConfirmModalDelete: ConfirmModalDelete,
    ImageInfo: ImageInfo,
    ConfirmationButton: ConfirmationButton,
    codemirror: codemirror,
    TreeView: TreeView,
  },

  directives: {
    copy,
  },

  data: () => ({
    loaded: false,
    currentInfo: {},
    messagesRemove: ['Remove File', 'Are you sure', 'Removing...'],
    cssType: ' py-custom',
    codeLoaded: false,
    zipLoaded: false,
    cmOptions: {
      tabSize: 2,
      theme: 'dracula',
      lineNumbers: true,
      line: true,
      readOnly: true,
    },
    editingName: false,
    nameNoExtension: null,
    nameWidth: null,
    inputElement: null,
    correctName: null,
  }),

  methods: {
    closePreview() {
      this.correctName = null;
      this.editingName = false;
      this.$emit('closePreview', true);
    },

    onCopy() {
      this.$toasted.show(this.__('Text copied to clipboard'), { type: 'success' });
    },

    removeFilePopup() {
      this.closePreview();
      this.$refs.confirmDelete.openModal(this.info.type, this.info.path);
    },

    rename() {
      this.correctName = this.nameNoExtension + '.' + this.info.ext;

      return api.renameFile(this.info.path, this.correctName).then(result => {
        if (result.success == true) {
          this.editingName = false;
          this.$toasted.show(this.__('File renamed successfully'), { type: 'success' });
          this.$emit('rename', result.data);
          this.$emit('refresh');
        } else {
          this.$toasted.show(
              this.__('Error renaming the file. Please check permissions'),
              { type: 'error' }
          );
        }
      });
    },

    editName() {
      this.editingName = true;
    },

    selectFile() {
      this.closePreview();
      this.$emit('selectFile', this.info);
    },

    handleClose() {
      this.closePreview();
    },
  },

  mounted() {
    this.loaded = false;
    let $this = this;
    let confirmDelete = setInterval(function () {
      if($this.$refs.confirmDelete.password.length > 0 && $this.$refs.confirmDelete.isDeleted == true){
        $this.$emit('refresh');
        confirmDelete = null;
      }
    },1000);
    this.$nextTick(function() {
      this.messagesRemove = [
        this.__('Remove File'),
        this.__('Are you sure?'),
        this.__('Removing...'),
      ];
    });
  },

  computed: {
    playerOptions() {
      if (this.info) {
        return {
          video: {
            url: this.info.name,
          },
          autoplay: false,
          contextmenu: [
            {
              text: 'GitHub',
              link: 'https://github.com/MoePlayer/vue-dplayer',
            },
          ],
        };
      }
      return {};
    },
  },

  watch: {
    'info.type': function(type) {
      if (type == 'audio') {
        this.$nextTick(function() {
          setTimeout(() => {
            this.cssType = ' py-custom items-center';
            new VuePlyr(this.$refs.audio);
          });
        });
      }

      if (type == 'video') {
        this.$nextTick(function() {
          setTimeout(() => {
            // this.cssType = 'items-center';
            new VuePlyr(this.$refs.video);
          });
        });
      }

      if (type == 'text') {
        this.$nextTick(function() {
          this.cssType = '';
          this.cmOptions.mode = this.info.mime;
          this.codeLoaded = true;
        });
      }

      if (type == 'pdf') {
        this.$nextTick(function() {
          this.cssType = 'mh400';
        });
      }

      if (type == 'zip') {
        this.$nextTick(function() {
          this.info.source = JSON.parse(this.info.source);
          this.zipLoaded = true;
        });
      }

      this.cssType = '';
      this.codeLoaded = false;
      this.zipLoaded = false;
    },

    'info.name': function(name) {
      if (name) {
        this.nameNoExtension = name
            .split('.')
            .slice(0, -1)
            .join('.');
      }
    },

    nameNoExtension: function(name) {
      if (name) {
        this.nameWidth = (name.length + 1) * 7;
      }
    },
  },
};
</script>

<style scoped lang="scss">
input {
  box-sizing: border-box !important;
}

.buttons-actions {
  padding-left: 1rem;
  padding-right: 1rem;
  border-left: 1px solid rgb(221, 221, 221);
  // border-bottom: 1px solid rgb(221, 221, 221);
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.box-preview {
  max-height: 500px;
}

.py-custom {
  padding: 2rem 0;
}

.info {
  .title,
  .value {
    font-size: 0.75rem;
  }

  .value {
    //-moz-user-select: text;
    -khtml-user-select: text;
    -webkit-user-select: text;
    -ms-user-select: text;
    user-select: text;
  }

  .copy {
    &:active,
    &:focus {
      outline: 0;
    }
  }
}

.vue-codemirror {
  width: 100%;
}

.mh400 {
  min-height: 400px;
}
</style>

<style>
.no-preview > .svg-mime {
  width: 150px;
  height: 100%;
}
.CodeMirror {
  height: 400px;
}
</style>
