<template>
	<view>
		<u-grid :col="3" :border="false" @click="toNav">
			<u-grid-item index="1">
				<u-badge :count="msgNum.thumb_collect" :offset="[10, 50]"></u-badge>
				<image class="nav-icon" src="/static/images/icon/souc.png"></image>
				<view class="grid-text">赞和收藏</view>
			</u-grid-item>
			<u-grid-item index="2">
				<u-badge :count="msgNum.follow" :offset="[10, 50]"></u-badge>
				<image class="nav-icon" src="/static/images/icon/gz.png"></image>
				<view class="grid-text">新增关注</view>
			</u-grid-item>
			<u-grid-item index="3">
				<u-badge :count="msgNum.comment" :offset="[10, 50]"></u-badge>
				<image class="nav-icon" src="/static/images/icon/pl.png"></image>
				<view class="grid-text">评论</view>
			</u-grid-item>
		</u-grid>
		<view class="msg-wrap">
			<navigator url="#">
				<view class="msg-item">
					<text class="iconfont icon-xiaoxitongzhi i-xt"></text>
					<view class="msg-c">
						<text>系统通知</text>
						<block v-if="msgNum.sys.newest.content != null">
							<text class="desc">{{msgNum.sys.newest.content}}</text>
						</block>
						<block v-else>
							<text class="desc">暂无系统通知</text>
						</block>
					</view>
					<view class="t-wrap">
						<text class="time">{{msgNum.sys.newest.create_time}}</text>
						<text v-if="msgNum.sys.num > 0" class="num">{{msgNum.sys.num}}</text>
					</view>
				</view>
			</navigator>
			<navigator url="#">
				<view class="msg-item">
					<text class="iconfont icon-xiaoxi-tongzhi i-ts"></text>
					<view class="msg-c">
						<text>推送消息</text>
						<block v-if="msgNum.push.newest.content != null">
							<text class="desc">{{msgNum.push.newest.content}}</text>
						</block>
						<block v-else>
							<text class="desc">暂无推送消息</text>
						</block>
					</view>
					<view class="t-wrap">
						<text class="time">{{msgNum.push.newest.create_time}}</text>
						<text v-if="msgNum.push.num > 0" class="num">{{msgNum.push.num}}</text>
					</view>
				</view>
			</navigator>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				msgNum: {
					thumb_collect: 0,
					follow: 0,
					comment: 0
				}
			};
		},
		onShow() {
			this.getMsgNum();
		},
		methods: {
			getMsgNum() {
				this.$H.post("/api/message/num", {
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					if (res[1].data.push.num > 0) {
						res[1].data.push.newest.create_time = this.$u.timeFrom(res[1].data.push.newest.create_time);
					}
					if (res[1].data.sys.num > 0) {
						res[1].data.sys.newest.create_time = this.$u.timeFrom(res[1].data.sys.newest.create_time);
					}
					this.msgNum = res[1].data;
					let num = this.msgNum;
					let numCount = num.thumb_collect + num.follow + num.comment
					if (numCount > 0) {
						uni.setTabBarBadge({
							index: 2,
							text: numCount.toString()
						})
					}else{
						uni.removeTabBarBadge({
							index:2
						})
					}
				})
			},
			toNav(e) {
				uni.navigateTo({
					url: "/pages/message-list/message-list?type=" + e
				})
			}
		}
	}
</script>

<style lang="scss">
	@import 'message.css';
</style>
