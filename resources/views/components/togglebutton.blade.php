<template id="toggle-button">
    <button v-on:click="toggle = !toggle" type="button"
            v-bind:class="toggle?'btn-primary':'btn-default'"
            class="btn btn-xs"
    >
        <span class="@{{ style }}">@{{ content }}</span>
    </button>
</template>

<script>
    Vue.component('togglebutton', {
        template: '#toggle-button',
        props:    ['toggle', 'style', 'content']
    });
</script>
