<template>
	<view class="container">
		<view class="head">
			<u-button class="plus-btn" type="error" @click="uploadImg">发布</u-button>
		</view>
		<textarea placeholder="这一刻的想法..." v-model="postContent" class="post-txt"></textarea>
		<u-upload ref="uUpload" name="Image" :action="uploadImgUrl" @on-uploaded="submit" :auto-upload="false"></u-upload>
		<u-toast ref="uToast" />
	</view>
</template>

<script>
	export default {
		data() {
			return {
				uploadImgUrl: "",
				fileList: [],
				postContent: "",
				topicId: 0,
				discussId: 0
			};
		},
		onLoad(options) {
			if (uni.getStorageSync("sessionKey") === "") {
				uni.navigateTo({
					url: "/pages/login/login"
				})
			}

			if (options.topicId) {
				this.topicId = options.topicId;
			}
			
			if (options.discussId) {
				this.discussId = options.discussId;
			}
			
			this.uploadImgUrl = this.$H.domain + '/admin/index/upload';
		},
		methods: {
			uploadImg() {
				uni.showLoading({
					mask: true,
					title: "提交中..."
				})
				this.$refs.uUpload.upload();
			},
			submit(e) {
				let mediaList = [];

				e.forEach(function(item, index) {
					mediaList.push(item.response.url)
				})
				let mediaStrr = mediaList.join(",")

				if (this.postContent == "") {
					this.$refs.uToast.show({
						title: "内容不能为空",
						type: 'error'
					})
					uni.hideLoading();
					return;
				}
				this.$H.post("/api/post/addPost", {
					content: this.postContent,
					sessionKey: uni.getStorageSync("sessionKey"),
					topicId: this.topicId,
					discussId: this.discussId,
					media: mediaStrr
				}).then(res => {
					if (res[1].data.code === 1) {
						uni.redirectTo({
							url: "/pages/post-detail/post-detail?id=" + res[1].data.pid
						})
					} else {
						this.$refs.uToast.show({
							title: res[1].data.msg,
							type: 'error'
						})
						uni.hideLoading();
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	@import 'plus-post.css';
</style>
