<template>
	<view>
		<view class="head">
			<block v-if="hasLogin">
				<view class="userinfo" @click="toUcenter">
					<u-avatar :src="userInfo.avatar"></u-avatar>
					<view class="username">
						<text>{{userInfo.username}}</text>
						<text>{{userInfo.intro}}</text>
					</view>
					<text class="iconfont icon-right"></text>
				</view>
			</block>
			<block v-else>
				<view class="btn-login">
					<u-button type="error" shape="circle" @click="toLogin" plain>授权登录</u-button>
				</view>
			</block>
		</view>
		<!--  -->
		<view class="grid">
			<u-grid :col="4" :border="false" style="margin: 20rpx 0;" @click="toNav">
				<u-grid-item index="/pages/my-fans/my-fans">
					<text>{{userInfo.fans}}</text>
					<view class="grid-text">粉丝</view>
				</u-grid-item>
				<u-grid-item index="/pages/my-follow/my-follow">
					<text>{{userInfo.follow}}</text>
					<view class="grid-text">关注</view>
				</u-grid-item>
				<u-grid-item>
					<text>{{userInfo.post_num}}</text>
					<view class="grid-text">帖子数</view>
				</u-grid-item>
				<u-grid-item>
					<text>{{userInfo.integral}}</text>
					<view class="grid-text">积分</view>
				</u-grid-item>
			</u-grid>
		</view>
		<!-- 个人中心 -->
		<view class="grid">
			<view>个人中心</view>
			<u-grid :col="4" :border="false" style="margin: 20rpx 0;" @click="toNav">
				<u-grid-item index="/pages/my-discuss/my-discuss">
					<image class="gn-icon" src="/static/images/icon/topic.png"></image>
					<view class="grid-text">我的话题</view>
				</u-grid-item>

				<u-grid-item index="/pages/my-topic/my-topic">
					<image class="gn-icon" src="/static/images/icon/qz.png"></image>
					<view class="grid-text">我的圈子</view>
				</u-grid-item>

				<u-grid-item index="/pages/my-dynamic/my-dynamic">
					<image class="gn-icon" src="/static/images/icon/dt.png"></image>
					<view class="grid-text">我的动态</view>
				</u-grid-item>

				<u-grid-item index="/pages/my-collection/my-collection">
					<image class="gn-icon" src="/static/images/icon/sc.png"></image>
					<view class="grid-text">我的喜欢</view>
				</u-grid-item>
			</u-grid>
		</view>
		<!-- 服务 -->
		<!-- #ifdef MP-WEIXIN -->
		<view class="grid">
			<view>服务</view>
			<view class="btn-wrap">
				<button class="btn-contact" open-type="contact" :show-message-card="true">
					<text class="iconfont icon-kefu icon-size" style="color: #9999ff;"></text>
					<view class="txt">联系客服</view>
				</button>
				<button class="btn-contact" open-type="share">
					<text class="iconfont icon-yaoqinghaoyou1 icon-size" style="color: #79d279;"></text>
					<view class="txt">分享好友</view>
				</button>
			</view>
		</view>
		<!-- #endif -->
	</view>
</template>

<script>
	import {
		mapState,
		mapMutations
	} from 'vuex'

	export default {
		data() {
			return {
				userInfo: ""
			}
		},
		computed: {
			...mapState(['hasLogin'])
		},
		onLoad() {
			//#ifdef MP-WEIXIN
			wx.showShareMenu({
				withShareTicket: true,
				menus: ['shareAppMessage', 'shareTimeline']
			})
			//#endif
		},
		onShow() {
			if (this.$store.state.hasLogin) {
				this.getUserInfo();
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
		methods: {
			toLogin() {
				uni.navigateTo({
					url: "/pages/login/login"
				})
			},
			getUserInfo() {
				this.$H.post("/api/member/userInfo", {
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					this.userInfo = res[1].data
				})
			},
			toUcenter() {
				uni.navigateTo({
					url: "/pages/ucenter/ucenter?uid=" + this.userInfo.uid
				})
			},
			toNav(url) {
				uni.navigateTo({
					url: url
				})
			}
		}
	}
</script>

<style lang="scss">
	@import "my.css";
</style>
