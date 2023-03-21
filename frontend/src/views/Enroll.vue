<template>
  <div v-if="selectedService === null" class="wrapper">
    <h1>Выберете услугу</h1>
    <ul>
      <li style="background: rosybrown" v-for="(item, index) in items" :key="index" @click="handleClick(item)">
        <span>{{ item.name }}</span>
        <div>
          <span>Цена: {{ item.price }} RUB. Время выполнения {{ item.time_executing }} ч.</span>
          <button>Выбрать</button>
        </div>
      </li>
    </ul>
  </div>
  <div v-else-if="selectedTime === null" class="wrapper">
    <h1>Выберете дату</h1>
    <div class="calendar">
      <div class="calendar-header">
        <button @click="prevMonth">&lt;</button>
        <h2>{{ getNameMonth(month) }} {{ year }}</h2>
        <button @click="nextMonth">&gt;</button>
      </div>
      <table class="calendar-table">
        <thead>
        <tr>
          <th>Пн</th>
          <th>Вт</th>
          <th>Ср</th>
          <th>Чт</th>
          <th>Пт</th>
          <th>Сб</th>
          <th>Вс</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(week, index) in weeks" :key="index">
          <td
              v-for="day in week"
              :key="day.date.getTime()"
              :class="{
                'current': isSameDay(day.date, currentDate),
                'selected': isSameDay(day.date, selectedDate),
                'disabled': !isInMonth(day.date)
              }"
              @click="selectDate(day.date)">
            <span>
              {{ day.date.getDate() }}
            </span>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <h1>Выбере время:</h1>
    <div class="time-picker">
      <input
          v-for="(time, index) in times"
          :key="index"
          :value="time"
          @click="selectedTime = time"
          type="button">
    </div>
  </div>
  <div v-else-if="selectedTime !== null" class="wrapper">
    <h1>Заполните свою контактную информацию</h1>
    <form>
      <label for="phone">Телефон:</label>
      <input type="tel" id="phone" name="phone" v-model="user.phone">
      <span v-if="!user.phone">Это поле должно быть обязательно заполненым</span>

      <label for="name">Имя:</label>
      <input type="text" id="name" name="name" v-model="user.name">
      <span v-if="!user.name">Это поле должно быть обязательно заполненым</span>

      <label for="surname">Фамилия (не обязательно):</label>
      <input type="text" id="surname" name="surname" v-model="user.surname">
      <br>

      <button type="submit" @click="sendEnrollSet()">Записаться</button>
    </form>
  </div>
</template>


<script>
const apiDomain = import.meta.env.VITE_API_DOMAIN;

import axios from 'axios';

