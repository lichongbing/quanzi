<template>
	<view>
		<discuss-list :list="discussList" :loadStatus="loadStatus"></discuss-list>
	</view>
</template>

<script>
	import discussList from '../../components/discuss-list/discuss-list.vue';
	export default {
		components: {
			discussList
		},
		data() {
			return {
				discussList: [],
				loadStatus:"loading"
			};
		},
		onLoad() {
			this.getDiscussList();
		},
		methods: {
			getDiscussList() {
				this.$H.post("/api/discuss/myDis", {
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					this.discussList = res[1].data.data
				})
			}
		}
	}
</script>

<style lang="scss">
	
</style>
