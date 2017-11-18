<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header text-center"><small class="pull-left" v-on:click="showYesterdayTasks()">yesterday</small> {{ currentDay }}</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <p v-show="todaysTasks" v-for="task in currentTasks">
                                    <span class="badge badge-pill badge-primary">{{ task.marker }}</span> <span v-bind:class="{ completed: task.done }">{{ task.name }}</span>
                                </p>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Weekly Ledger - Week of {{ weekStart() }}</div>

                    <div class="card-body">
                        <input v-on:change="addTask($event.target.value)" type="text" class="form-control" placeholder="New task..." aria-label="New task...">
                        <br />
                        <ul class="list-group">
                            <li v-show="weeklyTasks" v-for="task in weeklyTasks" class="list-group-item">
                                <span class="badge badge-pill badge-primary">{{ task.marker }}</span> <span v-bind:class="{ completed: task.done }">{{ task.name }}</span> <i v-on:click="addTaskToToday(task)" class="fa fa-arrow-right" aria-hidden="true"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                weeklyTasks: [
                    {
                        marker: '1',
                        name: "Work on Site",
                        recurring: false,
                        done: false,
                    },
                    {
                        marker: '2',
                        name: "Call Docter",
                        recurring: false,
                        done: true,
                    },
                    {
                        marker: '3',
                        name: "Call Dentist",
                        recurring: false,
                        done: false,
                    }
                ],
                currentTasks: [],
                todaysTasks: [
                    {
                        marker: '1',
                        name: "Work on Site",
                        recurring: false,
                        done: false,
                    }
                ],
                yesterdaysTasks: [
                    {
                        marker: '2',
                        name: "Call Docter",
                        recurring: false,
                        done: true,
                    }
                ],
                date: new Date()
            }
        },
        filters: {
            ago(date) {
                return 'test';
            }
        },
        created() {
            this.currentTasks = this.todaysTasks;
            this.currentDay = moment(new Date()).format('dddd, MMMM Do');
        },
        methods: {
            addTask(task) {
                var newTask = {
                    name: task
                }

                this.tasks.push(newTask);
            },
            addTaskToToday(task) {
                this.todaysTasks.push(task);
            },
            weekStart() {
                return moment(new Date()).startOf('week').add(1, 'days').format('MMMM Do');
            },
            showYesterdayTasks() {
                this.currentTasks = this.yesterdaysTasks;
                this.currentDay = moment(new Date()).subtract(1, 'days').format('dddd, MMMM Do');
            }
        }
    }
</script>
