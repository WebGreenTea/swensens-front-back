<template>
    <div class="modal-loading" v-if="loadscreen">
    <div class="load-icon-container">
        <div class="load"></div>
    </div>
  </div>
</template>

<script>
export default {
    name:'LoadScreen',
    props:{
        loadscreen: Boolean,
    }
}
</script>

<style>

</style>