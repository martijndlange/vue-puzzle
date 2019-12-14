<template>
  <div
    class="cell"
    :class="{
      clue: isClue,
      arrow: hasArrow,
      highlighted: isHighlighted,
      focus: hasFocus,
      'left-border': x === 1,
      'top-border': y === 1,
      'arrow--left-right': hasArrowDirection('left', 'right'),
      'arrow--top-bottom': hasArrowDirection('top', 'bottom'),
      'arrow--left-bottom': hasArrowDirection('left', 'bottom'),
      'arrow--bottom-right': hasArrowDirection('bottom', 'right'),
      'arrow--top-right': hasArrowDirection('top', 'right')
    }"
    :style="{width: size + 'px', height: size + 'px', top: top + 'px', left: left + 'px'}"
    @click.capture="handleFocus()"
  >
    <p
      v-if="isClue"
      class="cell-content clue"
    >
      {{ clue }}
    </p>
    <p
      v-if="!isClue"
      class="cell-content solution"
    >
      {{ value }}
    </p>

    <div
      v-if="isKeyCell"
      class="key"
    >
      {{ keyindex }}
    </div>

    <div
      class="hint"
    >
      {{ solution }}
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      value: {
        type: String,
        required: false,
        default: '',
      },
      solution: {
        type: String,
        required: false,
        default: '',
      },
      keyindex: {
        type: Number,
        required: false,
        default: 0,
      },
      clue: {
        type: String,
        required: false,
        default: '',
      },
      size: {
        type: Number,
        required: true,
        default: 0,
      },
      x: {
        type: Number,
        required: true,
        default: 0,
      },
      y: {
        type: Number,
        required: true,
        default: 0,
      },
      type: {
        type: String,
        required: false,
        default: '',
      },
      isHighlighted: {
        type: Boolean,
        required: false,
        default: false,
      },
      hasFocus: {
        type: Boolean,
        required: false,
        default: false,
      },
      arrows: {
        type: Object,
        required: false,
        default: function () {
          return {};
        }
      },
    },
    computed: {
      left: function () {
        return (this.x - 1) * this.size;
      },
      top: function () {
        return (this.y - 1) * this.size;
      },
      isClue: function () {
        return this.clue !== '';
      },
      isKeyCell: function () {
        return this.keyindex !== 0;
      },
      hasArrow: function () {
        return (this.arrows.from);
      },
    },
    methods: {
      hasArrowDirection: function (from, to) {
        return this.hasArrow && this.arrows.from === from && this.arrows.to === to;
      },
      handleFocus: function () {
        document.getElementById('feedback-input').focus();
        if (!this.isClue) {
          this.$emit('cell-focussed', this.x, this.y);
        }
      },
    },
  }
</script>
<style lang="scss">
  .cell {
    position: absolute;
    background-color: #FFF;
    align-items: center;
    display: flex;
    justify-content: center;
    padding: 2px;
    box-sizing: border-box;
    cursor: pointer;
    border-right: solid 1px #444;
    border-bottom: solid 1px #444;

    &.left-border {
      border-left: solid 1px #444;
    }

    &.top-border {
      border-top: solid 1px #444;
    }

    &.highlighted {
      background-image: linear-gradient(#ffffff 40%, #ddd 100%);
    }

    &.focus {
      p.solution {
        color: #fff !important;
      }

      &.arrow:before {
        color: #fff !important;
      }

      background-color: red;
      background-image: none;
    }

    &.arrow:before {
      display: block;
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0%;
      left: 0%;
      background-position: center top;
      background-repeat: no-repeat;
      background-size: 101%;
    }

    &.arrow--top-bottom:before, &.arrow--left-right:before {
      background-image: url("data:image/svg+xml;charset=utf8,%3C?xml version='1.0' encoding='utf-8'?%3E%3C!-- Generator: Adobe Illustrator 22.0.1, SVG Export Plug-In . SVG Version: 6.00 Build 0) --%3E%3Csvg version='1.1' id='Laag_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 200 200' style='enable-background:new 0 0 200 200;' xml:space='preserve'%3E%3Cpolygon points='75,0 125,0 100,25 '/%3E%3C/svg%3E");
    }

    &.arrow--left-right:before {
      -webkit-transform: rotate(-90deg);
      transform: rotate(-90deg);
    }

    &.arrow--left-bottom:before, &.arrow--bottom-right:before, &.arrow--top-right:before {
      background-image: url("data:image/svg+xml;charset=utf8,%3C?xml version='1.0' encoding='utf-8'?%3E%3C!-- Generator: Adobe Illustrator 22.0.1, SVG Export Plug-In . SVG Version: 6.00 Build 0) --%3E%3Csvg version='1.1' id='Laag_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 200 200' style='enable-background:new 0 0 200 200;' xml:space='preserve'%3E%3Cpolygon points='75,6 125,6 100,31 '/%3E%3Crect y='6' width='100' height='2'/%3E%3C/svg%3E");
    }

    &.arrow--bottom-right:before {
      -webkit-transform: rotate(-90deg);
      transform: rotate(-90deg);
    }

    &.arrow--top-right:before {
      -moz-transform: scaleX(-1);
      -o-transform: scaleX(-1);
      -webkit-transform: scaleX(-1) rotate(90deg);
      transform: scaleX(-1) rotate(90deg);
      filter: FlipH;
      -ms-filter: "FlipH";
    }

    &.clue {
      background-color: #FFFEE6;
      cursor: default;
    }

    div.key {
      position: absolute;
      bottom: 0;
      right: 2px;
      text-align: right;
    }

    div.hint {
      position: absolute;
      bottom: 0;
      left: 2px;
      text-align: left;
      color: #aaa;
      font-size: 10px
    }

    p.clue {
      font-size: 12px;
      text-align: center;
      color: #000;
    }

    p.solution {
      font-size: 20px;
      color: #aaa;
    }
  }
</style>
