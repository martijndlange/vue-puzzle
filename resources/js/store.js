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
    keyForPosition: state => pos =>  {
      return state.keywordcells[pos] ? state.keywordcells[pos] : '';
    },
    givenKeyword: state => {
      return state.keywordcells.join('');
    }
  }
});

export default store;