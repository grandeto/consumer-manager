<template>
    <div id="app">
        <div id="content">
            <el-row type="flex" class="table-actions">
                <el-col :offset="0" :span="8" class="table-search">
                    <el-input
                        @input="tableSearch"
                        :disabled="muteActions"
                        :value="searchInput"
                        size="medium"
                        :span="6"
                        placeholder="Search">
                    </el-input>
                </el-col>
                <el-col :offset="8" :span="8" class="table-sort" align="right">
                    <sort
                        :mute-actions="muteActions"
                        :sort-option="sortOption"
                        @sort-table="sortTable">
                    </sort>
                </el-col>
            </el-row>
            <el-row type="flex">
                <el-col>
                    <el-table
                        :data="consumersSearch"
                        stripe
                        border
                        :empty-text="noCunsomers"
                        ref="consumersTable"
                        sortable="custom"
                        class="table-consumers">
                        <el-table-column
                            label="#"
                            prop="id"
                            :sort-method="sortById"
                            width="70">
                        </el-table-column>
                        <el-table-column
                            label="Name"
                            prop="name"
                            :sort-method="sortByName"
                            width="300">
                            <template slot-scope="scope">
                                <edit-cell
                                    prop="name"
                                    type="text"
                                    :edited-consumer-prop="editedConsumerProp"
                                    :mute-actions="muteActions"
                                    :class="{ 'consumer-green': scope.row.age < 18, 'consumer-red': scope.row.age > 60, }"
                                    :row="scope.row"
                                    @start-edit="startEdit"
                                    @update-consumer-prop="updateConsumerProp"
                                    ></edit-cell>
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Age"
                            width="100">
                            <template slot-scope="scope">
                                <edit-cell
                                    prop="age"
                                    type="number"
                                    :edited-consumer-prop="editedConsumerProp"
                                    :mute-actions="muteActions"
                                    :row="scope.row"
                                    @start-edit="startEdit"
                                    @update-consumer-prop="updateConsumerProp"
                                ></edit-cell>
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="City"
                            width="200">
                            <template slot-scope="scope">
                                <edit-cell
                                    prop="city"
                                    type="text"
                                    :edited-consumer-prop="editedConsumerProp"
                                    :mute-actions="muteActions"
                                    :row="scope.row"
                                    @start-edit="startEdit"
                                    @update-consumer-prop="updateConsumerProp"
                                ></edit-cell>
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Actions"
                            align="center">
                            <template slot-scope="scope">
                                <el-button
                                    v-if="!scope.row.new"
                                    :disabled="muteActions"
                                    @click="destroy(scope.row.id)">Delete</el-button>
                                <el-button
                                    v-if="scope.row.new"
                                    :disabled="muteActions"
                                    class="add-button"
                                    @click="store(scope.row)">Add User</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-col>
            </el-row>
        </div>
    </div>
</template>

