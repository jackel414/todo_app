<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header text-center"><small class="pull-left" v-on:click="showYesterdayTasks()">yesterday</small> {{ currentDate }}</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <p v-show="todaysTasks" v-for="task in currentTasks">
                                    <span class="badge badge-pill badge-primary">{{ task.marker }}</span> <span v-bind:class="{ completed: task.complete }">{{ task.name }}</span> <i v-on:click="markComplete(task)" class="fa fa-check" aria-hidden="true"></i>
                                </p>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Weekly Ledger - Week of {{ startOfWeek }}</div>

                    <div class="card-body">
                        <input v-on:change="addTask($event.target.value)" type="text" class="form-control" placeholder="New task..." aria-label="New task...">
                        <br />
                        <ul class="list-group">
                            <li v-show="weeklyTasks" v-for="task in weeklyTasks" class="list-group-item">
                                <span class="badge badge-pill badge-primary">{{ task.marker }}</span> <span v-bind:class="{ completed: task.complete }">{{ task.name }}</span> <i v-on:click="addTaskToToday(task)" class="fa fa-arrow-right" aria-hidden="true"></i>
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
                weeklyTasks: [],
                currentTasks: [],
                todaysTasks: [],
                yesterdaysTasks: [],
                date: new Date()
            }
        },
        filters: {
            ago(date) {
                return 'test';
            }
        },
        created() {
            this.currentDay = moment(new Date()).format('dddd, MMMM Do');
            this.currentDate = moment(new Date()).format('YYYY-MM-DD');
            this.startOfWeek = moment(new Date()).startOf('week').add(1, 'days').format('MMMM Do');
            axios.get('/tasks').then(({data}) => this.setTasks(data));
        },
        methods: {
            setTasks(tasks) {
                this.weeklyTasks = tasks;
                let currentDate = this.currentDate;
                this.todaysTasks = tasks.filter(function(task) {
                    return task.day == currentDate;
                });
                this.currentTasks = this.todaysTasks;
            },
            addTask(task) {
                var newTask = {
                    name: task,
                    marker: '1',
                    recurring: false,
                    complete: false,
                }

                this.weeklyTasks.push(newTask);

                axios.post('/tasks', newTask).then(function(response) {
                    console.log(response);
                });
            },
            addTaskToToday(task) {
                this.todaysTasks.push(task);

                axios.post('/tasks/' + task.id + '/do-today', []).then(function(response) {
                    console.log(response);
                });
            },
            showYesterdayTasks() {
                this.currentTasks = this.yesterdaysTasks;
                this.currentDay = moment(new Date()).subtract(1, 'days').format('dddd, MMMM Do');
            },
            markComplete(task) {
                task.complete = true;

                axios.post('/tasks/' + task.id + '/complete', []).then(function(response) {
                    console.log(response);
                });
            }
        }
    }
</script>
