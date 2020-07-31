<template>
	<view>
		<view  class="status-box" :style="{'height':BoundHeight}"></view>
		<view class="head" :style="{'top':BoundHeight}">
			<view class="tab-box">
				<u-tabs name="tab_name" bg-color="#fff" :list="tabs" active-color="#616161" :is-scroll="false" c :current="current" @change="tabChange"></u-tabs>
			</view>
		</view>
		<view :style="{'margin-top':contentMargin}">
			<view v-show="current === 1">
				<!-- 推荐内容 -->
				<view class="post-list">
					<post-list :list="recommendPost" :loadStatus="loadStatus"></post-list>
				</view>
			</view>
			<view v-show="current === 0">
				<post-list :list="followPost" :loadStatus="loadStatus"></post-list>
			</view>
		</view>
		<!-- 返回顶部 -->
		<u-back-top :scroll-top="scrollTop"></u-back-top>
	</view>
</template>

<script>
	import postList from '../../components/post-list/post-list.vue';
	import topicList from '../../components/topic-list/topic-list.vue';
	let page1;
	let page2;
	export default {
		components: {
			postList,
			topicList
		},
		data() {
			return {
				tabs: [{
						tab_name: '关注'
					},
					{
						tab_name: '推荐'
					}
				],
				current: 1,
				myTopic: [],
				scrollTop: 0,
				recommendPost: [],
				followPost: [],
				loadStatus: "loading",
				storageTopicList: [],
				BoundHeight:"40px",
				contentMargin:""

			}
		},
		onShareAppMessage(res) {
			if (res.from === 'button') { // 来自页面内分享按钮
				console.log(res.target)
			}
			let imgURL = this.$H.domain + "/uploads/default/wx_share_cover.jpg";
			return {
				title: this.$H.miniappName,
				path: '/pages/index/index',
				imageUrl: imgURL
			}
		},
		onLoad() {
			page1 = 1;
			page2 = 1;
			
			let menuButtonInfo = uni.getMenuButtonBoundingClientRect()
			this.BoundHeight = menuButtonInfo.top + 'px'
			this.contentMargin = menuButtonInfo.top*2 + 'px'
			
			this.getRecommendPost();
			this.storageTopicList = uni.getStorageSync("topicList");

			//#ifdef MP-WEIXIN
			wx.showShareMenu({
				withShareTicket: true,
				menus: ['shareAppMessage', 'shareTimeline']
			})
			//#endif
		},
		onShow() {
			this.getMyTopic();
		},
		onPullDownRefresh() {
			page1 = 1;
			page2 = 1;
			this.recommendPost = [];
			this.getRecommendPost();
			uni.stopPullDownRefresh();
		},
		onPageScroll(e) {
			this.scrollTop = e.scrollTop;
		},
		onReachBottom() {
			if (this.current === 1) {
				page1++
				this.getRecommendPost();
			} else if (this.current === 2) {
				page2++
				this.getFollowPost();
			}
		},
		methods: {
			tabChange(index) {
				this.current = index;
				if (index === 0) {
					this.followPost = [];
					this.loadStatus = "loading";
					this.getFollowPost();
				}
			},			
			getRecommendPost() {
				this.loadStatus = "loading";
				this.$H.post('/api/post/allPost?page=' + page1).then(res => {
					this.recommendPost = this.recommendPost.concat(res[1].data.data);
					if (res[1].data.current_page === res[1].data.last_page || res[1].data.last_page === 0) {
						this.loadStatus = "nomore";
					} else {
						this.loadStatus = "loadmore"
					}
				});
			},
			getFollowPost() {
				this.$H.post('/api/post/followUserPost?page=' + page2, {
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					this.followPost = this.followPost.concat(res[1].data.data);
					if (res[1].data.current_page === res[1].data.last_page || res[1].data.last_page === 0) {
						this.loadStatus = "nomore";
					} else {
						this.loadStatus = "loadmore"
					}
				});
			},
			onTagItem(e) {
				if (this.current !== e.currentIndex) {
					this.current = e.currentIndex;
				}
				if (this.current === 0) {
					this.getRecommendPost();
				} else if (this.current === 1) {
					this.getFollowPost();
				} else if (this.current === 2) {
					this.getHotPost();
				}
			},
			delStorageTopic() {
				uni.setStorageSync("topicList", "");
				this.storageTopicList = []
			}
		}
	}
</script>

<style>
	@import "./index.css";
</style>
