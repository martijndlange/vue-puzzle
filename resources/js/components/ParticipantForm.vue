<template>
  <div>
    <form action="">
      <div class="field">
        <label
          class="label"
          for="naam"
        >
          Naam:
        </label>
        <div class="control">
          <input
            id="naam"
            class="input"
            name="naam"
            type="text"
            :value="naam"
          >
        </div>
      </div>
      <div class="field">
        <label
          class="label"
          for="email"
        >
          E-mailadres:
        </label>
        <div class="control">
          <input
            id="email"
            class="input"
            name="email"
            type="text"
            :value="email"
          >
        </div>
      </div>
      <div class="field">
        <label
          class="label"
          for="oplossing"
        >
          Oplossing:
        </label>
        <div class="control">
          <input
            id="oplossing"
            class="input"
            name="email"
            type="text"
            :value="solution"
          >
        </div>
      </div>

      <input
        type="button"
        class="button is-success"
        value="versturen"
        @click="submitForm"
      >
    </form>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        naam: '',
        email: '',
      }
    },
    computed: {
        solution: function() {
            return this.$store.state.solution;
        },
    },
    methods: {
      submitForm: function () {
        console.log('CLICK');
      }
    }
  }
</script>
<style lang="scss">
</style>
