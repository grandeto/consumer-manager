<template>
    <div v-if="!readyForEdit" :class="'consumer-' + prop"
    @dblclick="$emit('start-edit',
    {
        id: row.id,
        [prop]: row[prop],
        prop: prop
    })">
        {{ row[prop] }}
    </div>
    <div v-else>
        <el-input
            :type="type"
            :placeholder="prop"
            v-model="cellValue[prop]"
            :disabled="muteActions"
            class="edit-field">
        </el-input>
        <i class="fas fa-check-square fa-2x" v-if="showButtons" @click="$emit('update-consumer-prop', true)"></i>
        <i class="fas fa-times-circle fa-2x" v-if="showButtons" @click="$emit('update-consumer-prop', false)"></i>
    </div>
</template>

<script>
    export default {
        name: 'edit-cell',
        props: ['prop', 'type', 'row', 'muteActions', 'editedConsumerProp'],
        computed: {
            readyForEdit() {
                return ( Object.keys(this.editedConsumerProp).length && this.editedConsumerProp.id === this.row.id && this.editedConsumerProp.prop === this.prop ) ||
                this.row.new;
            },
            showButtons() {
                return !this.muteActions && !this.row.new;
            },
            cellValue() {
                return this.row.new ? this.row : this.editedConsumerProp;
            }
        }
    }
</script>
