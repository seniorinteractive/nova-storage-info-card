<template>
    <card class="flex flex-col items-center justify-center">
        <div class="px-3 py-3">
            <h1 class="text-center text-3xl text-80 font-light">{{objSize}}</h1>

            <button @click="objSize += 1 ">Click</button>
        </div>
    </card>
</template>

<script>

export default {

    props: {
        loading: {default: true},

    data() {
        return {
            componentLoading: this.loading,
            objSize: 0,
        }
    },

    mounted() {
        this.refreshStatsPeriodically();
    },

    methods: {
        refreshStatsPeriodically() {
            this.componentLoading = true;
            Promise.all([
                this.loadStats(),
            ]).then(() => {
                this.componentLoading = false;
                this.timeout = setTimeout(() => {
                    this.refreshStatsPeriodically(false);
                }, 5000);
            });
        },

        loadStats() {
            Nova.request().get('nova-vendor/StorageInfoCard/stats').then(
                (response) => {
                    this.objSize = response.data.objSize;
                });
        },
    },
    },
}
</script>
