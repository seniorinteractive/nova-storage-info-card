<template>
    <card class="card relative px-6 py-4 card-panel h-auto">
        <loading-view :loading="loading" class="w-full">
            <h3 class="mr-3 text-base text-80 font-bold">Контроль дисков</h3>
            <div class="py-2 flex lg:inline-flex">
                <span class="flex rounded-full bg-primary uppercase px-2 py-1 text-xs text-white font-bold mr-3">S3</span>
                <span class="flex rounded-full bg-primary uppercase px-2 py-1 text-xs text-white font-bold mr-3">5 TB</span>
            </div>

            <table v-if="!initialLoading" class="table w-full">
                <tbody>
                <tr v-for="(disk, i) in disks" :key="i">
                    <td>
                        <span class="font-semibold">{{ i + 1 }}. test</span> <br>
                        <span class="w-full font-light">{{ disk.bucket }}</span>
                    </td>
                    <td class="">
                        <span class="w-full font-semibold">{{ disk.size }}</span> <br>
                        <span class="w-full font-light">{{ disk.items }}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </loading-view>
    </card>
</template>

<script>

    export default {
        props: ['card'],

        data() {
            return {
                loading: true,
                disks: []
            }
        },

        mounted() {
            this.refreshStatsPeriodically();
        },

        methods: {
            refreshStatsPeriodically() {
                this.loading = true;
                Promise.all([
                    this.loadStats()
                ]).then(() => {
                    this.loading = false;
                })
            },

            loadStats() {
                return Nova.request().post('/nova-vendor/storage-info-card/stats', {
                    disk: 'yandex',
                }).then((res) => {
                    this.disks.push({
                        bucket: res.data.bucket,
                        size: res.data.size,
                        items: res.data.items,
                    });

                    this.disks.push({
                        bucket: res.data.bucket,
                        size: res.data.size,
                        items: res.data.items,
                    });

                    this.disks.push({
                        bucket: res.data.bucket,
                        size: res.data.size,
                        items: res.data.items,
                    });
                });
            },
        },
    }
</script>
