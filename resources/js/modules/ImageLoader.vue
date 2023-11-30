<template>
  <transition name="fade">
    <template v-if="view == 'grid'">

      <div @click="clickStrategy"
           ref="card"
           :loading="loading"
           class="card relative flex flex-wrap justify-center border border-lg border-50 overflow-hidden px-0 py-0 cursor-pointer"
      >
        <template v-if="loading">
          <div class="rounded-lg flex items-center justify-center absolute pin z-50 mx-auto w-full">
            <loader class="text-60" />
          </div>
        </template>

        <div v-if="file.mime != 'image'" v-html="file.thumb" class="mime-icon flex items-center justify-center  h-5/6">

        </div>

        <div  v-if="file.mime == 'image'" ref="image" class="image-block block w-full h-5/6">

        </div>

        <div class="actions-grid absolute pr-2 pt-2 pb-1 pl-2 dark:bg-gray-700"
             :class="{ 'hidden': !multiSelecting , '' : shouldShowHover}"
        >
          <div v-if="multiSelecting">
            <input :checked="selected" type="checkbox">
          </div>

          <div v-else class="flex flex-wrap text-70" >
            <div class="cursor-pointer" :class="{ ' mr-2' : renamePermission }" v-if="deletePermission" @click.prevent="deleteFile($event)">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
            </div>
            <div class="cursor-pointer" v-if="renamePermission" @click.prevent="renameFile($event)">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
            </div>
          </div>
        </div>

        <div class="missing p-8" v-if="missing">
          <p class="text-center leading-normal">
            <a :href="file.name" class="text-primary dim" target="_blank">{{__('This image')}}</a> {{__('could not be found.')}}
          </p>
        </div>

        <div class="h-1/6 w-full text-center text-xs  border-t border-30 bg-gray-500 text-white flex items-center justify-center">
          {{ truncateStr(file.name,25) }}
        </div>
      </div>
    </template>

    <template v-else-if="view == 'list'">

      <tr @click="clickStrategy" :loading="loading" v-bind:key="file.id" class="cursor-pointer">
        <td v-if="multiSelecting" class="w-8">
          <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="block">
            <g v-if="selected"><rect width="20" height="20" rx="4" fill="var(--primary)"></rect> <path fill="#FFF" d="M7.7 9.3c-.23477048-.3130273-.63054226-.46037132-1.01285927-.37708287-.38231702.08328846-.68093514.38190658-.7642236.7642236C5.83962868 10.0694577 5.9869727 10.4652295 6.3 10.7l2 2c.38884351.3811429 1.01115649.3811429 1.4 0l4-4c.3130273-.23477048.4603713-.63054226.3770829-1.01285927-.0832885-.38231702-.3819066-.68093514-.7642236-.7642236C12.9305423 6.83962868 12.5347705 6.9869727 12.3 7.3L9 10.58l-1.3-1.3v.02z"></path></g>
            <g v-else><rect width="20" height="20" fill="#FFF" rx="4"></rect> <rect width="19" height="19" fill="none" x=".5" y=".5" stroke="#CCD4DB" rx="4"></rect></g>
          </svg>
        </td>
        <td>
          <template v-if="loading">
            <div class="rounded-lg flex items-center justify-center absolute pin z-50 mx-auto w-full">
              <loader class="text-60" />
            </div>
          </template>

          <div class="w-full h-full flex justify-center items-center">
            <div v-if="file.mime != 'image'" v-html="file.thumb" class="mime-icon flex items-center justify-center w-1/3 h-full">

            </div>

            <div  v-if="file.mime == 'image'" ref="image" class="image-block flex justify-center items-center w-full h-full">

            </div>
          </div>

          <div class="w-full missing p-8" v-if="missing">
            <p class="text-center leading-normal">
              <a :href="file.name" class="text-primary dim" target="_blank">{{__('This image')}}</a> {{__('could not be found.')}}
            </p>
          </div>
        </td>

        <td class="px-2">
          {{ file.name }}
        </td>

        <td>
          {{ file.size_human }}
        </td>

        <td>
          {{ file.date }}
        </td>

        <td>
          <div class="flex flex-nowrap text-70">
            <div class="cursor-pointer mr-2" v-if="deletePermission" @click.prevent="deleteFile($event)">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
            </div>
            <div class="cursor-pointer mr-2" v-if="renamePermission" @click.prevent="renameFile($event)">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
            </div>
            <div class="cursor-pointer mr-2" v-if="movePermission" @click.prevent="moveFile($event)">
              <svg width="20" height="auto" viewBox="5 10 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools --> <title>
                {{ file.name }}</title> <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="ic_fluent_folder_move_48_regular" fill="#b3b9bf" fill-rule="nonzero"> <path d="M17.0606622,9 C17.8933043,9 18.7000032,9.27703406 19.3552116,9.78392956 L19.5300545,9.92783739 L22.116207,12.1907209 C22.306094,12.356872 22.5408581,12.4608817 22.7890575,12.4909364 L22.9393378,12.5 L40.25,12.5 C42.2542592,12.5 43.8912737,14.0723611 43.994802,16.0508414 L44,16.25 L44.0009146,24.0563927 C43.2471782,23.3816422 42.4076405,22.8007736 41.500684,22.3321695 L41.5,16.25 C41.5,15.6027913 41.0081253,15.0704661 40.3778052,15.0064536 L40.25,15 L22.8474156,14.9988741 L20.7205012,17.6147223 C20.0558881,18.4327077 19.0802671,18.9305178 18.0350306,18.993257 L17.8100737,19 L6.5,18.999 L6.5,35.25 C6.5,35.8972087 6.99187466,36.4295339 7.62219476,36.4935464 L7.75,36.5 L24.5185779,36.5004632 C24.786765,37.3812299 25.1535218,38.2190449 25.6059991,39.0010592 L7.75,39 C5.74574083,39 4.10872626,37.4276389 4.00519801,35.4491586 L4,35.25 L4,12.75 C4,10.7457408 5.57236105,9.10872626 7.55084143,9.00519801 L7.75,9 L17.0606622,9 Z M17.0606622,11.5 L7.75,11.5 C7.10279131,11.5 6.5704661,11.9918747 6.50645361,12.6221948 L6.5,12.75 L6.5,16.499 L17.8100737,16.5 C18.1394331,16.5 18.4534488,16.3701335 18.6858203,16.1419575 L18.7802162,16.0382408 L20.415,14.025 L17.883793,11.8092791 C17.693906,11.643128 17.4591419,11.5391183 17.2109425,11.5090636 L17.0606622,11.5 Z M36,23 C41.5228475,23 46,27.4771525 46,33 C46,38.5228475 41.5228475,43 36,43 C30.4771525,43 26,38.5228475 26,33 C26,27.4771525 30.4771525,23 36,23 Z M35.9990966,27.634211 L35.8871006,27.7097046 L35.7928932,27.7928932 L35.7097046,27.8871006 C35.4300985,28.2467008 35.4300985,28.7532992 35.7097046,29.1128994 L35.7928932,29.2071068 L38.585,32 L31,32 L30.8833789,32.0067277 C30.424297,32.0600494 30.0600494,32.424297 30.0067277,32.8833789 L30,33 L30.0067277,33.1166211 C30.0600494,33.575703 30.424297,33.9399506 30.8833789,33.9932723 L31,34 L38.585,34 L35.7928932,36.7928932 L35.7097046,36.8871006 C35.4046797,37.2793918 35.4324093,37.8466228 35.7928932,38.2071068 C36.1533772,38.5675907 36.7206082,38.5953203 37.1128994,38.2902954 L37.2071068,38.2071068 L41.7071068,33.7071068 L41.7577946,33.6525476 L41.8296331,33.5585106 L41.8751242,33.484277 L41.9063266,33.4232215 L41.9502619,33.3121425 L41.9725773,33.2335141 L41.9931674,33.1174626 L42,33 L41.997,32.924 L41.9798348,32.7992059 L41.9504533,32.6882636 L41.9287745,32.628664 L41.8753288,32.5159379 L41.8296215,32.441477 L41.787214,32.3832499 L41.7485042,32.336853 L41.7071068,32.2928932 L37.2071068,27.7928932 L37.1128994,27.7097046 C36.7892592,27.4580591 36.3465505,27.4328945 35.9990966,27.634211 Z" id="🎨-Color"> </path> </g> </g> </g></svg>
            </div>
          </div>
        </td>
      </tr>
    </template>
    <template v-else>
      -
    </template>

  </transition>
