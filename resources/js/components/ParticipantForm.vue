<template>
  <div>
    <form action="">
      <input
        type="hidden"
        name="keyword"
        :value="keyword"
      >
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
            readonly
          >
        </div>
      </div>

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
              type="email"
            >
          </div>
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
      keyword: function () {
        return this.$store.state.keyword;
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