export default {
  computed: {
    weeks() {
      const weeks = [];
      let firstMonthOfDay = new Date(this.year, this.month, 1);
      const lastMonthOfDay = new Date(this.year, this.month + 1, 0).getDate();
      const prevLastMonthOfDay = new Date(this.year, this.month, 0).getDate();
      let prevDayOfWeek = firstMonthOfDay.getDay() - 1;
      if (prevDayOfWeek <= 0) {
        prevDayOfWeek = 6;
      }
      let week = [];
      // Add null values for days from previous month
      for (let i = prevLastMonthOfDay - prevDayOfWeek + 1; i <= prevLastMonthOfDay; i++) {
        week.push({date: new Date(this.year, this.month - 1, i)});
      }
      for (let i = 1; i <= lastMonthOfDay; i++) {
        week.push({date: new Date(this.year, this.month, i)});
        if (week.length === 7) {
          weeks.push(week);
          week = [];
        }
      }
      // Add null values for days from next month
      let nextMonthDay = 1;
      while (week.length < 7) {
        week.push({date: new Date(this.year, this.month + 1, nextMonthDay)});
        nextMonthDay++;
      }
      if (week.length > 0) {
        weeks.push(week);
      }
      return weeks;
    }
  },
  mounted() {
    const today = new Date();
    this.month = today.getMonth();
    this.year = today.getFullYear();
    this.selectedDate = today;
    this.currentDate = today;
  },
  data() {
    return {
      items: [
        {
          name: 'Маникюр 1',
          price: 100,
          time_executing: 3,
        },
        {
          name: 'Маникюр 2',
          price: 100,
          time_executing: 3,
        },
        {
          name: 'Маникюр 3',
          price: 100,
          time_executing: 3,
        }
      ],
      selectedService: null,
      month: null,
      year: null,
      selectedDate: null,
      currentDate: null,
      selectedTime: null,
      times: ['10:00', '13:00', '16:00', '19:00', '21:00'],
      user: {
        name: null,
        surname: null,
        phone: null,
      }
    }
  },
  methods: {
    handleClick(item) {
      this.selectedService = item;
    },
    prevMonth() {
      if (this.currentDate.getMonth() === this.month) {
        return;
      }
      if (this.month === 0) {
        this.month = 11;
        this.year--;
      } else {
        this.month--;
      }
    },
    nextMonth() {
      if (this.month === 11) {
        this.month = 0;
        this.year++;
      } else {
        this.month++;
      }
    },
    isInMonth(date) {
      return date.getMonth() === this.month
          && date.getFullYear() === this.year
          && this.currentDate.getDate() <= date.getDate();
    },
    isSameDay(date1, date2) {
      if (!date1 || !date2) {
        return false;
      }
      return date1.getDate() === date2.getDate() &&
          date1.getMonth() === date2.getMonth() &&
          date1.getFullYear() === date2.getFullYear();
    },
    selectDate(date) {
      if (!this.isInMonth(date)) {
        return;
      }
      this.selectedDate = date;
    },
    getNameMonth(month) {
      const months = [
        'январь',
        'февраль',
        'март',
        'апрель',
        'май',
        'июнь',
        'июль',
        'август',
        'сентябрь',
        'октябрь',
        'ноябрь',
        'декабрь'
      ];
      return months[month];
    },
    alertSomeError() {
      alert('Произошла не известная ошибка. Обновите страницу');
    },
    emptyFieldsError() {
      alert('Неоходимо заполнить все обязательные поля');
    },
    validateData() {
      if (!this.selectedService) {
        this.alertSomeError();
        return false;
      }
      if (!this.selectedDate) {
        this.alertSomeError();
        return false;
      }
      if (!this.selectedTime) {
        this.alertSomeError();
        return false;
      }
      if (!this.user.phone) {
        this.emptyFieldsError();
        return false;
      }
      if (!this.user.name) {
        this.emptyFieldsError();
        return false;
      }
      return true
    },
    sendEnrollSet() {
      if (!this.validateData()) {
        return;
      }
      axios.post(apiDomain + '/enroll/set', {
            service: this.selectedService,
            date: this.selectedDate,
            time: this.selectedTime,
            user: this.user
          })
          .catch(error => {
            console.log(error);
          });
    }
  },
  created() {
    axios.get(apiDomain + '/services')
        .then(response => {
          this.items = response.data;
        })
        .catch(error => {
          console.log(error);
        });
  },
}
</script>

<style scoped>

form {
  display: flex;
  flex-direction: column;
}

.calendar {
  display: flex;
  flex-direction: column;
  align-items: center;
  font-family: sans-serif;
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  margin-bottom: 10px;
}

.calendar-header button {
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
}

.calendar-table {
  border-collapse: collapse;
  margin-bottom: 20px;
  width: 100%;
}

.calendar-table tbody {
  height: 270px;
}

.calendar-table td {
  text-align: center;
  padding: 10px;
  border: 1px solid #ccc;
  cursor: pointer;
}

.calendar-table td.current {
  background-color: red;
  color: #fff;
}

.calendar-table td.selected {
  background-color: #00bcd4;
  color: #fff;
}

.calendar-table td.disabled {
  color: #ccc;
  cursor: default;
}

.time-picker {
  display: flex;
  align-items: center;
  justify-content: space-evenly;
}

.time-picker label {
  margin-right: 10px;
}

ul {
  display: flex;
  padding: 0;
  flex-direction: column;
  justify-content: center;
  height: 100%;
}

li {
  list-style: none;
  margin-bottom: 1rem;
  border-radius: 5px;
}

li span {
  font-size: 18px;
  width: 100%;
  font-weight: bold;
  padding-left: 5px;
}

li button {
  padding: 10px 20px;
  background-color: #007bff;
  border: none;
  border-radius: 50px;
}

li div {
  display: flex;
  flex-direction: row;
  align-items: center;
}

</style>