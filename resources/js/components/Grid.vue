<template>
  <div class="container">
    <div class="row">
      <div
        id="puzzle-container"
        class="puzzle-container"
        :style="{width: `${containerWidth}px`, height: `${containerHeight+1}px` }"
      >
        <cell
          v-for="(cell, i) in cells"
          :key="cell+i"
          :x="cell.x"
          :y="cell.y"
          :clue="cell.clue"
          :solution="cell.solution"
          :keyindex="cell.keyindex"
          :arrows="cell.arrow"
          :size="cellSize"
          :is-highlighted="cellIsHighlighted(cell.x, cell.y)"
          :has-focus="cellHasFocus(cell.x, cell.y)"
          :value="cellValue(cell.x, cell.y)"
          @cell-focussed="handleCellFocus"
        />
      </div>
    </div>
    <input
      id="feedback-input"
      type="text"
      :value="feedbackInputValue"
    >
  </div>
</template>

<script>
  export default {
    props: {
      width: {
        type: Number,
        required: true,
        default: 0,
      },
      height: {
        type: Number,
        required: true,
        default: 0,
      },
      keyword: {
        type: String,
        required: true,
        default: '',
      },
      solution: {
        type: Array,
        required: false,
        default: function () {
          return []
        }
      },
      cells: {
        type: Array,
        required: false,
        default: function () {
          return []
        }
      },
      words: {
        type: Array,
        required: false,
        default: function () {
          return []
        }
      },
      clues: {
        type: Object,
        required: false,
        default: function () {
          return {};
        }
      },
    },
    data() {
      return {
        feedbackInputValue: '',
        containerWidth: 1000,
        focusX: 0,
        focusY: 0,
        highlightedWord: {},
        highlightedCells: [],
        keywordCells: [],
      }
    },
    computed: {
      cellSize: function () {
        return Math.floor(this.containerWidth / this.width);
      },
      containerHeight: function () {
        return this.cellSize * this.height;
      }
    },
    created: function () {
      //
    },
    mounted() {
      // we have to add a seperate listener for input feedback, becaouse android doesn't response to key events...
      // every press results in same keycode (229)
      document.getElementById('feedback-input').addEventListener('input', this.handleInput);
      window.document.addEventListener('keyup', this.handleKeyUp);
      window.document.addEventListener('click', this.handleClick);

      this.$store.commit('setKeyword', this.keyword);
      this.cells.forEach((cell) => {
        if (cell.keyindex) {
          if (!this.keywordCells[cell.x]) {
            this.keywordCells[cell.x] = [];
          }
          this.keywordCells[cell.x][cell.y] = cell.keyindex;
        }
      });
      console.log('Grid mounted.');
    },
    methods: {
      setNextFieldForDirection(direction) {
        let pos = direction === 'up' || direction === 'left' ? 0 : 1000;
        let focusX = this.focusX;
        let focusY = this.focusY;
        let changed = false;

          this.cells.forEach(function (cell) {
            if (
              (direction === 'up' && cell.x === focusX && cell.y < focusY && cell.y > pos && cell.solution) ||
              (direction === 'down' && cell.x === focusX && cell.y > focusY && cell.y < pos && cell.solution)
            ) {
              pos = cell.y;
              changed = true;
            }
            if (
              (direction === 'left' && cell.y === focusY && cell.x < focusX && cell.x > pos && cell.solution) ||
              (direction === 'right' && cell.y === focusY && cell.x > focusX && cell.x < pos && cell.solution)

            ) {
              pos = cell.x;
              changed = true;
            }
          });
          if (changed && (direction === 'up' || direction === 'down')) {
            this.focusY = pos;
          }
          if (changed && (direction === 'left' || direction === 'right')) {
            this.focusX = pos;
          }
      },
      cellValue: function(xCell, yCell) {
        if (this.solution[xCell] && this.solution[xCell][yCell]) {
          return this.solution[xCell][yCell];
        }
        return '';
      },
      cellHasFocus: function(xCell, yCell) {
        const x = parseInt(xCell, 10);
        const y = parseInt(yCell, 10);
        return x === this.focusX && y === this.focusY;
      },
      cellIsHighlighted: function(xCell, yCell) {
        if (this.highlightedCells.length === 0) {
          return false;
        }
        const x = parseInt(xCell, 10);
        const y = parseInt(yCell, 10);
        let found = false;
        this.highlightedCells.forEach(function (cell) {
          const arrXY = cell.split('-');
          const xCheck = parseInt(arrXY[0], 10);
          const yCheck = parseInt(arrXY[1], 10);
          if (x === xCheck && y === yCheck) {
            found = true;
          }
        });
        return found;
      },
      /**
       * make sure the hidden input field has the correct top position
       * otherwise on mobile the screen starts to jump when switching cells
      */
      setScroll() {
        const feedbackInput = document.getElementById('feedback-input');
        const height = (parseInt(this.focusY) * (parseInt(this.cellSize)) );
        feedbackInput.style.setProperty('top', `${height}px`);
        window.scrollTo(0, height - 150);
      },
      storeKeyCell(x, y, char) {
        if (this.keywordCells[x] && this.keywordCells[x][y]) {
          this.$store.commit('setKeywordCell', { 'position': (this.keywordCells[x][y])-1, 'char': char});
        }
      },
      handleClick(event) {
        // when clicking or focusing outside the puzzle, reset layout
        const c = event.target.className;
        if (c.indexOf('cell') === -1 && c.indexOf('cell-content') === -1) {
          this.focusX = 0;
          this.focusY = 0;
          this.highlightedWord = {};
          this.highlightedCells = [];
        }
      },
      handleCellFocus: function(x, y) {
        this.focusX = x;
        this.focusY = y;
        this.setScroll();
        this.highlightWord(x, y);
      },
      handleInput: function(event) {
        const dir = this.highlightedWord.x.indexOf('-') >= 0 ? 'x' : 'y';
        const arrFromTo = this.highlightedWord[dir].split('-');
        const wordTo = parseInt(arrFromTo[1], 10);
        const legalChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // let char = String.fromCharCode(event.keyCode);
        let char = event.target.value;
        char = char.toUpperCase();

        // input not legal char
        if (legalChars.indexOf(char) === -1) {
          return;
        }

        this.storeKeyCell(this.focusX, this.focusY, char);

        // add valid value to solution array (USING VUE SET METHOD!)
        if (!this.solution[this.focusX]) {
          this.$set(this.solution, this.focusX, []);
        }
        this.$set(this.solution[this.focusX], this.focusY, char);

        // move cursor
        if (dir === 'x' && this.focusX < wordTo) {
          this.focusX += 1;
        }
        if (dir === 'y' && this.focusY < wordTo) {
          this.focusY += 1;
        }

        this.feedbackInputValue = '';
      },
      handleKeyUp: function(event) {
        if (this.focusX === 0 && this.focusY === 0) {
          return;
        }

        this.setScroll();

        const dir = this.highlightedWord.x.indexOf('-') >= 0 ? 'x' : 'y';
        const arrFromTo = this.highlightedWord[dir].split('-');
        const wordFrom = parseInt(arrFromTo[0], 10);

        const keyDelete = event.keyCode === 46;
        const keyBackspace = event.keyCode === 8;
        const keyLeft = event.keyCode === 37;
        const keyUp = event.keyCode === 38;
        const keyRight = event.keyCode === 39;
        const keyDown = event.keyCode === 40;
        const keyReturn = event.keyCode === 13;

        if (keyDelete) {
          if (this.solution[this.focusX] && this.solution[this.focusX][this.focusY]) {
            this.$set(this.solution[this.focusX], this.focusY, '');
            this.storeKeyCell(this.focusX, this.focusY, '');
            return;
          }
        }

        if (keyBackspace) {
          this.$set(this.solution[this.focusX], this.focusY, '');
          this.storeKeyCell(this.focusX, this.focusY, '');
          if (dir === 'x' && this.focusX > wordFrom) {
            this.focusX -= 1;
          }
          if (dir === 'y' && this.focusY > wordFrom) {
            this.focusY -= 1;
          }
          return;
        }

        if (keyReturn) {
          this.highlightWord(this.focusX, this.focusY);
          return;
        }

        if (keyLeft || keyUp || keyRight || keyDown) {
          let navigation = keyUp ? 'up' : (keyRight ? 'right' : (keyDown ? 'down' : 'left'));
          let direction = keyLeft || keyRight ? 'x' : 'y';
          this.setNextFieldForDirection(navigation);
          this.highlightWord(this.focusX, this.focusY, direction, false, true);
        }
      },
      /**
       * Highlight a word the selected position belongs to
       *
       * @param x           X-position of focused field
       * @param y           Y-position of focused field
       * @param searchFor   Direction in which to start searching for matching word
       * @param searchOnce  Perform search once, skip other direction (for 2nd search)
       * @param keepWord    Stay in current word (for arrow navigation)
       */
      highlightWord(x, y, searchFor = 'x', searchOnce = false, keepWord = false) {
        let found = false;
        const inverseSearchFor = searchFor === 'x' ? 'y' : 'x';
        this.words.forEach((word) => {
          if (word[searchFor].indexOf("-") >= 0) {
            const searchPos = searchFor === 'x' ? x : y;
            const searchPosOtherDirection = searchFor === 'x' ? y : x;
            const arrFromTo  = word[searchFor].split('-');
            const searchFrom = parseInt(arrFromTo[0], 10);
            const searchTo   = parseInt(arrFromTo[1], 10);
            const wordPosOtherDirection = parseInt(word[inverseSearchFor], 10);
            if (
              wordPosOtherDirection === searchPosOtherDirection &&
              searchPos >= searchFrom &&
              searchPos <= searchTo
            ) {

              // if selected word is the same as the current highlighted word,
              // then check if there is a word available in the other direction for the xy position
              // this makes it possible to change direction on click or pressing return
              if (!keepWord && this.highlightedWord['id'] === word['id'] && searchOnce === false) {
                this.highlightWord(x, y, inverseSearchFor);
                return;
              }
              found = true;
              this.highlightedWord = word;
              this.highlightedCells = [];
              for (let i = searchFrom; i <= searchTo; i++) {
                // depending on the search direction, the range is horizontal or vertical
                if (searchFor === 'x') {
                  this.highlightedCells.push(`${i}-${searchPosOtherDirection}`);
                } else {
                  this.highlightedCells.push(`${searchPosOtherDirection}-${i}`);
                }
              }
            }
          }
        });
        // if no match is found, then try once again in other direction
        // boolean prevents infinite loop
        if (!found) {
          this.highlightWord(x, y, inverseSearchFor, true);
        }
      },
    }
  }
</script>
<style lang="scss">
  .puzzle-container {
    position: relative;
  }
  #feedback-input {
    position: absolute;
    left: 22px;
    top: -2px;
    width: 2px;
    height: 2px;
    border: none;
    opacity: 0;
  }
</style>
