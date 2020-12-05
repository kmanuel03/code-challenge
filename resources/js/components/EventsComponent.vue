<template>
    <div class="container">
        <div class="row">
            <div class="col-5">
                <h5> CALENDAR </h5>
            </div>
            <div class="col-7">
                <h5> December 2020</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-5">
                <form>
                    <div class="form-row">
                        <div class="col">
                            <div class="alert alert-success" v-if="noErrors">Successfully Saved!</div>
                            <label for="eventTitle">Event</label>
                            <input type="text" class="form-control" :class="{'border border-danger' : input_error.title}" id="eventTitle" v-model="data.title">
                            <small v-if="input_error.title" :class="{'text-danger' : input_error.title}">{{ input_error.title[0] }}</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label for="dateFrom">From</label>
                            <input type="date" class="form-control" :class="{'border border-danger' : input_error.dateFrom }" id="dateFrom" v-model="data.dateFrom">
                            <small v-if="input_error.dateFrom" :class="{'text-danger' : input_error.dateFrom}">{{ input_error.dateFrom[0] }}</small>
                        </div>
                        <div class="col">
                            <label for="dateTo">To</label>
                            <input type="date" class="form-control" :class="{'border border-danger' : input_error.dateTo }" id="dateTo" v-model="data.dateTo">
                            <small v-if="input_error.dateTo" :class="{'text-danger' : input_error.dateTo}">{{ input_error.dateTo[0] }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <div class="form-check">
                                <div class="form-check form-check-inline" v-for="(day, index) in weekDays" :key="index">
                                    <input type="checkbox" class="form-check-input" :id="day.id" :value="day.id" v-model="data.checkedDays">
                                    <label class="form-check-label" :for="day.id">{{ day.name }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <small v-if="input_error.checkedDays" :class="{'text-danger' : input_error.checkedDays}">{{ input_error.checkedDays[0] }}</small>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="button" @click="save" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-7">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between" :class="{ 'list-group-item-info': event.hasEvent }" v-for="event in events.dateRange">{{moment(event.date)}} <span>{{ event.hasEvent ? events.events.title : '' }}</span></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => {
            return {
                weekDays: [
                    {
                        id: 1,
                        name: 'mon'
                    },
                    {
                        id: 2,
                        name: 'tue'
                    },
                    {
                        id: 3,
                        name: 'wed'
                    },
                    {
                        id: 4,
                        name: 'thu'
                    },
                    {
                        id: 5,
                        name: 'fri'
                    },
                    {
                        id: 6,
                        name: 'sat'
                    },
                    {
                        id: 0,
                        name: 'sun'
                    },
                ],
                daysInMonth: [],
                data: {
                    checkedDays: [],
                },
                events: {
                    dateRange: []
                },
                input_error : {},
                noErrors: false

            }
        },
        methods: {
            save() {
                axios.post('api/save-events', this.data).then(response => {
                    this.events = response.data;
                    this.input_error = {};
                    this.noErrors = true;
                }).catch((error) => {
                    this.input_error = error.response.data.errors;
                    this.noErrors = false;
                });
            },
            moment(date) {
                return this.$moment(date).format('D ddd');
            },
            getDaysInMonth() {
                var monthDate = this.$moment().startOf('month');
                for(let i=0; i < monthDate.daysInMonth(); i++){
                    let newDay = monthDate.clone().add(i,'days');
                    this.events.dateRange.push({date: newDay, hasEvent: false});
                }
            },
        },
        mounted() {
            //for calendar visual on page load
            this.getDaysInMonth();
        }
    }
</script>
