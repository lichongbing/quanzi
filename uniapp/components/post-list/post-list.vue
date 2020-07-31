<template>
	<view>
		<block v-for="item in list" :key="item.id">
			<navigator :url="'/pages/post-detail/post-detail?id=' + item.id" hover-class="none">
				<view class="post-item">
					<view class="post-item-top-user">
						<navigator :url="'/pages/ucenter/ucenter?uid=' + item.uid" @tap.stop="toUcenter" hover-class="none">
							<image :src="item.userInfo.avatar"></image>
						</navigator>
						<text style="color:#333">{{item.userInfo.username}}</text>
						<text style="font-size:10px">{{item.create_time | timeFrom}}</text>
					</view>
					<text class="discuss-title" :data-id="item.discuss_id" v-if="item.discuss_id > 0" @tap.stop="toDiscuss">#{{item.discuss_title}}</text>
					<view class="post-content">
						<text class="post-text">{{item.content}}</text>
						<!--一张图片-->
						<block v-if="item.media.length == 1">
							<u-lazy-load class="img-style-1" @tap.stop="previewImage(item.media[0],item.media)" :image="item.media[0]"></u-lazy-load>
						</block>
						<!--二张图片-->
						<block v-if="item.media.length == 2">
							<view class="img-style-2">
								<image v-for="(mediaItem, index2) in item.media" :key="index2" @tap.stop="previewImage(mediaItem,item.media)" mode="aspectFill" :src="mediaItem"></image>
							</view>
						</block>
						<!--三张以上图片-->
						<block v-if="item.media.length > 2">
							<view class="img-style-3">
								<image v-for="(mediaItem, index2) in item.media" :key="index2" @tap.stop="previewImage(mediaItem,item.media)"  mode="aspectFill" :src="mediaItem"></image>
							</view>
						</block>
					</view>
					<view class="interaction">
						<view class="interaction-item">
							<text class="iconfont icon-yuedu"></text>
							<text>{{item.read_count}}</text>
						</view>
						<view class="interaction-item">
							<text class="iconfont icon-pinglun"></text>
							<text>{{item.comment_count}}</text>
						</view>
						<view class="interaction-item">
							<text class="iconfont icon-dianzan1"></text>
							<text>{{item.fabulous_count}}</text>
						</view>
					</view>
				</view>
			</navigator>
		</block>
		<!-- 加载状态 -->
		<block v-if="list.length === 0 && loadStatus == 'nomore'">
			<u-empty text="暂无内容" mode="list"></u-empty>
		</block>
		<block v-else>
			<u-loadmore bg-color="#f5f5f5" :status="loadStatus" />
		</block>
	</view>
</template>

<script>
	export default {
		props: {
			list: Array,
			loadStatus: String
		},
		methods: {
			previewImage(url,urls) {
				uni.previewImage({
					current: url, // 当前显示图片的http链接
					urls: urls // 需要预览的图片http链接列表
				})
			},
			toUcenter() {},
			toDiscuss(e) {
				uni.navigateTo({
					url: "/pages/discuss/discuss?id=" + e.currentTarget.dataset.id
				})
			}
		}
	}
</script>

<style lang="scss">
	.post-item {
		width: 100%;
		height: auto;
		line-height: 1.5em;
		background: #fff;
		margin-bottom: 10rpx;
		overflow: hidden;
		padding-bottom: 20rpx;
	}

	.post-item .post-content .img-style-1 {
		display: block;
		width: 100%;
		max-height: 600rpx;
		border-radius: 5px;
		overflow: hidden;
	}

	.post-item .post-content .img-style-2 {
		display: flex;
	}

	.post-item .post-content .img-style-2 image {
		margin: 5rpx;
		border-radius: 5px;
		width: 100%;
		height: 294rpx;

	}

	.post-item .post-content .img-style-3 {
		display: flex;
		flex-wrap: wrap;
	}

	.post-item .post-content .img-style-3 image {
		width: 31.3%;
		height: 200rpx;
		margin: 1%;
		border-radius: 5px;
	}

	.post-item-bottom {
		margin: 20rpx;
	}

	.post-item-bottom-right {
		width: 50%;
		height: 100%;
		float: right;
		text-align: center;
	}

	.post-item-bottom text {
		font-size: 12px;
		color: #999;
		margin-right: 10rpx;
	}

	.post-content {
		width: 85%;
		height: auto;
		margin-left: 85rpx;
		margin-top: 20rpx;
		font-size: 14px;
	}

	.post-item-top-user {
		width: 100%;
		height: 80rpx;
		line-height: 50rpx;
		margin-bottom: 20rpx;
		margin: 20rpx;
		font-size: 14px;
	}

	.post-item-top-user image {
		width: 80rpx;
		height: 80rpx;
		border-radius: 50%;
		float: left;
	}

	.post-item-top-user text {
		display: block;
		width: 60%;
		height: 40rpx;
		float: left;
		line-height: 50rpx;
		color: #999;
		margin-left: 10rpx;
	}

	.post-text {
		display: block;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 4;
		overflow: hidden;
	}

	.discuss-title {
		height: 30rpx;
		padding: 10rpx;
		line-height: 30rpx;
		border-radius: 20rpx;
		font-size: 12px;
		background: #f0f0f5;
		color: #68c778;
		text-align: center;
		margin-left: 85rpx;
	}

	.interaction {
		height: 70rpx;
		line-height: 70rpx;
		font-size: 12px;
		color: #333;
		margin-top: 20rpx;
	}

	.interaction-item {
		width: 30%;
		height: 100%;
		float: left;
		text-align: center;
		margin: 0 10rpx;
	}

	.interaction-item text {
		margin: 0 10rpx;
	}

	.interaction-item van-icon {
		position: relative;
		top: 10rpx;
	}
</style>
