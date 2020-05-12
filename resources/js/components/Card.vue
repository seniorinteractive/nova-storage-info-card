<template>
    <card class="card relative px-6 py-4 card-panel h-auto">
        <loading-view :loading="loading" class="w-full">
            <div class="flex flex-row justify-between">
                <div>
                    <h3 class="mr-3 text-base text-80 font-bold">Контроль дисков</h3>
                    <div class="py-2 flex lg:inline-flex">
                        <span class="flex rounded-full bg-50 uppercase px-2 py-1 text-xs font-bold mr-3">S3</span>
                        <span class="flex rounded-full bg-50 uppercase px-2 py-1 text-xs font-bold mr-3">{{ card.count }} {{ card.measure }}</span>
                    </div>
                </div>
                <div>
                    <button @click="refresh" class="bg-primary flex flex-row items-center text-white rounded py-2 px-4">
                        <span class="mr-2">Обновить</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                            <path d="M20.944 12.979c-.489 4.509-4.306 8.021-8.944 8.021-2.698 0-5.112-1.194-6.763-3.075l1.245-1.633c1.283 1.645 3.276 2.708 5.518 2.708 3.526 0 6.444-2.624 6.923-6.021h-2.923l4-5.25 4 5.25h-3.056zm-15.864-1.979c.487-3.387 3.4-6 6.92-6 2.237 0 4.228 1.059 5.51 2.698l1.244-1.632c-1.65-1.876-4.061-3.066-6.754-3.066-4.632 0-8.443 3.501-8.941 8h-3.059l4 5.25 4-5.25h-2.92z" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </div>

            <ol v-if="!initialLoading" class="w-full text-black" :class="disks.length === 1 ? 'list-none pl-0' : 'pl-4'">
                <li v-for="(disk, i) in disks" :key="i">
                    <div class="flex flex-row justify-between border-b text-black-50 pb-2 py-3" :class="disks.length === 1 ? 'border-transparent' : 'border-primary-10%'">
                        <div class="flex flex-col">
                            <span class="font-semibold">{{card.name}}</span>
                            <span class="w-full font-light">{{ disk.bucket }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="w-full font-semibold">{{ disk.size }}</span>
                            <span class="w-full font-light">{{ disk.items }}</span>
                        </div>
                    </div>
                </li>
            </ol>

        </loading-view>
    </card>
</template>

<script>
    export default {
        props: ['card'],


        data() {
            return {
                loading: true,
                disks: [],
            }
        },

        mounted() {
            this.load();
        },

        methods: {
            refresh() {
                location.reload(true);
            },

            load() {
                this.loading = true;
                Promise.all([
                    this.loadStats()
                ]).then(() => {
                    this.loading = false;
                })
            },

            loadStats() {
                return Nova.request().post('/nova-vendor/storage-info-card/stats', {
                    disk: this.card.disk_name,
                }).then((res) => {
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
