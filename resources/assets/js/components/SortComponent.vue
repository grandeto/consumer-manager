<template>
    <el-select
        :value="sortOption"
        placeholder="Sort option"
        :disabled="muteActions"
        @change="$emit('sort-table', $event)">
        <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value">
        </el-option>
    </el-select>
</template>

<script>
    export default {
        name: "sort",
        props: ['muteActions', 'sortOption'],
        data() {
            return {
                options: [
                    {
                        value: 'id_ascending',
                        label: 'ID Asc'
                    }, {
                        value: 'id_descending',
                        label: 'ID Desc'
                    }, {
                        value: 'name_ascending',
                        label: 'Name Asc'
                    }, {
                        value: 'name_descending',
                        label: 'Name Desc'
                    }
                ]
            }
        }
    }
</script>
