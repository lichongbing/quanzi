<template>
	<view>
		<!-- 我关注的圈子 -->
		<view class="block-title">我关注的圈子</view>
		<topic-list :list="myTopic" loadStatus="none"></topic-list>
		<!-- 热门话题 -->
		<view class="block-title">热门话题</view>
		<!-- 滚动板块 -->
		<scroll-view :scroll-x="true">
			<view class="scroll-view">
				<!-- 热门话题 -->
				<view class="scroll-item">
					<text class="title">热门话题</text>
					<navigator class="dis-item" v-for="(item,index) in hotDisList" hover-class="none" :key="index" :url="'/pages/discuss/discuss?id=' + item.id">
						<view class="dis-title"># {{item.title}}</view>
						<view class="desc">{{item.introduce}}</view>
					</navigator>
				</view>
				<!-- 最新话题 -->
				<view class="scroll-item">
					<text class="title">最新话题</text>
					<navigator class="dis-item" v-for="(item,index) in newDisList" hover-class="none" :key="index" :url="'/pages/discuss/discuss?id=' + item.id">
						<view class="dis-title"># {{item.title}}</view>
						<view class="desc">{{item.introduce}}</view>
					</navigator>
				</view>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				myTopic: [],
				hotDisList: [],
				newDisList: []
			};
		},
		onLoad() {
			this.getMyTopic();
			this.gteHotDiscuss();
			this.gteNewDiscuss();
		},
		methods: {
			//我关注的圈子
			getMyTopic() {
				this.$H.post('/api/topic/myTopic', {
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					this.myTopic = res[1].data.data;
				});
			},
			// 热门话题
			gteHotDiscuss() {
				this.$H.post('/api/discuss/list', {
					active: "hot",
					paginate: 3
				}).then(res => {
					this.hotDisList = res[1].data.data;
				});
			},
			// 最新话题
			gteNewDiscuss() {
				this.$H.post('/api/discuss/list', {
					active: "new",
					paginate: 3
				}).then(res => {
					this.newDisList = res[1].data.data;
				});
			}
		}
	}
</script>

<style lang="scss">
	@import 'more.css';
</style>