<script>

    import SortComponent from './SortComponent.vue';
    import EditCellComponent from './EditCellComponent.vue';

    export default {
        data() {
            return {
                consumers: [],
                noCunsomers: "Fetching consumers. Please wait...",
                sortOption: '',
                searchInput: '',
                editedConsumerProp: {},
                muteActions: false
            }
        },
        computed: {
            consumersSearch() {
                let consumers = this.consumers.slice(0);
                consumers.push({new:true});
                if (!this.searchInput) {
                    return consumers;
                } else {
                    return consumers.filter(consumer => {
                        let found =
                        consumer.new ||
                        consumer.name.toLowerCase().includes(this.searchInput) ||
                        consumer.age.toString().toLowerCase().includes(this.searchInput) ||
                        consumer.city.toLowerCase().includes(this.searchInput);

                        if (!found) this.noCunsomers = "Nothing found"

                        return found;
                    });
                }
            }
        },
        methods: {
            getAll() {
                this.muteActions = true;
                window.axios.get('/api/consumers').then(({ data }) => {
                    data.forEach(consumer => {
                        this.consumers.push(consumer);
                    });
                    this.muteActions = false;
                }).catch(error => {
                    this.$message({
                        showClose: true,
                        dangerouslyUseHTMLString: true,
                        message: this.handleErrorMessages(error.response.data.errors),
                        type: 'error',
                        duration: 5000
                    });
                    this.muteActions = false;
                    this.noCunsomers = "Unable to show the consumers. Please try again later."
                });
            },
            store(consumer) {
                this.muteActions = true;
                window.axios.post('/api/consumers/', consumer).then(({ data }) => {
                    this.consumers.push(data);
                    this.muteActions = false;
                    this.searchInput = '';
                    this.sortTable("id_ascending");
                }).catch(error => {
                    this.$message({
                        showClose: true,
                        dangerouslyUseHTMLString: true,
                        message: this.handleErrorMessages(error.response.data.errors),
                        type: 'error',
                        duration: 5000
                    });
                    this.muteActions = false;
                });;
            },
            update(id, consumer) {
                this.muteActions = true;
                window.axios.put(`/api/consumers/${id}`, consumer).then(() => {
                    let editedConsumer = this.consumers.find(consumer => consumer.id === id);
                    editedConsumer.name = consumer.name;
                    editedConsumer.age = consumer.age;
                    editedConsumer.city = consumer.city.charAt(0).toUpperCase() + consumer.city.toLowerCase().slice(1);
                    this.editedConsumerProp = {};
                    this.muteActions = false;
                }).catch(error => {
                    this.$message({
                        showClose: true,
                        dangerouslyUseHTMLString: true,
                        message: this.handleErrorMessages(error.response.data.errors),
                        type: 'error',
                        duration: 5000
                    });
                    this.editedConsumerProp = {};
                    this.muteActions = false;
                });;
            },
            destroy(id) {
                this.muteActions = true;
                window.axios.delete(`/api/consumers/${id}`).then(() => {
                    let index = this.consumers.findIndex(consumer => consumer.id === id);
                    this.consumers.splice(index, 1);
                    this.$message({
                        showClose: true,
                        message: 'Consumer successfuly deleted!',
                        type: 'success'
                    });
                    this.muteActions = false;
                    if (!this.consumers.length) this.noCunsomers = "No consumers. Add the first one!";
                }).catch(error => {
                    this.$message({
                        showClose: true,
                        dangerouslyUseHTMLString: true,
                        message: this.handleErrorMessages(error.response.data.errors),
                        type: 'error',
                        duration: 5000
                    });
                    this.muteActions = false;
                });
            },
            tableSearch(text){
                this.searchInput = text.toLowerCase();
            },
            sortTable(prop) {
                this.sortOption = prop;
                let sortAttr = prop.split('_');
                this.$refs.consumersTable.sort(sortAttr[0], sortAttr[1]);
            },
            sortById(a, b) {
                return this.sortRows(a, b, 'id');
            },
            sortByName(a, b) {
                return this.sortRows(a, b, 'name');
            },
            sortRows(a, b, prop) {
                if (a['new'] === undefined && b['new'] === undefined) {
                    if (prop === 'name') {
                        if (a[prop] !== undefined) {
                            if (a[prop].toLowerCase() > b[prop].toLowerCase()) {
                                return 1;
                            }
                            if (a[prop].toLowerCase() < b[prop].toLowerCase()) {
                                return -1;
                            }
                        } else {
                            if (a[prop] > b[prop]) {
                                return 1;
                            }
                            if (a[prop] < b[prop]) {
                                return -1;
                            }
                        }
                    } else {
                        if (a[prop] > b[prop]) {
                            return 1;
                        }
                        if (a[prop] < b[prop]) {
                            return -1;
                        }
                    }
                    return 0;
                }
            },
            startEdit(event) {
                this.editedConsumerProp = event;
            },
            updateConsumerProp(update) {
                if (update) {
                    this.muteActions = true;
                    let consumer = this.consumers.find(consumer => consumer.id == this.editedConsumerProp.id);
                    consumer = Object.assign({}, consumer);
                    consumer[this.editedConsumerProp.prop] = this.editedConsumerProp[this.editedConsumerProp.prop];
                    this.update(consumer.id, consumer);
                } else {
                    this.editedConsumerProp = {};
                }
            },
            handleErrorMessages(errors) {
                if (typeof(errors) === "string") {
                    return errors;
                } else {
                    let ul = '<ul>';
                    for (let i in errors) {
                        ul+= '<li>' + errors[i][0] + '</li>';
                    }
                    return ul+= '</ul>';
                }
            }
        },
        components: {
            'sort': SortComponent,
            'edit-cell': EditCellComponent
        },
        created() {
            this.getAll();
        }
    }
</script>
