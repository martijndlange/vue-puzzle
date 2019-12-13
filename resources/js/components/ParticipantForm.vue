<template>
  <div>
    <form action="">
      <div
        class="columns"
      >
        <div class="field column">
          <label
            class="label"
            for="naam"
          >
            Naam:
          </label>
          <div class="control">
            <input
              id="naam"
              :value="naam"
              class="input"
              name="naam"
              type="text"
            >
          </div>
        </div>
      </div>
      <div
        class="columns"
      >
        <div class="field column">
          <label
            class="label"
            for="email"
          >
            E-mailadres:
          </label>
          <div class="control">
            <input
              id="email"
              :value="email"
              class="input"
              name="email"
              type="text"
            >
          </div>
        </div>
      </div>

      <div class="field">
        <label
          class="label"
        >
          Oplossing:
        </label>
      </div>
     
      <div
        class="columns is-mobile"
      >
        <div
          v-for="(i) in 5"
          :key="i"
          class="column"
        >
          <input
            class="input key-cell"
            :value="keyForIndex(i)"
            type="text"
          >
        </div>
      </div>

      <input
        class="button is-success"
        type="button"
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
      solution: function () {
        return this.$store.state.solution;
      },
    },
    methods: {
      submitForm: function () {
        console.log('CLICK');
      },
      /*eslint-disable-next-line*/
      keyForIndex: function (index) {
        return this.$store.getters.keyForPosition(index - 1);
      }
    }
  }
</script>
<style lang="scss">
  .key-cell {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    padding-left: 0px;
    padding-right: 0px;
  }
</style>