</template>

<script>
import findIndex from 'lodash/findIndex';
import axios from 'axios'
export default {
  components: {
    //
  },

  props: {
    file: {
      type: Object,
      default: function() {
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
  }),

  computed: {
    fileName() {
      let text = this.file.name;
      return text.slice(0, 25) + (25 < text.length ? '...' : '');
    },
    selected() {
      return (
        findIndex(this.selectedFiles, { type: this.file.type, path: this.file.path }) >= 0
      );
    },

    shouldShowHover() {
      if (this.deletePermission) {
        return true;
      }

      if (this.renamePermission) {
        return true;
      }

      if (this.multiSelecting) {
        return true;
      }

      return false;
    },
  },

  mounted() {
    if (this.file.mime == 'image') {
      axios.get(this.file.thumb, {
        responseType: 'blob',
      })
        .then(({ headers, data }) => {
          const blob = new Blob([data], { type: headers['content-type'] });
          let imageDiv = document.createElement('img');
          let imageBlog = null;

          imageBlog = window.URL.createObjectURL(blob);
          imageDiv.src = imageBlog;
          imageDiv.className = this.getClassContainer();
          imageDiv.draggable = false;
          this.$refs.image.appendChild(imageDiv);
          this.loading = false;
        })
        .catch(error => {
          if (error && this.$refs.image) {
            //defaulImage
            let imageDiv = document.createElement('img');
            imageDiv.src = this.file.thumb;
            imageDiv.className = this.getClassContainer();
            imageDiv.draggable = false;
            this.$refs.image.appendChild(imageDiv);
            this.loading = false;
          }
        });
    } else {
      this.loading = false;
    }
  },
  methods: {
    clickStrategy() {
      return this.multiSelecting ? this.select() : this.showInfo();
    },

    select() {
      this.$emit('select', {
        type: this.file.type,
        path: this.file.path,
      });
    },

    showInfo() {
      this.$emit('showInfo', this.file);
    },

    getClassContainer() {
      if (this.view == 'list') {
        return 'w-16 h-auto object-cover';
      }

      return 'block w-full h-full bg-center bg-cover h-2/3';
    },

    deleteFile(e) {
      this.stopDefaultActions(e);
      this.$emit('delete', 'file', this.file.path);
    },

    renameFile(e) {
      this.stopDefaultActions(e);
      this.$emit('rename', 'file', this.file.path);
    },

    moveFile(e) {
      this.stopDefaultActions(e);
      this.$emit('move', 'file', this.file.path);
    },

    stopDefaultActions(e) {
      e.preventDefault();
      e.stopPropagation();
    },
    truncateStr: function(text, stop) {
      return text.slice(0, stop) + (stop < text.length ? '...' : '');
    },
  },
  watch: {
    'file.loading': function(value) {
      if (value == true) {
        this.loading = true;
      } else {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped  lang="scss">
.card {
  padding: 0 !important;

  &:hover {
    > .image-block,
    > .mime-icon {
      opacity: 0.5;
    }

    > .actions-grid {
      display: flex;
    }
  }
}

.h-5\/6 {
  height: 83.33333%;
}

.h-1\/6 {
  height: 16.66667%;
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
}
</style>

<style>
.svg-mime {
  width: 80px;
  height: 75%;
  fill: #62676d;
}
</style>
