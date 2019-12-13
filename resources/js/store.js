import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    keyword: '',
    keywordcells: ['', '', '', '', '']
  },
  mutations: {
    setKeyword (state, keyword) {
      state.keyword = keyword;
    },
    setKeywordCell(state, {position, char}) {
      Vue.set(state.keywordcells, position, char);
    }
  },
  getters: {
    /*eslint-disable-next-line*/
    keyForPosition: state => pos =>  {
      return state.keywordcells[pos] ? state.keywordcells[pos] : '';
    },
  }
});

export default store;