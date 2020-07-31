<template>
	<view>
		<view class="head">
			<image class="bg" :src="topicInfo.bg_image"></image>
			<view class="head-c">
				<text class="name">{{topicInfo.topic_name}}</text>
				<view class="count">
					<text>{{topicInfo.userCount}}人已加入</text>·
					<text>{{topicInfo.postCount}}篇内容</text>
					<block v-if="isJoinTopic">
						<u-button class="btn" :custom-style="btnStyle" @click="outTopic">退出圈子</u-button>
					</block>
					<block v-else>
						<u-button class="btn" :custom-style="btnStyle" type="error" @click="joinTopic">加入圈子</u-button>
					</block>
				</view>
			</view>
		</view>
		<!-- 公告 -->
		<view class="member-wrap" v-if="topicInfo.member_list.length > 0" @click="noticeShow =true">
			<view class="member-wrap-head">
				<view class="notice-box">公告<text class="notice-txt">{{topicInfo.description}}</text></view>
				<u-icon class="icon" name="arrow-right"></u-icon>
			</view>
		</view>
		<!-- 公告弹窗 -->
		<u-popup v-model="noticeShow" mode="bottom" :closeable="true" height="60%">
			<view class="popup-notice-wrap">
				<view class="popup-notice-head">公告</view>
				<text>{{topicInfo.description}}</text>
			</view>
		</u-popup>
		<!-- 活跃圈友 -->
		<view class="member-wrap" v-if="topicInfo.member_list.length > 0">
			<view class="member-wrap-head">
				<text>活跃圈友</text>
				<u-icon class="icon" name="arrow-right"></u-icon>
			</view>
			<u-grid :col="5" :border="false" @click="jump">
				<u-grid-item v-for="(item,index) in topicInfo.member_list" :key="index" :index="item.uid">
					<u-avatar class="avatar" :src="item.avatar"></u-avatar>
					<view class="grid-text">{{item.username | substr}}</view>
				</u-grid-item>
			</u-grid>
		</view>
		<!-- 圈子话题 -->
		<view class="member-wrap bottom20" v-if="topicInfo.discuss_list.length > 0">
			<view class="member-wrap-head">
				<text>圈子话题</text>
				<u-icon class="icon" name="arrow-right"></u-icon>
			</view>
			<navigator class="discuss-item" v-for="(item,index) in topicInfo.discuss_list" :key="index" :url="'/pages/discuss/discuss?id='+item.id">
				<text># {{item.title}}</text>
				<text class="count">{{item.read_count}}次浏览</text>
			</navigator>
		</view>
		<!-- 帖子类型 -->
		<view class="post-type-wrap">
			<view :class="order=='hot'?'active':''" @click="postOrder(1)">热门</view>
			<view :class="order=='news'?'active':''" @click="postOrder(2)">最新</view>
			<!-- <view>精华</view> -->
		</view>
		<block v-if="order=='hot'">
			<post-list :list="postHot" :loadStatus="loadStatus"></post-list>
		</block>
		<block v-if="order=='news'">
			<post-list :list="postNews" :loadStatus="loadStatus"></post-list>
		</block>
		<!-- 发布按钮 -->
		<navigator :url="'/pages/plus-post/plus-post?topicId=' + topicId" hover-class="none">
			<view class="plus-box">
				<u-icon name="plus" color="#fff" size="50"></u-icon>
			</view>
		</navigator>
	</view>
</template>

<script>
	import postList from '../../components/post-list/post-list.vue';
	let page1;
	let page2;
	export default {
		components: {
			postList
		},
		data() {
			return {
				noticeShow: false,
				order: 'hot',
				btnStyle: {
					marginRight: 0,
					width: '150rpx',
					fontSize: '12px',
					height: '60rpx',
					lineHeight: '60rpx'
				},
				topicId: 0,
				topicInfo: {},
				postHot: [],
				postNews: [],
				loadStatus: "loading",
				isJoinTopic: false
			};
		},
		onLoad(options) {
			page1 = 1;
			page2 = 1;
			this.topicId = options.id;
			this.getTopicInfo();
			this.getPostList();
			this.getIsJoinTopic();

			//#ifdef MP-WEIXIN
			wx.showShareMenu({
				withShareTicket: true,
				menus: ['shareAppMessage', 'shareTimeline']
			})
			//#endif
		},
		onReachBottom() {
			if (this.order == 'hot') {
				page1++;
			}

			if (this.order == 'news') {
				page2++;
			}

			this.getPostList();
		},
		onPullDownRefresh() {
			if (this.order == 'hot') {
				page1 = 1;
				this.postHot = [];
			}

			if (this.order == 'news') {
				page2 = 1;
				this.postNews = [];
			}

			this.getPostList();
			uni.stopPullDownRefresh();
		},
		onShareAppMessage(res) {
			if (res.from === 'button') { // 来自页面内分享按钮
				console.log(res.target)
			}
			return {
				title: this.topicInfo.topic_name + '-' + this.topicInfo.description,
				path: '/pages/topic-detail/topic-detail?id=' + this.topicId,
				imageUrl: this.topicInfo.cover_image
			}
		},
		filters: {
			substr: function(e) {
				return e.substr(0, 4);
			}
		},
		methods: {
			postOrder(type) {
				if (type === 1) {
					this.order = 'hot';
				}
				if (type === 2) {
					this.order = 'news';
				}
				this.getPostList();
			},
			jump(uid) {
				uni.navigateTo({
					url: "/pages/ucenter/ucenter?uid=" + uid
				})
			},
			joinTopic() {
				this.$H.post('/api/topic/joinTopic', {
					topicId: this.topicId,
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					if (res[1].data.code === 1) {
						this.isJoinTopic = true
					}
				});
			},
			outTopic() {
				this.$H.post('/api/topic/userTopicDel', {
					topicId: this.topicId,
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					if (res[1].data.code === 1) {
						this.isJoinTopic = false
					}
				});
			},
			getIsJoinTopic() {
				this.$H.post('/api/topic/isJoinTopic', {
					topicId: this.topicId,
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					if (res[1].data.code === 1) {
						this.isJoinTopic = true
					}
				});
			},
			getTopicInfo() {
				this.$H.post('/api/topic/topicDetail', {
					id: this.topicId
				}).then(res => {
					this.topicInfo = res[1].data;					
				});
			},
			getPostList() {
				this.loadStatus = "loading";
				let page;
				if (this.order == 'hot') {
					page = page1
				}
				if (this.order == 'news') {
					page = page2
				}
				this.$H.post('/api/post/topicPost?page=' + page + '&order=' + this.order, {
					topicId: this.topicId,
				}).then(res => {
					if (this.order == 'hot') {
						this.postHot = this.postHot.concat(res[1].data.data);
					}

					if (this.order == 'news') {
						this.postNews = this.postNews.concat(res[1].data.data);
					}
					if (res[1].data.current_page === res[1].data.last_page || res[1].data.last_page === 0) {
						this.loadStatus = "nomore";
					} else {
						this.loadStatus = "loadmore";
					}
				});
			}
		}
	}
</script>

<style lang="scss">
	@import 'topic-detail.css';
</style>
