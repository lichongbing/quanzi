<script>
	export default {
		onLaunch: function() {			
			//console.log(getApp().globalData.userInfo);
		},
		onShow: function() {
			// 获取消息
			if (uni.getStorageSync("sessionKey") != "") {
				this.$H.post("/api/message/num", {
					sessionKey: uni.getStorageSync("sessionKey")
				}).then(res => {
					this.msgNum = res[1].data;
					let num = this.msgNum;
					let numCount = num.thumb_collect + num.follow + num.comment
					if (numCount > 0) {
						uni.setTabBarBadge({
							index: 2,
							text: numCount.toString()
						})
					} else {
						uni.removeTabBarBadge({
							index: 2
						})
					}
				})
			}
		},
		onHide: function() {
			// console.log('App Hide');
		}
	};
</script>

<style lang="scss">
	// 发布按钮
	.plus-box {
		background-color: $themes-color;
		width: 110rpx;
		height: 110rpx;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		position: fixed;
		bottom: 200rpx;
		right: 50rpx;
		z-index: 999;
		box-shadow: 0 0 10rpx #333;
	}

	.plus-box:active {
		background-color: #eb9794;
	}

	.fixed-bottom {
		position: fixed;
		bottom: 20rpx;
		left: 20rpx;
		right: 20rpx;
	}

	@import "uview-ui/index.scss";
	@import "common/css/common.css";
	@import "common/css/iconfont.css";
</style>
