<style>
    .toggle-button {
        line-height:30px;
        margin-right:10px;
    }

    .toggle-button button {
        position: relative;
        bottom: 1px;
    }

    .toggle-button span {
        font-size: 1.2rem;
    }

</style>

<template id="toggle-button">
    <button v-on:click="toggle = !toggle" type="button"
            v-bind:class="toggle?'btn-primary':'btn-default'"
            class="btn btn-xs"
    >
        <span class="@{{ style }}">@{{ content }}</span>
        <input type="checkbox" v-model="toggle" class="hidden"/>
    </button>
</template>

<script>
    Vue.component('togglebutton', {
        template: '#toggle-button',
        props:    ['toggle', 'style', 'content']
    });
</script>
