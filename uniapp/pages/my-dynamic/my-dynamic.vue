<template>
	<view>
		<post-list :list="postList" :loadStatus="loadStatus"></post-list>
	</view>
</template>

<script>
	import postList from '../../components/post-list/post-list.vue';
	let page;
	export default {
		components: {
			postList
		},
		data() {
			return {
				postList: [],
				loadStatus: "loading"
			};
		},
		onLoad() {
			page = 1;
			this.getDiscussList();
		},
		onReachBottom() {
			page++;
			this.getDiscussList();
			
		},
		methods: {
			getDiscussList() {
				this.loadStatus = "loading";
				this.$H.post('/api/post/myPost?page=' + page, {
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					this.postList = this.postList.concat(res[1].data.data);
					if (res[1].data.current_page === res[1].data.last_page || res[1].data.last_page === 0) {
						this.loadStatus = "nomore";
					} else {
						this.loadStatus = "loadmore"
					}
				})
			}
		}
	}
</script>

<style lang="scss">

</style>
