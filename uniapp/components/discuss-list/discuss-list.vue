<template>
	<view>
		<block v-for="(item,index) in list" :key="index">
			<navigator :url="'/pages/discuss/discuss?id=' + item.id">
				<view class="discuss-item">
					<text class="title">{{item.title}}</text>
					<text class="desc">{{item.introduce}}</text>
					<view class="count">
						<text>阅读量 {{item.read_count}}</text>
						<text>回复量 {{item.read_count}}</text>
					</view>
				</view>
			</navigator>
		</block>
	</view>
</template>

<script>
	export default {
		props:{
			list: Array,
			loadStatus: {
				type: String,
				default: 'loading'
			}
		},
		data() {
			return {

			};
		}
	}
</script>

<style lang="scss">
	.discuss-item {
		display: flex;
		flex-direction: column;
		padding: 20rpx;
		border-bottom: 1px solid #f5f5f5;
	}

	.title {
		margin-bottom: 20rpx;
	}

	.desc {
		font-size: 12px;
		color: #616161;
	}

	.count {
		font-size: 12px;
		color: #999;
		margin-top: 20rpx;
	}
</style>
