<template>
	<view>
		<block v-for="(item,index) in list" :key="index">
			<navigator :url="'/pages/topic-detail/topic-detail?id='+item.id">
				<view class="topic-item">
					<image :src="item.cover_image"></image>
					<view>
						<view>{{item.topic_name}}</view>
						<text class="user-count">{{item.description}}</text>
					</view>
					<view class="num">
						<text class="user-count">{{item.userCount}}个成员</text>
						<text class="post-count">{{item.postCount}}个作品</text>
					</view>
				</view>
			</navigator>
		</block>
		<!-- 加载状态 -->
		<block v-if="loadStatus != 'none'">
			<block v-if="list.length === 0 && loadStatus == 'nomore'">
				<u-empty text="暂无相关圈子" mode="favor"></u-empty>
			</block>
			<block v-else>
				<u-loadmore :status="loadStatus" bgColor="#f5f5f5" />
			</block>
		</block>
	</view>
</template>

<script>
	export default {
		props: {
			list: Array,
			loadStatus: String
		},
		data() {
			return {

			};
		}
	}
</script>

<style lang="scss">
	.topic-item {
		display: flex;
		background-color: #fff;
		align-items: center;
		padding: 20rpx;
		border-bottom: 1px solid #f5f5f5;
	}

	.topic-item .num {
		margin-left: auto;
	}

	.topic-item image {
		width: 100rpx;
		height: 100rpx;
		border-radius: 10rpx;
		margin-right: 10rpx;
	}

	.topic-item .btn-gz {
		margin-left: auto;
		margin-right: 20rpx;
	}

	.topic-item text {
		font-size: 10px;
		color: #999;
		margin: 0 10rpx;
	}
</style>
