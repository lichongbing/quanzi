<template>
	<view class="container">
		<view class="login">
			<u-button type="error" open-type="getUserInfo" @getuserinfo="userLogin" shape="circle" plain>授权登录</u-button>
		</view>
	</view>
</template>

<script>
	import {
		mapState,
		mapMutations
	} from 'vuex';

	export default {
		data() {
			return {

			};
		},
		onLoad() {

		},
		onShow() {

		},
		methods: {
			userLogin(e) {
				let that = this;
				// 获取用户信息
				uni.getUserInfo({
					lang: 'zh_CN',
					success: function(infoRes) {
						let userInfo = infoRes.userInfo;
						uni.login({
							success: function(res) {
								if (res.code) {
									that.$H.post('/api/member/wxLogin', {
										code: res.code,
										username: userInfo.nickName,
										avatar: userInfo.avatarUrl,
										province: userInfo.province,
										city: userInfo.city,
										gender: userInfo.gender
									}).then(e => {
										if (e[1].data.code === 1) {
											uni.setStorageSync("hasLogin", true);
											uni.setStorageSync("sessionKey", e[1].data.sessionKey);																					
											that.$store.commit("login"); // 更新Vuex登录状态											
											uni.navigateBack();
										}
									})
								}
							}
						});
					}
				});
			}
		}
	}
</script>

<style lang="scss">
	@import 'login.css';
</style>
