<template>
  <div
    :class="{
      'input-group': true,
      'datepick-container': true,
      calendar: isActive
    }"
  >
    <div class="pika-container">
      <div class="pika-custom" v-if="isActive">
        <div class="pika-event-btn today" @click="customDate('today', $event)">
          Today
        </div>
        <div
          class="pika-event-btn tomorrow"
          @click="customDate('tomorrow', $event)"
        >
          Tomorrow
        </div>
        <div
          class="pika-event-btn weekend"
          @click="customDate('weekend', $event)"
        >
          This Weekend
        </div>
      </div>
    </div>
    <h3>Start on</h3>
    <input
      disabled
      :value="inputValue"
      ref="input"
      aria-labelledby="datepicker"
      placeholder="YYYY-MM-DD"
      class="datepicker no-select"
      id="event-datepicker"
    />
    <div class="input-group-append no-select">
      <span class="input-group-text form-control">
        <img
          src="/app/themes/ucla/resources/assets/images/calendar.svg"
          style="display:inline-block;"
          class="svg calendar-icon"
          alt="calendar"
          width="16"
          height="16"
        />
        <div class="sr-only">Calendar</div>
        <chevron-icon></chevron-icon>
        <div class="sr-only">Dropdown</div>
      </span>
    </div>
  </div>
</template>

<script>
import Pikaday from "pikaday";
import moment from "moment/src/moment";
import Vue from "vue";
import Icon from "./Icon.vue";
import ChevronIcon from "./ChevronIcon.vue";

export default {
  data() {
    return {
      isActive: false,
      inputValue: "",
      picker: null,
      dateType: false
    };
  },
  components: {
    Icon,
    ChevronIcon
  },
  props: {
    value: { required: true },
    format: { default: "YYYY-MM-DD" },
    options: { default: {} }
  },
  methods: {
    customDate(date) {
      this.dateType = date;
      let rawDate = moment();
      let formattedDate = rawDate.format("MMM D, YYYY");

      if (date == "today") {
        rawDate = moment();
        formattedDate = rawDate.format("MMM D, YYYY");
      }
      if (date == "tomorrow") {
        rawDate = moment().add(1, "days");
        formattedDate = rawDate.format("MMM D, YYYY");
      }

      if (date == "weekend") {
        const saturday = 6;
        const sunday = 7; // for Saturday
        const today = moment().isoWeekday();
        if (today == saturday || today == sunday) {
          // then just give me this week's instance of that day
          rawDate = moment();
          formattedDate = rawDate.format("MMM D, YYYY");
        } else {
          // otherwise, give me *next week's* instance of that same day
          rawDate = moment().isoWeekday(saturday);
          formattedDate = rawDate.format("MMM D, YYYY");
        }
      }

      // Create values for date object
      // https://github.com/dbushell/Pikaday/issues/764
      let day = rawDate.format("D");
      let month = parseInt(rawDate.format("M")) - 1;
      let year = rawDate.format("YYYY");

      let self = this;
      Vue.nextTick(() => {
        // console.log(moment(formattedDate, "MMMM D, YYYY"));
        self.picker.setDate(new Date(year, month, day), false);
        // self.picker.gotoDate(new Date(year, month, day));
        self.picker.hide();
      });
    }
  },
  mounted() {
    this.inputValue = this.value;

    let $eventDatePicker = $("#event-datepicker");
    // Calendar
    let $pikaPopup = $(".pika-single");
    // Referance for calendar position
    let $datepickContainer = $(".datepick-container");
    // Today, Tomorrow, This weekend added text
    let $pikaCustom = $(".pika-custom");
    let self = this;
    self.picker = new Pikaday({
      container: $(".pika-container")[0],
      field: this.$refs.input,
      trigger: $datepickContainer[0],
      format: this.format,
      onOpen() {
        self.isActive = true;
        self.dateType = "normal";
      },
      onDraw: function() {
        $pikaPopup = $(".pika-single");
        var inputVal = $eventDatePicker.val();
      },
      onClose: function() {
        self.isActive = false;
      },
      onSelect: () => {
        self.inputValue = self.picker.toString("MMM D, YYYY");
        let selectedDate = {
          date: self.picker.toString("MMM D, YYYY"),
          type: self.dateType
        };
        self.$emit("input", selectedDate);
      },
      ...this.options
    });
  }
};
</script>
