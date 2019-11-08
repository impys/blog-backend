<template>
  <default-field :field="field" :errors="errors">
    <template slot="field">
      <markdown :id="field.name" :class="errorClasses" :placeholder="field.name" :value="value"></markdown>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import Markdown from "./Markdown";

export default {
  mixins: [FormField, HandlesValidationErrors],

  components: {
    Markdown
  },

  props: ["resourceName", "resourceId", "field"],

  mounted() {
    console.log(this.field);
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || "";
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || "");
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value;
    }
  }
};
</script>
