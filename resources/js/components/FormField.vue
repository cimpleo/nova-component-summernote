<template>
  <default-field :field="field" :errors="errors" :full-width-content="true">
    <template slot="field">
      <div @mouseleave="deactivateCodeView">
        <div :class="[errorClasses, errorClasses.length ? 'border' : '']">
          <textarea :id="fieldId" :dusk="field.attribute" v-model="content"></textarea>
          <codemirror v-model="stylesheet" :options="cmOptions"/>
        </div>

        <p v-if="hasError" class="my-2 text-danger">
          {{ firstError }}
        </p>
      </div>
    </template>
  </default-field>
</template>

<script>
  import { FormField, HandlesValidationErrors } from 'laravel-nova';
  import { codemirror } from 'vue-codemirror';
  import 'codemirror/mode/css/css';
  import 'codemirror/lib/codemirror.css';
  import 'codemirror/theme/monokai.css';

  import bootstrap from 'bootstrap';
  import summernote from 'summernote';
  import 'summernote/dist/summernote.css';

  export default {
    mixins: [FormField, HandlesValidationErrors],

    components: {
      codemirror
    },

    props: ['resourceName', 'resourceId', 'field'],

    data() {
      return {
        cmOptions: {
          mode: 'text/css',
          tabSize: 2,
          theme: 'monokai',
          lineNumbers: true,
          line: true,
        },
        content: '',
        stylesheet: '',
      };
    },

    computed: {
      fieldId() {
        return `${this.field.attribute}-${this._uid}`;
      }
    },

    methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue() {
        this.value = this.field.value || '';
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill(formData) {
        this.deactivateCodeView();
        formData.append(this.field.attribute, this.value || '');
      },

      /**
       * Update the field's internal value.
       */
      setValue() {
        let vm = this;
        this.content = $(vm.$el).find('.note-editable').html();
      },

      handleChange(value) {
        this.value = value
      },

      combineContents() {
        this.value = `<style type="text/css">${this.stylesheet}</style>${this.content}`;
      },

      deactivateCodeView() {
        // Workaround for Summernote CodeView bug (https://github.com/summernote/summernote/issues/1309)
        const vm = this;
        const summernoteNode = $(vm.$el).find(`#${vm.fieldId}`);
        const isCodeViewActive = summernoteNode.summernote('codeview.isActivated') !== false;

        if (isCodeViewActive) {
          summernoteNode.summernote('codeview.deactivate');
        }
      }
    },

    watch: {
      content() {
        this.combineContents();
      },
      stylesheet() {
        this.combineContents();
      }
    },

    mounted() {
      let vm = this;

      const regex = /(?:(?:<style(?:\s[\w\=\"\/]+)*?)>(?<stylesheet>(?:.|\n)*)?(?:<\/style>))?(?<content>(?:.|\n)*)/gm;
      const matches = regex.exec(this.value);

      this.content = matches.groups.content.trim() || '';
      this.stylesheet = matches.groups.stylesheet.trim() || '';

      $(document).ready(() => {
        $(vm.$el)
          .find(`#${vm.fieldId}`)
          .summernote({
            callbacks: {
              onKeyup: function (e) {
                vm.setValue();
              },
              onPaste: function (e) {
                vm.setValue();
              },
              onBlur: function (e) {
                vm.deactivateCodeView();
                vm.setValue();
              }
            },
            codemirror: {
              theme: 'monokai',
              width : '100px',
            }
          });
      });
    },
  };
</script>
