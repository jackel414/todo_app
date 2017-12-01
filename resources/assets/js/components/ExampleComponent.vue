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
                        <input v-on:keyup.13="addTask(newTask)" v-model="newTask" type="text" class="form-control" placeholder="New task..." aria-label="New task...">
                        <br />
                        <ul class="list-group">
                            <li v-show="weeklyTasks" v-for="task in weeklyTasks" class="list-group-item">
                                <span class="badge badge-pill badge-primary">{{ task.marker }}</span>&nbsp;
                                <span v-bind:class="{ completed: task.complete }">{{ task.name }}</span>&nbsp;
                                <i v-on:click="addTaskToToday(task)" class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp;&nbsp;
                                <i v-on:click="deleteTask(task)" class="fa fa-times" aria-hidden="true"></i>
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
                date: new Date(),
                newTask: '',
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
            this.getTasks();
        },
        methods: {
            getTasks() {
                axios.get('/tasks').then(({data}) => this.setTasks(data));
            },
            setTasks(tasks) {
                this.weeklyTasks = tasks;
                let currentDate = this.currentDate;
                this.todaysTasks = tasks.filter(function(task) {
                    return task.day == currentDate;
                });
                this.currentTasks = this.todaysTasks;
            },
            addTask(task) {
                if (task.length < 1) {
                    alert("Please enter task name");
                    return false;
                }

                var newTask = {
                    name: task,
                    marker: '1',
                    recurring: false,
                    complete: false,
                }

                this.weeklyTasks.push(newTask);

                this.newTask = '';

                axios.post('/tasks', newTask).then((response) => {
                    let postion = this.weeklyTasks.length - 1;
                    this.weeklyTasks[postion].id = response.data.task.id;
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
            },
            deleteTask(task) {
                axios.delete('/tasks/' + task.id).then((response) => {
                    this.getTasks();
                });
            }
        }
    }
</script>
