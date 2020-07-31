<template>
	<view class="container">
		<topic-list :list="topicList" :loadStatus="loadStatus"></topic-list>

		<!-- 创建圈子按钮 -->
		<view style="height: 120rpx;"></view>
		<u-button @click="jump" class="fixed-bottom" type="error" shape="circle">
			<u-icon name="plus"></u-icon>
			<text>创建圈子</text>
		</u-button>
	</view>
</template>

<script>
	import topicList from '../../components/topic-list/topic-list.vue';
	let page;
	export default {
		components: {
			topicList
		},
		data() {
			return {
				topicList: [],
				loadStatus: "loading"
			};
		},
		onLoad() {
			page = 1;
			this.getMyTopic();
		},
		onReachBottom() {
			page++;
			this.getMyTopic();
		},
		methods: {
			getMyTopic() {
				this.loadStatus = "loading";
				this.$H.post("/api/topic/myCreateTopic?page="+page, {
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					this.topicList = this.topicList.concat(res[1].data.data);
					if (res[1].data.current_page === res[1].data.last_page || res[1].data.last_page === 0) {
						this.loadStatus = "nomore";
					} else {
						this.loadStatus = "loadmore"
					}
				})
			},
			jump(){
				uni.navigateTo({
					url:'/pages/topic-add/topic-add'
				})
			}
		}
	}
</script>

<style lang="scss">
	page{
		background-color: #f5f5f5;
	}
</style>
